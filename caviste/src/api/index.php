<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \RedBeanPHP\R as R;

require '../../vendor/autoload.php';

// instantiate the App object
$app = new \Slim\App();

// Add route callbacks
$app->get('/', function (Request $request, Response $response, array $args) {
    return $response->withStatus(200)->write('Hello World!');
});

$app->get('/wines[/]', function(Request $request, Response $response, array $args) {
    R::setup('mysql:host=localhost;dbname=cellar','root','root');
    
    $wines = R::findAll('wine');
    
    R::close();
    
    $newResponse = $response->withJson($wines);
    
    return $newResponse->withStatus(200);
});

$app->get('/wines/search/{keyword}[/]', function(Request $request, Response $response, array $args) {
    R::setup('mysql:host=localhost;dbname=cellar','root','root');
    
    $wines = R::find('wine',"`name` LIKE ?",["%{$args['keyword']}%"]);
    
    R::close();
    
    $newResponse = $response->withJson($wines);
    
    return $newResponse->withStatus(200);
});

$app->get('/wines/{id}[/]', function(Request $request, Response $response, array $args) {   
    R::setup('mysql:host=localhost;dbname=cellar','root','root');
    
    $wines = R::find('wine',"`id`={$args['id']}");
    
    R::close();
    
    $newResponse = $response->withJson($wines);
    
    return $newResponse->withStatus(200);
});

$app->post('/wines[/]', function(Request $request, Response $response, array $args) {
    R::setup('mysql:host=localhost;dbname=cellar','root','root');
    
    $wine = R::dispense('wine');
    
    $formData = $request->getParsedBody();

    $wine->import($formData);

    try {
        $id = R::store($wine);
        
        $newResponse = $response->withJson(true);
    } catch (Exception $ex) {
        $newResponse = $response->withJson(false);
    }
    
    R::close();
    
    return $newResponse->withStatus(200);
});

$app->put('/wines/{id}[/]', function(Request $request, Response $response, array $args) {
    R::setup('mysql:host=localhost;dbname=cellar','root','root');
    //Rechercher le vin à modifier
    $wine = R::load('wine',$args['id']);
    
    if($wine['id']!=0) {
        $formData = $request->getParsedBody();
        
        $wine->import($formData);
        
        R::store($wine);
        
        $newResponse = $response->withJson(true);
    } else {    //Sinon le vin n'a pas pu être trouvé
        $newResponse = $response->withJson(false);
    }
    
    R::close();
    
    return $newResponse->withStatus(200);
});

$app->delete('/wines/{id}[/]', function(Request $request, Response $response, array $args) {
    R::setup('mysql:host=localhost;dbname=cellar','root','root');
    //Rechercher le vin à supprimer
    $wine = R::load('wine',$args['id']);
    
    if($wine['id']!=0) {
        R::trash($wine);    //Supprimer le vin
        //Chercher à nouveau le vin
        $wine = R::load('wine',$args['id']);
        
        if($wine['id']==0) {    //Si le vin n'existe plus
            $newResponse = $response->withJson(true);
        } else {    //Sinon le vin n'a pas pu être supprimé!
            $newResponse = $response->withJson(false);
        }
    } else {    //Sinon le vin n'a pas pu être trouvé
        $newResponse = $response->withJson(false);
    }
    
    R::close();
    
    return $newResponse->withStatus(200);
});

$app->post('/wines/picture[/]', function(Request $request, Response $response, array $args) {
    $path = __DIR__.'/../../pics';
    
    $uploadedFiles = $request->getUploadedFiles();   //var_dump($file);
    
    $file = $uploadedFiles['pictureFile'];
    
    if (!empty($file) && $file->getError() === UPLOAD_ERR_OK) {
        $uploadFileName = $file->getClientFilename();
        $file->moveTo("$path/$uploadFileName");
        
        $newResponse = $response->withJson(true);
    } else {
        $newResponse = $response->withJson(false);
    }
    
    return $newResponse->withStatus(200);
});

// Run application
$app->run();
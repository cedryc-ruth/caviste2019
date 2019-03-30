<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

// instantiate the App object
$app = new \Slim\App();

// Add route callbacks
$app->get('/', function (Request $request, Response $response, array $args) {
    return $newResponse->withStatus(200)->write('Hello World!');
});

$app->get('/wines', function(Request $request, Response $response, array $args) {
    $wines = [
        [
            'id' => 7,
            'name' => 'Pinot Noir',
            'grapes' => 'Rouge',
            'country' => 'France',
        ],
        [
            'id' => 8,
            'name' => 'Pink Flamingo',
            'grapes' => 'Rosé',
            'country' => 'France',
        ],
    ];
    
    $newResponse = $response->withJson($wines);
    
    return $newResponse->withStatus(200);
});

$app->get('/wines/search/{keyword}', function(Request $request, Response $response, array $args) {
    return $response->write('Search wines?');
});

$app->get('/wines/{id}', function(Request $request, Response $response, array $args) {
    return $response->write('Get wine.');
});

$app->post('/wines', function(Request $request, Response $response, array $args) {
    return $response->write('Create wine.');
});

$app->put('/wines/{id}', function(Request $request, Response $response, array $args) {
    return $response->write('Update wine.');
});

$app->delete('/wines/{id}', function(Request $request, Response $response, array $args) {
    return $response->write('Delete wine.');
});

$app->post('/wines/picture', function(Request $request, Response $response, array $args) {
    return $response->write('Upload wine picture!');
});

// Run application
$app->run();
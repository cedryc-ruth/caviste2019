<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');

//Mocking du datasource
$wines = [
    [
        'id' => 1,
        'name' => 'La Chevalière',
        'grapes' => 'Pinot noir',
        'country' => 'France',
        'region' => 'Languedoc',
        'year' => 2013,
        'notes' => 'sdsdsd',
        'pictures' => 'pics/vin_1.jpg'
    ],
    [
        'id' => 2,
        'name' => 'Rose Gris',
        'grapes' => 'Rosé',
        'country' => 'France',
        'region' => 'Pays d\'Oc',
        'year' => 2018,
        'notes' => 'azazazaza',
        'picture' => 'pics/vin_2.jpg'
    ],
    [
        'id' => 3,
        'name' => 'Château Grand Corbin',
        'grapes' => 'Saint-Emilion',
        'country' => 'France',
        'region' => 'Bordeaux',
        'year' => 2012,
        'notes' => 'erererere',
        'picture' => 'pics/vin_3.jpg'
    ],
    [
        'id' => 4,
        'name' => 'Château Laroze',
        'grapes' => 'Saint-Emilion',
        'country' => 'France',
        'region' => 'Bordeaux',
        'year' => 2015,
        'notes' => 'dfdfdfd',
        'picture' => 'pics/vin_4.jpg'
    ],
    [
        'id' => 5,
        'name' => 'Vinas Del Vero ',
        'grapes' => 'Cabernet Sauvignon',
        'country' => 'Espagne',
        'region' => ' Aragón',
        'year' => 2018,
        'notes' => 'eseses',
        'picture' => 'pics/vin_5.jpg'
    ],
    [
        'id' => 6,
        'name' => 'Bonastro',
        'grapes' => 'Cabernet Sauvignon',
        'country' => 'Chili',
        'region' => 'Central Valley',
        'year' => 2018,
        'notes' => 'chchchc',
        'picture' => 'pics/vin_6.jpg'
    ],
    [
        'id' => 7,
        'name' => 'Château la Verrière',
        'grapes' => 'Bordeaux Supérieur',
        'country' => 'France',
        'region' => 'Bordeaux',
        'year' => 2016,
        'notes' => 'sdsdsd',
        'picture' => 'pics/vin_7.jpg'
    ],
    [
        'id' => 8,
        'name' => 'Pink Flamingo',
        'grapes' => 'Rosé',
        'country' => 'France',
        'region' => 'Provence',
        'year' => 2016,
        'notes' => 'pfpfpfpf',
        'picture' => 'pics/vin_8.jpg'
    ],
];


//Dictionnaire de requêtes
//Retrouve tous les vins de la base de données
//GET	/api/wines
if($_SERVER['REQUEST_METHOD']=='GET' && $_SERVER['REQUEST_URI']=='/mock.php/api/wines') {
    echo json_encode($wines);
} elseif($_SERVER['REQUEST_METHOD']=='GET' 
        && strpos($_SERVER['REQUEST_URI'],'/mock.php/api/wines/search/')===0) {
    //Recherche les vins dont le nom contient ‘Chateau’
    //GET	/api/wines/search/Chateau
    if(preg_match('#/chateau$#',$_SERVER['REQUEST_URI'])===1) {
        echo json_encode([
            $wines[2],
            $wines[3],
            $wines[6],
        ]);
    } else {
        echo json_encode([]);
    }
} elseif($_SERVER['REQUEST_METHOD']=='GET' && $_SERVER['REQUEST_URI']=='/mock.php/api/wines/8') {
    //Retrouve le vin dont l'id == 8
    //GET	/api/wines/8
    echo json_encode([$wines[7]]);
} elseif($_SERVER['REQUEST_METHOD']=='POST' && $_SERVER['REQUEST_URI']=='/mock.php/api/wines') {
    //Ajoute un vin dans la base de données
    //POST	/api/wines
    $wine = [
        'id' => 9,
        'name' => 'Nouveau vin',
        'grapes' => 'Cabernet',
        'country' => 'Belgique',
        'region' => 'Namur',
        'year' => 2019,
        'notes' => 'test test',
        'picture' => 'pics/generic.jpg'
    ];

    $wines[] = $wine;

    echo true;
} elseif($_SERVER['REQUEST_METHOD']=='PUT' && $_SERVER['REQUEST_URI']=='/mock.php/api/wines/8') {
    //Modifie les données du vin dont l'id == 8
    //PUT	/api/wines/8
    $wine = [
        'id' => 8,
        'name' => 'Pink',
        'grapes' => 'Rosé',
        'country' => 'France',
        'region' => 'Provence',
        'year' => 2019,
        'notes' => 'Test ',
        'picture' => 'pics/vin_8.jpg'
    ];

    foreach($wines as &$w) {
        if($w['id']==$wine['id']) {
            $w['id'] = $wine['id'];
            break;
        }
    }

    echo true;
} elseif($_SERVER['REQUEST_METHOD']=='DELETE' && $_SERVER['REQUEST_URI']=='/mock.php/api/wines/8') {
    //Supprime le vin dont l'id == 8
    //DELETE	/api/wines/8
    for($i=0;$i<sizeof($wines);$i++) {
        if($wines[$i]['id']==8) {
            break;
        }
    }

    unset($wines[$i]);

    echo true;
} elseif($_SERVER['REQUEST_METHOD']=='POST' && $_SERVER['REQUEST_URI']=='/mock.php/api/wines/picture') {
    //Ajoute un image de vin dans le dossier pics du serveur
    //POST	/api/wines/picture    
    if(isset($_FILES['pictureFile']) && empty($_FILES['pictureFile']['error'])) {
        $source = $_FILES['pictureFile']['tmp_name'];
        $destination = 'pics/'.$_FILES['pictureFile']['name'];

        if(move_uploaded_file($source, $destination)) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    } else {
        echo json_encode(false);
    }
}


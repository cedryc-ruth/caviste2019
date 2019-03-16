console.log('Mon cellier SPA');

/* Une fois la page chargée,
 * récupérer la liste des vins en interrogeant l'API
 * 
 * Une fois la liste obtenue,
 * afficher les vins dans le UL d'id liste
 */

window.onload = function() {
    const apiURL = 'http://caviste.localhost/mock.php';
    
    let listeUL = document.getElementById('liste');
    //console.log(listeUL);
    
    let options = {
        method: 'GET',
    };
    
    fetch(apiURL + '/api/wines', options)
        .then(function(response){
            console.log(response);
        })
        .catch(function(error){
            console.log('Une erreur est survenue: ' + error)
        });
}
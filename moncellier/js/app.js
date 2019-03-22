console.log('Mon cellier SPA');
/**
 * ATTENTION: n'oubliez pas de désactiver le cache du navigateur
 * Onglet Network => cocher la case Disable cache
 * 
 */

/* Une fois la page chargée,
 * récupérer la liste des vins en interrogeant l'API
 * 
 * Une fois la liste obtenue,
 * afficher les vins dans le UL d'id liste
 */
const apiURL = 'http://caviste.localhost/mock.php';

function showWines(wines, listeUL) {
    //Affichage des vins
    listeUL.innerHTML = '';     //Effacement de la liste

    let strVins = '';
    wines.forEach(function(wine) {
        strVins += '<li class="list-group-item" data-id="'+wine.id+'">'+wine.name+'</li>';
    });

    listeUL.innerHTML = strVins;    //Affichage des vins
    
    //Gestionnaire de click
    let listeLIs = document.querySelectorAll('ul#liste li');
    
    listeLIs.forEach(function(li) {
        li.onclick = function(){
            let idWine = this.getAttribute('data-id');
            
            fetch(apiURL + '/api/wines/' + idWine)
            .then(function(response){
                console.log(response);
        
                response.json().then(function(wines) {
                    console.log(wines);
                    
                });
            })
            .catch(function(error){
                console.log('Une erreur est survenue: ' + error)
            });
        };
    });
    
}

window.onload = function() {   
    let listeUL = document.getElementById('liste');
    //console.log(listeUL);
    
    let options = {
        method: 'GET',
    };
    
    fetch(apiURL + '/api/wines', options)
        .then(function(response){
            response.json().then(function(wines) {
                console.log(wines);
                
                showWines(wines, listeUL);                
            });
        })
        .catch(function(error){
            console.log('Une erreur est survenue: ' + error)
        });
        
    let btSearch = document.getElementById('btSearch');
    
    btSearch.onclick = function() {
        //Récupérer le mot-clé tapé dans le formulaire de recherche
        let keyword = this.form.keyword.value;
        
        fetch(apiURL + '/api/wines/search/' + keyword)
            .then(function(response){
                console.log(response);
        
                response.json().then(function(wines) {
                    console.log(wines);
                    
                    showWines(wines, listeUL);
                });
            })
            .catch(function(error){
                console.log('Une erreur est survenue: ' + error)
            });
    };
    
}
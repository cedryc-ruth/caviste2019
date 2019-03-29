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
const SERVER_URL = 'http://caviste.localhost/';

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
                //console.log(response);
        
                response.json().then(function(wines) {
                    console.log(wines);
                    
                    if(wines.length>0) {
                        let idInput = document.getElementById('idWine');
                        let nameInput = document.getElementById('name');
                        let grapesInput = document.getElementById('grapes');
                        let countryInput = document.getElementById('country');
                        let regionInput = document.getElementById('region');
                        let yearInput = document.getElementById('year');
                        let pictureImg = document.getElementById('picture');
                        let notesInput = document.getElementById('notes');
                        
                        idInput.value = wines[0].id;
                        nameInput.value = wines[0].name;
                        grapesInput.value = wines[0].grapes;
                        countryInput.value = wines[0].country;
                        regionInput.value = wines[0].region;
                        yearInput.value = wines[0].year;
                        pictureImg.src = SERVER_URL + wines[0].picture;
                        notesInput.value = wines[0].notes;
                    }
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
    
    btSave.onclick = function() {
        //Récupérer les données du formulaire
        let idInput = document.getElementById('idWine');
        let nameInput = document.getElementById('name');
        let grapesInput = document.getElementById('grapes');
        let countryInput = document.getElementById('country');
        let regionInput = document.getElementById('region');
        let yearInput = document.getElementById('year');
        let pictureImg = document.getElementById('picture');
        let notesInput = document.getElementById('notes');

        let wine = {
            id: idInput.value,
            name: nameInput.value,
            grapes: grapesInput.value,
            country: countryInput.value,
            region: regionInput.value,
            year: yearInput.value,
            picture: pictureImg.src.substring(SERVER_URL.length-1),
            notes: notesInput.value,
        };
        
        if(wine.id != '') {
            console.log('UPDATE');

            console.log(wine);
            //Envoyer les données au serveur en méthode PUT
            let options = {
                method: 'PUT',
                body: wine,
            };

            fetch(apiURL + '/api/wines/'+ wine.id, options)
            .then(function(response) {
                response.json().then(function(data) {
                    console.log(data);
                });
            })
            .catch(function(error) {
                console.log(error);
            });
        } else {
            console.log('SAVE');
            
            //Envoyer les données au serveur en méthode POST
            let options = {
                method: 'POST',
                body: wine,
            };
            
            fetch(apiURL + '/api/wines', options)
            .then(function(response) {
                response.json().then(function(data) {
                    console.log(data);
                });
            })
            .catch(function(error) {
                console.log(error);
            });
        }
    };
    
    btDelete.onclick = function() {
        console.log('DELETE');
        //Récupérer les données du formulaire
        let idInput = document.getElementById('idWine');
        
        let wine = {
            id: idInput.value,
        };
        
        console.log(wine);
        //Envoyer les données au serveur en méthode PUT
        let options = {
            method: 'DELETE',
            body: wine,
        };
        
        fetch(apiURL + '/api/wines/'+ wine.id, options)
        .then(function(response) {
            response.json().then(function(data) {
                console.log(data);
            });
        })
        .catch(function(error) {
            console.log(error);
        });
    };
    
    btAdd.onclick = function() {
        //Vider le formulaire
        let frmWine = document.getElementById('frmWine');
        frmWine.reset();
        
        let pictureImg = document.getElementById('picture');
        pictureImg.src = SERVER_URL + '/pics/generic.jpg';
        
        //Donner le focus au champ "nom"
        let nameInput = document.getElementById('name');
        nameInput.focus();
    };
    
    btImgChange.onclick = function() {
        
    };
    
}
document.addEventListener("DOMContentLoaded", function () {
    const ajouterBouton = document.getElementById("ajouter");
    const enleverBouton = document.getElementById("enlever");
    const listeElements = document.getElementById("liste-elements");
    let elementCounter = 1;

    ajouterBouton.addEventListener("click", function () {
        
        listeElements.textContent = elementCounter;
        elementCounter++;
    });

    enleverBouton.addEventListener("click", function () {
        listeElements.textContent = elementCounter;
            elementCounter--
    });
});
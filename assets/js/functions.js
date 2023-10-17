document.addEventListener("DOMContentLoaded", function () {
    const ajouterBouton = document.getElementById("ajouter");
    const enleverBouton = document.getElementById("enlever");
    const listeElements = document.getElementById("liste-elements");
    let elementCounter = 1;

    ajouterBouton.addEventListener("click", function () {
        const nouvelElement = document.createElement("li");
        nouvelElement.textContent = "Élément " + elementCounter;
        listeElements.appendChild(nouvelElement);
        elementCounter++;
    });

    enleverBouton.addEventListener("click", function () {
        const dernierElement = listeElements.lastChild;
        if (dernierElement) {
            listeElements.removeChild(dernierElement);
            elementCounter--;
        }
    });
});
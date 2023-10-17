document.addEventListener("DOMContentLoaded", function () {
    const ajouterBoutons = document.querySelectorAll(".btn-success");
    const enleverBoutons = document.querySelectorAll(".btn-danger");
    const inputNumbers = document.querySelectorAll(".form-control");
  
    ajouterBoutons.forEach((ajouterBouton, index) => {
      let elementCounter = 0;
  
      ajouterBouton.addEventListener("click", function () {
        elementCounter++;
        inputNumbers[index].value = elementCounter;
      });
  
      enleverBoutons[index].addEventListener("click", function () {
        if (elementCounter > 0) {
          elementCounter--;
          inputNumbers[index].value = elementCounter;
        }
      });
    });
  });
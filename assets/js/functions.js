document.addEventListener("DOMContentLoaded", function () {
  const ajouterBouton = document.getElementById("ajouter");
  const enleverBouton = document.getElementById("enlever");
  const inputNumber = document.querySelectorAll("#input-number");
  let elementCounter = 0;

  ajouterBouton.addEventListener("click", function () {
    inputNumber.value = elementCounter;
    elementCounter++;
    inputNumber.value = elementCounter;
  });

  enleverBouton.addEventListener("click", function () {
    inputNumber.value = elementCounter;
    elementCounter--;
    inputNumber.value = elementCounter;
  });
});

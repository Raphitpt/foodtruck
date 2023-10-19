document.addEventListener("DOMContentLoaded", function () {
  const platName = document.querySelectorAll(".card-title");
  const platPrice = document.querySelectorAll(".card-price");
  const platComposition = document.querySelectorAll(".card-text");
  const idPlat = document.querySelectorAll(".id_plats");
  const addBoutons = document.querySelectorAll(".btn-primary");
  const ajouterBoutons = document.querySelectorAll(".btn-success");
  const enleverBoutons = document.querySelectorAll(".btn-danger");
  const inputNumbers = document.querySelectorAll(".form-control");
  const divSupplement = document.querySelector(".supplements");
  const closeSupplement = document.querySelector(".cross_close");

  let panier = [];

  ajouterBoutons.forEach((ajouterBouton, index) => {
    let elementCounter = 0;

    ajouterBouton.addEventListener("click", function () {
      elementCounter++;
      inputNumbers[index].value = elementCounter;

      // You should check if the item is already in the cart and update its quantity
      let id = idPlat[index].value;
      let itemIndex = panier.findIndex(item => item.id === id);

      if (itemIndex !== -1) {
        // Item already in cart, update quantity
        panier[itemIndex].quantite = elementCounter;
      } else {
        // Item not in cart, add it
        panier.push({
          id: id,
          nom: platName[index].innerHTML,
          prix: platPrice[index].innerHTML,
          composition: platComposition[index].innerHTML,
          quantite: elementCounter,
        });
      }

      console.log(panier);
    });

    enleverBoutons[index].addEventListener("click", function () {
      if (elementCounter > 0) {
        elementCounter--;
        inputNumbers[index].value = elementCounter;

        let id = idPlat[index].value;
        let itemIndex = panier.findIndex(item => item.id === id);

        if (itemIndex !== -1) {
          if (elementCounter === 0) {
            // Remove the item from the cart when the quantity reaches 0
            panier.splice(itemIndex, 1);
          } else {
            // Update the quantity
            panier[itemIndex].quantite = elementCounter;
          }
        }

        console.log(panier);
      }
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const platName = document.querySelectorAll(".card-title");
  const platPrice = document.querySelectorAll(".card-price");
  const platComposition = document.querySelectorAll(".card-text");
  const idPlat = document.querySelectorAll(".id_plats");
  const addBoutons = document.querySelectorAll(".btn-primary");
  const enleverBoutons = document.querySelectorAll(".btn-danger");
  const inputNumbers = document.querySelectorAll(".form-control");
  const divSupplement = document.querySelector(".supplements");
  const closeSupplement = document.querySelector(".cross_close");
  const ajouterBoutons = document.querySelectorAll(".btn-success");
  const orderButton = document.querySelector(".order-button");
  orderButton.style.display = "none";

  let panier = JSON.parse(sessionStorage.getItem('panier')) || [];

  function updateInputNumbers() {
    panier.forEach(function (item) {
      const id = item.id;
      const inputNumber = document.querySelector(`.id_plats[value="${id}"] + .form-control`);
      if (inputNumber) {
        inputNumber.value = item.quantite;
      }
    });
  }


  // Update input numbers when the page loads
  updateInputNumbers();
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

      const panierDiv = document.querySelector(".panier");
      panierDiv.innerHTML = generatePanierHTML(panier);
      console.log(panier); 
      updateInputNumbers();
      sessionStorage.setItem('panier', JSON.stringify(panier));

      // Afficher le bouton "order-button" uniquement s'il y a des articles dans le panier
      orderButton.style.display = panier.length > 0 ? "block" : "none";
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

        // Recalculer le total du panier
        const panierTotal = calculateTotal(panier);

        // Mettre à jour le contenu du panier
        const panierDiv = document.querySelector(".panier");
        updateInputNumbers();
        panierDiv.innerHTML = generatePanierHTML(panier, panierTotal);

        // Afficher le bouton "order-button" uniquement s'il y a des articles dans le panier
        orderButton.style.display = panier.length > 0 ? "block" : "none";
      }
      sessionStorage.setItem('panier', JSON.stringify(panier));
    });
  });
  function calculateTotal(panier) {
    let total = 0;
    panier.forEach(function (plat) {
      // Convertir le prix et la quantité en nombre à virgule flottante
      const prix = parseFloat(plat.prix);
      const quantite = parseFloat(plat.quantite);
  
      // Calculer le total pour cet article
      const articleTotal = prix * quantite;
  
      // Ajouter le total de cet article au total général
      total += articleTotal;
    });
    return total;
  }
  
  function generatePanierHTML(panier) {
    let html = '<ul>';
    panier.forEach(function (plat) {
      // Convertir le prix et la quantité en nombre à virgule flottante
      const prix = parseFloat(plat.prix);
      const quantite = parseFloat(plat.quantite);
  
      // Calculer le total pour cet article
      const articleTotal = prix * quantite;
  
      html += `<li>${plat.nom} - ${prix}€ - Quantité: ${quantite} - Total: ${articleTotal}€</li>`;
    });
  
    // Calculer le total de l'ensemble du panier
    const panierTotal = calculateTotal(panier);
  
    html += '</ul>';
    html += `<p>Total du panier : ${panierTotal}€</p>`;
    return html;
  }
  
  function calculateTotal(panier) {
    let total = 0;
    panier.forEach(function (plat) {
      // Convertir le prix et la quantité en nombre à virgule flottante
      const prix = parseFloat(plat.prix);
      const quantite = parseFloat(plat.quantite);
  
      // Calculer le total pour cet article
      const articleTotal = prix * quantite;
  
      // Ajouter le total de cet article au total général
      total += articleTotal;
    });
    return total;
  }
  
  function generatePanierHTML(panier, panierTotal) {
    let html = '<ul>';
    panier.forEach(function (plat) {
      // Convertir le prix et la quantité en nombre à virgule flottante
      const prix = parseFloat(plat.prix);
      const quantite = parseFloat(plat.quantite);
  
      // Calculer le total pour cet article
      const articleTotal = prix * quantite;
  
      html += `<li>${plat.nom} - ${prix}€ - Quantité: ${quantite} - Total: ${articleTotal}€</li>`;
    });
  
    html += '</ul>';
    html += `<p>Total du panier : ${panierTotal}€</p>`;
    return html;
  };

  function updateCartDisplay() {
    const panierTotal = calculateTotal(panier);
    const panierDiv = document.querySelector(".panier");
    panierDiv.innerHTML = generatePanierHTML(panier, panierTotal);
  }
  
  

  updateCartDisplay();


});

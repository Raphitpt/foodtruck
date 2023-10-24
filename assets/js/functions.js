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

  let panier = JSON.parse(sessionStorage.getItem("panier")) || [];

  function updateInputNumbers() {
    console.log(panier);
    panier.forEach(function (item) {
      const id = item.id;
      const inputNumber = document.getElementById(`input-number-${id}`);
      if (inputNumber) {
        inputNumber.value = item.quantite;
      }
    });
  }

  updateInputNumbers();

  ajouterBoutons.forEach((ajouterBouton, index) => {
    let elementCounter = inputNumbers[index].value;

    ajouterBouton.addEventListener("click", function () {
      elementCounter++;
      inputNumbers[index].value = elementCounter;
      let id = idPlat[index].value;
      let itemIndex = panier.findIndex((item) => item.id === id);

      if (itemIndex !== -1) {
        panier[itemIndex].quantite = elementCounter;
      } else {
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
      updateInputNumbers();
      updateCartDisplay();
      sessionStorage.setItem("panier", JSON.stringify(panier));

      // Afficher le bouton "order-button" uniquement s'il y a des articles dans le panier
      orderButton.style.display = panier.length > 0 ? "block" : "none";
    });

    enleverBoutons[index].addEventListener("click", function () {
      if (elementCounter > 0) {
        elementCounter--;
        inputNumbers[index].value = elementCounter;

        let id = idPlat[index].value;
        let itemIndex = panier.findIndex((item) => item.id === id);

        if (itemIndex !== -1) {
          if (elementCounter === 0) {
            panier.splice(itemIndex, 1);
          } else {
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
      updateCartDisplay();
      sessionStorage.setItem("panier", JSON.stringify(panier));
    });
  });

  function generatePanierHTML(panier) {
    let html = "<ul>";
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

    html += "</ul>";
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
    let html = "<ul>";
    panier.forEach(function (plat) {
      // Convertir le prix et la quantité en nombre à virgule flottante
      const prix = parseFloat(plat.prix);
      const quantite = parseFloat(plat.quantite);

      // Calculer le total pour cet article
      const articleTotal = prix * quantite;

      html += `<li>${plat.nom} - ${prix}€ - Quantité: ${quantite} - Total: ${articleTotal}€ <button>Supprimer</button></li>`;
    });

    html += "</ul>";
    if(panier.length > 0){
      html += `<p>Total du panier : ${panierTotal}€</p>`;
    } else {
      html += `<i class="fa-solid fa-cart-shopping"></i>`;
    }
    
    return html;
  }

  function updateCartDisplay() {
    const panierTotal = calculateTotal(panier);
    console.log(panierTotal);
    const panierDiv = document.querySelector(".panier");
    panierDiv.innerHTML = generatePanierHTML(panier, panierTotal);
  }

  updateCartDisplay();
});

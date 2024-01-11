document.addEventListener("DOMContentLoaded", function () {
  const platName = document.querySelectorAll(".card-title");
  const platPrice = document.querySelectorAll(".card-price");
  const platComposition = document.querySelectorAll(".card-text");
  const idPlat = document.querySelectorAll(".id_plats");
  const addBoutons = document.querySelectorAll(".btn-primary");
  const enleverBoutons = document.querySelectorAll(".btn-danger");
  // const inputNumbers = document.querySelectorAll(".form-control");
  const divSupplement = document.querySelector(".supplements");
  const closeSupplement = document.querySelector(".cross_close");
  const ajouterBoutons = document.querySelectorAll(".button_add");

  let panierDiv = document.querySelector(".panier");

  // Vérifier si la div est vide
  if (panierDiv.innerHTML.trim() === "") {
    // Ajouter une classe pour styliser l'icône dans un rond
    panierDiv.classList.add("icon-in-circle");
    let icon = '<i class="fa-solid fa-cart-shopping"></i>';
    panierDiv.appendChild(icon);
  }

  // update js functions

  let panier = JSON.parse(sessionStorage.getItem("panier")) || [];

  // Fonction pour mettre à jour les inputs lors du chargement de la page (sessionStorage)
  function updateInputNumbers() {
    console.log(panier);
    panier.forEach(function (item) {
      const id = item.id;
      const inputNumber = document.getElementById(`input-number-${id}`);
      const footerCard = document.getElementById(`supplement-card-${id}`);
      if (inputNumber) {
        inputNumber.value = item.quantite;
      }

      // if (footerCard.childElementCount === 0) {
      //   let footerHTML = '<button class="btn btn-danger btn-sm">Ajouter un supplément</button>';
      //   footerCard.innerHTML = footerHTML;
      // }
    });
  }

  updateInputNumbers();

  ajouterBoutons.forEach((ajouterBouton, index) => {
    // let elementCounter = inputNumbers[index].value;
    let elementCounter = 0;
    ajouterBouton.addEventListener("click", function () {
      elementCounter++;
      // inputNumbers[index].value = elementCounter;
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

    // enleverBoutons[index].addEventListener("click", function () {
    //   if (elementCounter > 0) {
    //     elementCounter--;
    //     inputNumbers[index].value = elementCounter;

    //     let id = idPlat[index].value;
    //     let itemIndex = panier.findIndex((item) => item.id === id);
    //     const footerCard = document.getElementById(`supplement-card-${id}`);

    //     if (itemIndex !== -1) {
    //       if (elementCounter === 0) {
    //         panier.splice(itemIndex, 1);
    //         footerCard.innerHTML = "";
    //       } else {
    //         panier[itemIndex].quantite = elementCounter;
    //       }
    //     }

    //     // Recalculer le total du panier
    //     const panierTotal = calculateTotal(panier);

    //     // Mettre à jour le contenu du panier
    //     const panierDiv = document.querySelector(".panier");
    //     updateInputNumbers();
    //     panierDiv.innerHTML = generatePanierHTML(panier, panierTotal);

    //     // Afficher le bouton "order-button" uniquement s'il y a des articles dans le panier
    //     orderButton.style.display = panier.length > 0 ? "block" : "none";
    //   }
    //   updateCartDisplay();
    //   sessionStorage.setItem("panier", JSON.stringify(panier));
    // });
  });

  // Fonction pour calculer le total du panier

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

  // // Fonction pour générer le HTML du panier
  function generatePanierHTML(panier, panierTotal) {
    let html = "<ul>";
    panier.forEach(function (plat) {
      // Convertir le prix et la quantité en nombre à virgule flottante
      const prix = parseFloat(plat.prix);
      const quantite = parseFloat(plat.quantite);

      // Calculer le total pour cet article
      const articleTotal = prix * quantite;

      html += `<li class="list_commande">
      <i class="fa-solid fa-xmark"></i>
      <div class="div_img_commande">
        <img src="./assets/img/Fouées_angevines_avec_rillettes.JPG"class="img_commande">
      </div>
      <div class="name_plat_commande">
        <p>${plat.nom}<p>
        <p>Supléments<p>
        <p>${prix} €</p>
      </div>
      <fieldset class="number_add">
        <button type="button" title="-" class="sub" control-id="ControlID-20">-</button>
        <input type="number" name="quantity" pattern="[0-9]+" control-id="ControlID-21" min="1" value="${quantite}">
        <button type="button" title="+" class="add" control-id="ControlID-22">+</button>
      </fieldset>
      </li>
      <div class="line"></div>`;
    });

    html += "</ul>";
    if (panier.length > 0) {
      html += '<div class="bottom_panier">';
      html += `<p>Total du panier : ${panierTotal}€</p>`;
      html += `<button onclick="location.href = './order.php'" class="button_command">Commander</button>`;
      html += "</div>";
    } else {
      html += `<i class="fa-solid fa-cart-shopping"></i>`;
    }
    if (panier.length == 0) {
      html = "";
    }
    return html;
  }

  // Fonction pour supprimer un article du panier
  const commandeSuppr = document.querySelectorAll(".commandeSuppr");

  commandeSuppr.forEach((suppr) => {
    suppr.addEventListener("click", function () {
      console.log("suppr");
      const itemIndex = panier.findIndex((item) => item.id === id);
      panier.splice(itemIndex, 1);
      updateCartDisplay();
      sessionStorage.setItem("panier", JSON.stringify(panier));
    });
  });
  // Fonction pour mettre à jour l'affichage du panier lors du chargement de la page (sessionStorage)
  function updateCartDisplay() {
    const panierTotal = calculateTotal(panier);
    console.log(panierTotal);
    const panierDiv = document.querySelector(".panier");
    panierDiv.innerHTML = generatePanierHTML(panier, panierTotal);
  }

  updateCartDisplay();
});

//Toggle plats
function togglePlat(platType) {
  const sucreIcon = document.querySelector(".plats_title_sucrées");
  const saleIcon = document.querySelector(".plats_title_salées");
  const supplIcon = document.querySelector(".plats_title_suppléments");
  const platsSucre = document.querySelector(".plats_sucre");
  const platsSale = document.querySelector(".plats_sale");
  const platsSuppl = document.querySelector(".plats_suppl");

  if (platType === "plats_sucrées") {
    saleIcon.classList.remove("sel");
    sucreIcon.classList.add("sel");
    supplIcon.classList.remove("sel");
    platsSucre.style.display = "block";
    platsSale.style.display = "none";
    platsSuppl.style.display = "none";
  } else if (platType === "plats_salées") {
    saleIcon.classList.add("sel");
    sucreIcon.classList.remove("sel");
    supplIcon.classList.remove("sel");
    platsSale.style.display = "block";
    platsSucre.style.display = "none";
    platsSuppl.style.display = "none";
  } else if (platType === "suppléments") {
    platsSuppl.style.display = "block";
    platsSucre.style.display = "none";
    platsSale.style.display = "none";
    supplIcon.classList.add("sel");
    saleIcon.classList.remove("sel");
    sucreIcon.classList.remove("sel");
  }
}

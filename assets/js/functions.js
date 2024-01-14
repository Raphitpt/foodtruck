// Déclaration de panier dans la portée globale
let panier = JSON.parse(sessionStorage.getItem("panier")) || [];
let panierDiv = document.querySelector(".panier");  // Assurez-vous d'avoir un élément avec la classe "panier"
// Fonction pour calculer le total
function calculateTotal(panier) {
  let total = 0;
  panier.forEach(function (plat) {
    const prix = parseFloat(plat.prix);
    const quantite = parseFloat(plat.quantite);
    total += prix * quantite;
  });
  return total;
}

// Fonction pour générer le HTML du panier
function generatePanierHTML(panier) {
  let html = "<ul>";
  panier.forEach(function (plat) {
    const prix = parseFloat(plat.prix);
    const quantite = parseFloat(plat.quantite);

    html += `<li class="list_commande">
      <div class="supprCommande">
        <i class="fa-solid fa-xmark"></i>
      </div>
      
      <div class="div_img_commande">
        <img src="./assets/img/Fouées_angevines_avec_rillettes.JPG" class="img_commande">
      </div>
      <div class="name_plat_commande">
        <p>${plat.nom}</p>
        <p>Supléments</p>
        <p>${prix} €</p>
        <p class="id_plats" style="display:none;">${plat.id}</p>
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
    html += `<p>Total du panier : ${calculateTotal(panier)}€</p>`;
    html += `<button onclick="location.href = './order.php'" class="button_command">Commander</button>`;
    html += "</div>";
  } else {
    html += `<i class="fa-solid fa-cart-shopping"></i>`;
  }
  if (panier.length === 0) {
    html = "";
  }
  return html;
}

// Fonction pour mettre à jour l'affichage du panier
function updateCartDisplay() {
  const panierTotal = calculateTotal(panier);
  panierDiv.innerHTML = generatePanierHTML(panier);
}
// Fonction pour ajouter un plat au panier
function addToCart(platId, withSupplements) {
  // Récupérer ou initialiser le panier depuis le sessionStorage
  let panier = JSON.parse(sessionStorage.getItem("panier")) || [];

  // Récupérer les informations du plat
  const platName = document
    .querySelector(`.id_plats[value='${platId}']`)
    .closest(".card")
    .querySelector(".card-title").innerText;
  const platPrice = document
    .querySelector(`.id_plats[value='${platId}']`)
    .closest(".card")
    .querySelector(".card-price").innerText;

  // Créer une nouvelle instance d'objet pour le plat actuel
  const nouveauPlat = {
    id: platId,
    nom: platName,
    prix: platPrice,
    composition: [],
    quantite: 1,
  };

  // Ajouter les suppléments si nécessaire
  if (withSupplements) {
    const checkSupplElements = document.querySelectorAll(".checkSuppl:checked");
    checkSupplElements.forEach((checkSupplElement) => {
      const supplementId = checkSupplElement.dataset.id;
      const supplementName = checkSupplElement.dataset.name;
      const supplementPrice = checkSupplElement.dataset.price;
      const supplement = {
        id: supplementId,
        name: supplementName,
        price: supplementPrice,
      };
      nouveauPlat.composition.push(supplement);
    });
  }

  // Faire une copie profonde du panier existant
  let nouveauPanier = JSON.parse(JSON.stringify(panier));

  // Vérifier si le plat est déjà dans le panier
  const existingItemIndex = nouveauPanier.findIndex((item) => item.id === platId);

  // Mettre à jour le panier
  if (existingItemIndex !== -1) {
    nouveauPanier[existingItemIndex].quantite++;

    // Ajouter les nouveaux suppléments sans écraser les existants
    if (withSupplements) {
      nouveauPanier[existingItemIndex].composition = JSON.parse(JSON.stringify(nouveauPlat.composition));
    }
  } else {
    nouveauPanier.push(nouveauPlat);
  }

  // Mettre à jour le sessionStorage avec le nouveau panier
  sessionStorage.setItem("panier", JSON.stringify(nouveauPanier));

  // Mettre à jour l'affichage du panier
  updateCartDisplay();
}



document.addEventListener("DOMContentLoaded", function () {
  // Sélection des éléments du DOM
  const platName = document.querySelectorAll(".card-title");
  const platPrice = document.querySelectorAll(".card-price");
  const platComposition = document.querySelectorAll(".card-text");
  const idPlat = document.querySelectorAll(".id_plats");
  const ajouterBoutons = document.querySelectorAll(".button_add");
  const divSuppl = document.querySelector(".supplements");
  const divListPlats = document.querySelector(".list_plat");
  const noThanks = document.querySelector(".noThanks");
  const checkSupplYes = document.querySelector(".addSupplYes");
  const checkSuppl = document.querySelectorAll(".checkSuppl");
  let panierDiv = document.querySelector(".panier");

  // Initialisation du panier avec une icône si vide
  if (panierDiv.innerHTML.trim() === "") {
    panierDiv.classList.add("icon-in-circle");
    let icon = document.createElement("i");
    icon.classList.add("fa-solid", "fa-cart-shopping");
    panierDiv.appendChild(icon);
  }

  // Fonction pour mettre à jour les quantités dans les inputs
  function updateInputNumbers() {
    panier.forEach(function (item) {
      const id = item.id;
      const inputNumber = document.getElementById(`input-number-${id}`);
      if (inputNumber) {
        inputNumber.value = item.quantite;
      }
    });
  }

  // Mise à jour des quantités
  updateInputNumbers();

  ajouterBoutons.forEach((ajouterBouton, index) => {
    let elementCounter = 0;
    ajouterBouton.addEventListener("click", function () {
      elementCounter++;
      let id = idPlat[index].value;
      let itemIndex = panier.findIndex((item) => item.id === id);

      // Mettre à jour ou ajouter un nouvel élément au panier
      if (itemIndex !== -1) {
        panier[itemIndex].quantite = elementCounter;
      } else {
        panier.push({
          id: id,
          nom: platName[index].innerHTML,
          prix: platPrice[index].innerHTML,
          composition: [],
          quantite: elementCounter,
        });
      }

      // Affichage des suppléments si disponibles
      const hasSupplements = checkSuppl.length > 0;
      if (hasSupplements) {
        divSuppl.style.display = "block";
        checkSupplYes.addEventListener("click", function () {
          addToCart(id, true);
          divSuppl.style.display = "none";
        });

        noThanks.addEventListener("click", function () {
          addToCart(id, false);
          divSuppl.style.display = "none";
        });
      } else {
        // Aucun supplément disponible, ajouter directement au panier
        addToCart(id, false);
      }

      updateInputNumbers();
      updateCartDisplay();

      sessionStorage.setItem("panier", JSON.stringify(panier));
    });
  });

  panierDiv.addEventListener("click", function (event) {
    if (event.target.classList.contains("fa-xmark")) {
      // Suppression d'un élément du panier
      const listItem = event.target.closest(".list_commande");
      const id = listItem.querySelector(".id_plats").textContent;

      listItem.classList.add("fade-out");
      const itemIndex = panier.findIndex((item) => item.id === id);
      panier.splice(itemIndex, 1);
    }
    if (
      event.target.classList.contains("add") ||
      event.target.classList.contains("sub")
    ) {
      // Modification de la quantité d'un élément du panier
      const listItem = event.target.closest(".list_commande");
      const id = listItem.querySelector(".id_plats").textContent;
      const itemIndex = panier.findIndex((item) => item.id === id);
      if (event.target.classList.contains("add")) {
        panier[itemIndex].quantite++;
      } else if (event.target.classList.contains("sub")) {
        if (panier[itemIndex].quantite > 1) {
          panier[itemIndex].quantite--;
        }
      }
    }
    updateCartDisplay();

    sessionStorage.setItem("panier", JSON.stringify(panier));
  });

  // Mise à jour de l'affichage du panier
  function updateCartDisplay() {
    const panierTotal = calculateTotal(panier);
    panierDiv.innerHTML = generatePanierHTML(panier);
  }

  updateCartDisplay();
});


// Fonction pour afficher les plats en fonction de leur type (sucré, salé, supplément)
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
    if (supplIcon !== null) {
      supplIcon.classList.remove("sel");
    }
    platsSucre.style.display = "block";
    platsSale.style.display = "none";
    platsSuppl.style.display = "none";
  } else if (platType === "plats_salées") {
    saleIcon.classList.add("sel");
    sucreIcon.classList.remove("sel");
    if (supplIcon !== null) {
      supplIcon.classList.remove("sel");
    }
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

// Gestion du changement de langue
français = document.querySelector(".français");
anglais = document.querySelector(".anglais");
fra = document.querySelector(".fra");
ang = document.querySelector(".ang");
navang = document.querySelector(".navang");
navfr = document.querySelector(".navfr");

anglais.addEventListener("click", function () {
  français.style.display = "block";

  fra.style.display = "none";
  if (window.innerWidth > 770) {
    navfr.style.display = "none";
    navang.style.display = "flex";
  }
  ang.style.display = "flex";
  anglais.style.display = "none";
});

français.addEventListener("click", function () {
  anglais.style.display = "block";

  ang.style.display = "none";
  if (window.innerWidth > 770) {
    navang.style.display = "none";
    navfr.style.display = "flex";
  }
  fra.style.display = "flex";
  français.style.display = "none";
});

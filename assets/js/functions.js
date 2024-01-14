// Fonction pour calculer le total
function calculateTotal(panier) {
  let total = 0;
  panier.forEach(function (plat) {
    const prix = parseFloat(plat.prix);
    const quantite = parseFloat(plat.quantite);
    const supplementCost = calculateSupplementCost(plat.supplements);
    total += (prix + supplementCost) * quantite;
  });
  return total;
}

function calculateSupplementCost(supplements) {
  let supplementCost = 0;
  supplements.forEach(function (supplement) {
    const supplementPrice = parseFloat(supplement.price);
    supplementCost += supplementPrice;
  });
  return supplementCost;
}

function cleanURL(fullURL) {
  const baseURL = window.location.origin;
  const cleanedURL = fullURL.replace(baseURL, "");
  return cleanedURL;
}

// Fonction pour générer le HTML du panier
function generatePanierHTML(panier) {
  let html = "<ul>";
if(panier !== null){
  panier.forEach(function (plat) {
    let prix = parseFloat(plat.prix);
    const quantite = parseFloat(plat.quantite);

    // Template HTML pour chaque plat dans le panier
    html += `
      <li class="list_commande">
        <div class="supprCommande">
          <i class="fa-solid fa-xmark"></i>
        </div>
        <div class="div_img_commande">
          <img src="${plat.img_url}" class="img_commande">
        </div>
        <div class="name_plat_commande">
          <p>${plat.nom}</p>
          `;
    if (plat.supplements.length > 0) {
      html += `<p>Supplément(s) : `;
      plat.supplements.forEach(function (supplement) {
        html += `${supplement.name} (${supplement.price}€) `;
        prix += parseFloat(supplement.price);
      });
      html += `</p>`;
    } else {
      html += `<p>Supplément(s) : Aucun</p>`;
    }

    html += `
          <p class="pri">${prix} €</p>
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
} else {
  html = `<p>Votre panier est vide</p>`;
}


  // Ajout du total du panier et du bouton de commande
  if (panier.length > 0) {
    html += '<div class="bottom_panier">';
    html += `<p>Total du panier : ${calculateTotal(panier)}€</p>`;
    html += `<button onclick="location.href = './order.php'" class="button_command">Commander</button>`;
    html += "</div>";
  }

  return html;
}

document.addEventListener("DOMContentLoaded", function () {
  // Sélection des éléments du DOM
  const idPlat = document.querySelectorAll(".id_plats");
  const ajouterBoutons = document.querySelectorAll(".button_add");
  const divSuppl = document.querySelector(".supplements");
  const divListPlats = document.querySelector(".list_plat");
  const noThanks = document.querySelector(".noThanks");
  const checkSupplYes = document.querySelector(".addSupplYes");
  const checkSuppl = document.querySelectorAll(".checkSuppl");

  // Déclaration de panier dans la portée globale
  let panier = JSON.parse(sessionStorage.getItem("panier")) || [];
  let panierDiv = document.querySelector(".panier"); // Assurez-vous d'avoir un élément avec la classe "panier"

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

  // Fonction pour mettre à jour l'affichage du panier
  function updateCartDisplay() {
    panier = JSON.parse(sessionStorage.getItem("panier")) || [];
    const panierTotal = calculateTotal(panier);
    panierDiv.innerHTML = generatePanierHTML(panier);
  }

  // Mise à jour de l'affichage initial du panier
  updateCartDisplay();

  ajouterBoutons.forEach((ajouterBouton, index) => {
    ajouterBouton.addEventListener("click", function () {
      let id = idPlat[index].value;
      let platName = document.querySelectorAll(".card-title")[index].innerHTML;
      let platPrice = document.querySelectorAll(".card-price")[index].innerHTML;
      let img_url = "." + cleanURL(document.querySelectorAll(".card_img")[index].src);

      // Récupérer les informations sur les suppléments
      const hasSupplements = checkSuppl.length > 0;

      // Définir les gestionnaires d'événements
      function handleCheckSupplYes() {
        addToCart(id, platName, platPrice, img_url, true);
        divSuppl.style.display = "none";
        checkSuppl.forEach((checkSuppl) => {
          checkSuppl.checked = false;
        });
        cleanupEventListeners();
      }

      function handleNoThanks() {
        addToCart(id, platName, platPrice, img_url, false);
        divSuppl.style.display = "none";
        checkSuppl.forEach((checkSuppl) => {
          checkSuppl.checked = false;
        });
        cleanupEventListeners();
      }

      function cleanupEventListeners() {
        checkSupplYes.removeEventListener("click", handleCheckSupplYes);
        noThanks.removeEventListener("click", handleNoThanks);
      }

      // Ajouter des gestionnaires d'événements supplémentaires si nécessaire
      if (hasSupplements) {
        checkSupplYes.addEventListener("click", handleCheckSupplYes);
        noThanks.addEventListener("click", handleNoThanks);
        divSuppl.style.display = "block";
      } else {
        // Aucun supplément disponible, ajouter directement au panier
        addToCart(id, platName, platPrice, img_url, false);
      }

      updateInputNumbers();
      updateCartDisplay();
    });
  });

  function addToCart(id, platName, platPrice, img_url, withSupplements) {
    // Récupérer ou initialiser le panier depuis le sessionStorage
    let panier = JSON.parse(sessionStorage.getItem("panier")) || [];

    // Rechercher toutes les occurrences du plat dans le panier
    const existingItems = panier.filter((item) => item.id === id);

    // Vérifier si une occurrence a les mêmes suppléments
    const selectedSupplements = withSupplements ? getSelectedSupplements() : [];
    const hasMatchingComposition = existingItems.some((item) =>
      arraysEqual(item.supplements, selectedSupplements)
    );

    if (hasMatchingComposition) {
      // Si une occurrence a les mêmes suppléments, incrémenter la quantité
      const matchingItem = existingItems.find((item) =>
        arraysEqual(item.supplements, selectedSupplements)
      );
      matchingItem.quantite++;
    } else {
      // Sinon, ajouter un nouvel élément au panier
      panier.push({
        id: id,
        nom: platName,
        prix: platPrice,
        supplements: selectedSupplements,
        img_url: img_url,
        quantite: 1,
      });
    }

    // Mettre à jour le sessionStorage avec le nouveau panier
    sessionStorage.setItem("panier", JSON.stringify(panier));

    // Mettre à jour l'affichage du panier
    updateCartDisplay();
  }

  function arraysEqual(arr1, arr2) {
    if (arr1.length !== arr2.length) {
      return false;
    }

    for (let i = 0; i < arr1.length; i++) {
      if (
        arr1[i].id !== arr2[i].id ||
        arr1[i].name !== arr2[i].name ||
        arr1[i].price !== arr2[i].price
      ) {
        return false;
      }
    }

    return true;
  }

  function getSelectedSupplements() {
    const checkSupplElements = document.querySelectorAll(".checkSuppl:checked");
    const selectedSupplements = [];

    checkSupplElements.forEach((checkSupplElement) => {
      const supplementId = checkSupplElement.dataset.id;
      const supplementName = checkSupplElement.dataset.name;
      const supplementPrice = checkSupplElement.dataset.price;

      selectedSupplements.push({
        id: supplementId,
        name: supplementName,
        price: supplementPrice,
      });
    });

    return selectedSupplements;
  }

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
    sessionStorage.setItem("panier", JSON.stringify(panier));
    updateCartDisplay();
    
  });
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

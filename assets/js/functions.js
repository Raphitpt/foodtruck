document.addEventListener("DOMContentLoaded", function () {
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
  let elementCounter = "";
  if (panierDiv.innerHTML.trim() === "") {
    panierDiv.classList.add("icon-in-circle");
    let icon = document.createElement("i");
    icon.classList.add("fa-solid", "fa-cart-shopping");
    panierDiv.appendChild(icon);
  }

  let panier = JSON.parse(sessionStorage.getItem("panier")) || [];

  function updateInputNumbers() {
    panier.forEach(function (item) {
      const id = item.id;
      const inputNumber = document.getElementById(`input-number-${id}`);
      if (inputNumber) {
        inputNumber.value = item.quantite;
      }
    });
  }

  updateInputNumbers();

  const resetSupplementCheckboxes = () => {
    checkSuppl.forEach((checkbox) => {
      checkbox.checked = false;
    });
  };

  const displaySupplementSection = () => {
    divSuppl.style.display = "block";
    divListPlats.style.display = "none";
  };

  const handleSupplementCheckbox = (check, index) => {
    let id = idPlat[index].value;
    let itemIndex = panier.findIndex((item) => item.id === id);

    let supplID = check.getAttribute("data-id");
    let supplName = check.getAttribute("data-name");
    let supplPrice = check.getAttribute("data-price");

    if (check.checked) {
      checkSupplYes.style.display = "block";
      if (!panier[itemIndex].supplements) {
        panier[itemIndex].supplements = [];
      }

      panier[itemIndex].supplements.push({
        id: supplID,
        nom: supplName,
        prix: supplPrice,
      });
    } else {
      panier[itemIndex].supplements = panier[itemIndex].supplements.filter(
        (supplement) => supplement.id !== supplID
      );
    }
  };

  const hideSupplementSection = () => {
    divSuppl.style.display = "none";
    divListPlats.style.display = "block";
    resetSupplementCheckboxes(); // Reset the checkboxes
  };

  const handleAddToCart = (index) => {
    elementCounter++;

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
        supplements: [],
        quantite: elementCounter,
      });
    }

    panierDiv.innerHTML = generatePanierHTML(panier);
    updateInputNumbers();
    updateCartDisplay();

    updateSessionStorage();
  };

  ajouterBoutons.forEach((ajouterBouton, index) => {
    ajouterBouton.addEventListener("click", () => {
      displaySupplementSection();

      checkSuppl.forEach((check) => {
        check.addEventListener("click", () => handleSupplementCheckbox(check, index));
      });

      noThanks.addEventListener("click", hideSupplementSection);

      checkSupplYes.addEventListener("click", () => {
        handleAddToCart(index);
        console.log(index);
        hideSupplementSection();
        
      });
    });
  });

  function calculateTotal(panier) {
    let total = 0;
    panier.forEach(function (plat) {
      const prix = parseFloat(plat.prix);
      const quantite = parseFloat(plat.quantite);
      const articleTotal = prix * quantite;
      total += articleTotal;
    });
    return total;
  }

  function generatePanierHTML(panier) {
    let html = "<ul>";
    panier.forEach(function (plat) {
      const prix = parseFloat(plat.prix);
      const quantite = parseFloat(plat.quantite);
      const articleTotal = prix * quantite;
      console.log(plat);

      html += `<li class="list_commande">
        <div class="supprCommande">
          <i class="fa-solid fa-xmark"></i>
        </div>

        <div class="div_img_commande">
          <img src="./assets/img/Fouées_angevines_avec_rillettes.JPG" class="img_commande">
        </div>
        <div class="name_plat_commande">
          <p>${plat.nom}</p>
          <p>${plat.supplements[0].nom}</p>
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


  panierDiv.addEventListener("click", function (event) {
    if (event.target.classList.contains("fa-xmark")) {
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

    updateSessionStorage();
  });

  function updateCartDisplay() {
    const panierTotal = calculateTotal(panier);
    panierDiv.innerHTML = generatePanierHTML(panier);
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

français = document.querySelector(".français");
anglais = document.querySelector(".anglais");
fra = document.querySelector(".fra");
ang = document.querySelector(".ang");
navang = document.querySelector(".navang");
navfr = document.querySelector(".navfr");

anglais.addEventListener("click", function () {
  français.style.display = "block";
  navfr.style.display="none";
  fra.style.display="none";
  navang.style.display="flex";
  ang.style.display="flex";
  anglais.style.display="none";

});
français.addEventListener("click", function () {
  anglais.style.display = "block";
  navang.style.display="none";
  ang.style.display="none";
  navfr.style.display="flex";
  fra.style.display="flex";
  français.style.display="none";

});



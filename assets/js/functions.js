document.addEventListener("DOMContentLoaded", function () {
  const platName = document.querySelectorAll(".card-title");
  const platPrice = document.querySelectorAll(".card-price");
  const platComposition = document.querySelectorAll(".card-text");
  const idPlat = document.querySelectorAll(".id_plats");
  const addBoutons = document.querySelectorAll(".btn-primary");
  const enleverBoutons = document.querySelectorAll(".btn-danger");
  const ajouterBoutons = document.querySelectorAll(".button_add");

  let panierDiv = document.querySelector(".panier");

  if (panierDiv.innerHTML.trim() === "") {
    panierDiv.classList.add("icon-in-circle");
    let icon = document.createElement('i');
<<<<<<< HEAD
    icon.classList.add("fa-solid", "fa-cart-shopping");
    panierDiv.appendChild(icon);
  }

  // update js functions
=======
    icon.className = "fa-solid fa-cart-shopping";
    panierDiv.appendChild(icon);
  }

>>>>>>> 3c39f63a5a190491cab908aef06d276ea1851c34
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

  ajouterBoutons.forEach((ajouterBouton, index) => {
    let elementCounter = 0;
    ajouterBouton.addEventListener("click", function () {
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
          quantite: elementCounter,
        });
      }

      panierDiv.innerHTML = generatePanierHTML(panier);
      updateInputNumbers();
      updateCartDisplay();

      sessionStorage.setItem("panier", JSON.stringify(panier));
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

      html += `<li class="list_commande">
        <i class="fa-solid fa-xmark"></i>
        <div class="div_img_commande">
          <img src="./assets/img/Fouées_angevines_avec_rillettes.JPG" class="img_commande">
        </div>
        <div class="name_plat_commande">
<<<<<<< HEAD
          <p>${plat.nom}</p>
          <p>Supléments</p>
=======
          <p>${plat.nom}<p>
          <p>Supléments<p>
>>>>>>> 3c39f63a5a190491cab908aef06d276ea1851c34
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
<<<<<<< HEAD
      html += `<p>Total du panier : ${calculateTotal(panier)}€</p>`;
=======
      const panierTotal = calculateTotal(panier);
      html += `<p>Total du panier : ${panierTotal}€</p>`;
>>>>>>> 3c39f63a5a190491cab908aef06d276ea1851c34
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

<<<<<<< HEAD
  const commandeSuppr = document.querySelectorAll(".commandeSuppr");

  commandeSuppr.forEach((suppr) => {
    suppr.addEventListener("click", function () {
      const id = suppr.dataset.id;
      const itemIndex = panier.findIndex((item) => item.id === id);
      panier.splice(itemIndex, 1);
      updateCartDisplay();
      sessionStorage.setItem("panier", JSON.stringify(panier));
    });
  });

=======
>>>>>>> 3c39f63a5a190491cab908aef06d276ea1851c34
  function updateCartDisplay() {
    const panierTotal = calculateTotal(panier);
    const panierDiv = document.querySelector(".panier");
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
<<<<<<< HEAD
    if (supplIcon !== null) {
=======
    if (supplIcon != null){
>>>>>>> 3c39f63a5a190491cab908aef06d276ea1851c34
      supplIcon.classList.remove("sel");
    }
    platsSucre.style.display = "block";
    platsSale.style.display = "none";
    platsSuppl.style.display = "none";
  } else if (platType === "plats_salées") {
    saleIcon.classList.add("sel");
    sucreIcon.classList.remove("sel");
<<<<<<< HEAD
    if (supplIcon !== null) {
      supplIcon.classList.remove("sel");
    }
=======
    if (supplIcon != null){
      supplIcon.classList.remove("sel");
    }
    
>>>>>>> 3c39f63a5a190491cab908aef06d276ea1851c34
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

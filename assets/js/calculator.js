document.addEventListener("DOMContentLoaded", function () {
  const widthInput = document.getElementById("width");
  const heightInput = document.getElementById("height");
  const glassType = document.getElementById("glassType");
  const shapeInputs = document.querySelectorAll('input[name="shape"]');
  const toughened = document.getElementById("toughened");
  const petFlap = document.getElementById("petFlap");
  const extras = document.getElementById("extras");
  const totalEl = document.getElementById("singleTotal");

  const calculateTotal = () => {
    let width = parseFloat(widthInput.value || 0);
    let height = parseFloat(heightInput.value || 0);
    let area = (width * height) / 929; // Convert to sqft

    let glassPrice = parseFloat(glassType.value || 0);
    let shapePercent = parseFloat(document.querySelector('input[name="shape"]:checked').value || 0);
    let toughenedPercent = toughened.checked ? 20 : 0;
    let petPrice = parseFloat(petFlap.value || 0);
    let extrasPrice = parseFloat(extras.value || 0);

    let basePrice = area * glassPrice;
    let percentAdditions = (shapePercent + toughenedPercent) / 100;
    let total = basePrice + (basePrice * percentAdditions) + petPrice + extrasPrice;

    totalEl.innerText = Math.round(total);
  };

  [widthInput, heightInput, glassType, toughened, petFlap, extras].forEach(el =>
    el.addEventListener("input", calculateTotal)
  );
  shapeInputs.forEach(input => input.addEventListener("change", calculateTotal));
});

function sendCartAction(type, action) {
  const form = document.getElementById(`${type}Form`);
  const formData = new FormData(form);

  let details = {};
  formData.forEach((value, key) => {
    details[key] = value;
  });

  fetch("process/add_to_cart.php", {
    method: "POST",
    body: new URLSearchParams({
      action,
      product_type: type,
      details: JSON.stringify(details),
      price: document.getElementById(`${type}Total`).innerText
    }),
  })
  .then(() => {
    if (action === 'buy') {
      window.location.href = 'checkout.php';
    } else {
      alert("Added to cart!");
    }
  });
}

function addToCart(type) {
  sendCartAction(type, 'add');
}

function buyNow(type) {
  sendCartAction(type, 'buy');
}


document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");
  const nameInput = document.getElementById("name");
  const categoryInput = document.getElementById("category_id");
  const priceInput = document.getElementById("price");
  const stockInput = document.getElementById("stock");

  // Helper: show error
  function showError(input, message) {
    input.classList.add("error");
    let error = input.nextElementSibling;
    if (!error || !error.classList.contains("error-message")) {
      error = document.createElement("div");
      error.className = "error-message";
      input.insertAdjacentElement("afterend", error);
    }
    error.textContent = message;
  }

  // Helper: clear error
  function clearError(input) {
    input.classList.remove("error");
    const error = input.nextElementSibling;
    if (error && error.classList.contains("error-message")) {
      error.remove();
    }
  }

  // Validation on blur
  [nameInput, categoryInput, priceInput, stockInput].forEach(input => {
    input.addEventListener("blur", () => {
      if (input.value.trim() === "") {
        showError(input, `${input.previousElementSibling.textContent} is required`);
      } else {
        clearError(input);
      }
    });
  });

  // Form submission
  form.addEventListener("submit", function (e) {
    let valid = true;

    // Product name
    if (nameInput.value.trim() === "") {
      showError(nameInput, "Product name is required");
      valid = false;
    } else {
      clearError(nameInput);
    }

    // Category ID
    if (categoryInput.value.trim() === "" || isNaN(categoryInput.value)) {
      showError(categoryInput, "Valid Category ID is required");
      valid = false;
    } else {
      clearError(categoryInput);
    }

    // Price
    if (priceInput.value.trim() === "" || isNaN(priceInput.value) || parseFloat(priceInput.value) <= 0) {
      showError(priceInput, "Valid price is required");
      valid = false;
    } else {
      clearError(priceInput);
    }

    // Stock
    if (stockInput.value.trim() === "" || isNaN(stockInput.value) || parseInt(stockInput.value) < 0) {
      showError(stockInput, "Valid stock is required");
      valid = false;
    } else {
      clearError(stockInput);
    }

    if (!valid) {
      e.preventDefault();
      return;
    }

    // Confirmation before submitting
    if (!confirm("Are you sure you want to update this product?")) {
      e.preventDefault();
    }
  });
});

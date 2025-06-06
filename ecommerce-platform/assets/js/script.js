document.addEventListener("DOMContentLoaded", () => {
  // ==================== CART FUNCTIONALITY ====================
  const addToCartButtons = document.querySelectorAll('.add-to-cart');
  
  addToCartButtons.forEach(button => {
    button.addEventListener('click', async (e) => {
      e.preventDefault();
      const productId = button.dataset.productId;
      const spinner = button.querySelector('.spinner-border') || createSpinner(button);
      
      try {
        button.disabled = true;
        spinner.classList.remove('d-none');
        
        const response = await fetch('actions/add_to_cart.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `product_id=${productId}`
        });
        
        if (response.ok) {
          updateCartCounter();
          showToast('Product added to cart!', 'success');
        } else {
          throw new Error('Failed to add to cart');
        }
      } catch (error) {
        showToast('Failed to add item', 'danger');
        console.error(error);
      } finally {
        button.disabled = false;
        spinner.classList.add('d-none');
      }
    });
  });

  // ==================== TOAST NOTIFICATIONS ====================
  function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    const toast = document.createElement('div');
    
    toast.className = `toast show align-items-center text-white bg-${type}`;
    toast.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    `;
    
    toastContainer.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
  }

  // ==================== PRODUCT FILTERS ====================
  const priceFilter = document.getElementById('price-filter');
  if (priceFilter) {
    priceFilter.addEventListener('input', (e) => {
      document.getElementById('price-value').textContent = `$${e.target.value}`;
      filterProducts();
    });
  }

  // ==================== UTILITY FUNCTIONS ====================
  function updateCartCounter() {
    fetch('actions/get_cart_count.php')
      .then(response => response.json())
      .then(data => {
        const counters = document.querySelectorAll('.cart-counter');
        counters.forEach(counter => {
          counter.textContent = data.count > 0 ? data.count : '';
        });
      });
  }

  function createSpinner(button) {
    const spinner = document.createElement('span');
    spinner.className = 'spinner-border spinner-border-sm d-none';
    spinner.setAttribute('aria-hidden', 'true');
    button.insertBefore(spinner, button.firstChild);
    return spinner;
  }

  function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'position-fixed bottom-0 end-0 p-3';
    container.style.zIndex = '1100';
    document.body.appendChild(container);
    return container;
  }

  function filterProducts() {
    const price = document.getElementById('price-filter').value;
    const category = document.getElementById('category-filter').value;
    
    fetch(`actions/filter_products.php?max_price=${price}&category=${category}`)
      .then(response => response.text())
      .then(html => {
        document.getElementById('products-container').innerHTML = html;
      });
  }

  // ==================== INITIALIZATIONS ====================
  // Initialize cart counter on page load
  updateCartCounter();

  // Initialize Bootstrap tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Back to top button behavior (matches footer.php)
  window.addEventListener('scroll', function() {
    const backToTop = document.getElementById('backToTop');
    if (window.pageYOffset > 300) {
      backToTop.style.display = 'flex';
    } else {
      backToTop.style.display = 'none';
    }
  });
});
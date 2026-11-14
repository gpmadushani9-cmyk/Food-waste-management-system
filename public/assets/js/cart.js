// Shopping Cart System
let cart = JSON.parse(localStorage.getItem('cart') || '[]');

function updateCartCount() {
  const count = cart.reduce((sum, item) => sum + item.quantity, 0);
  const cartCountEl = document.getElementById('cart-count');
  if (cartCountEl) {
    cartCountEl.textContent = count;
    cartCountEl.style.display = count > 0 ? 'inline-block' : 'none';
  }
}

function addToCart(id, name, price, discount = 0) {
  const existing = cart.find(item => item.id === id);
  
  if (existing) {
    existing.quantity++;
  } else {
    cart.push({
      id: id,
      name: name,
      price: price,
      discount: discount,
      quantity: 1
    });
  }
  
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCartCount();
  showNotification('Added to cart!');
  renderCart();
}

function removeFromCart(id) {
  cart = cart.filter(item => item.id !== id);
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCartCount();
  renderCart();
}

function updateQuantity(id, quantity) {
  const item = cart.find(item => item.id === id);
  if (item) {
    item.quantity = Math.max(1, quantity);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    renderCart();
  }
}

function clearCart() {
  cart = [];
  localStorage.setItem('cart', JSON.stringify(cart));
  updateCartCount();
  renderCart();
}

function getCartTotal() {
  return cart.reduce((sum, item) => {
    const itemPrice = item.price * (1 - item.discount / 100);
    return sum + (itemPrice * item.quantity);
  }, 0);
}

function renderCart() {
  const cartContainer = document.getElementById('cart-items');
  const cartTotal = document.getElementById('cart-total');
  
  if (!cartContainer) return;
  
  if (cart.length === 0) {
    cartContainer.innerHTML = `
      <div style="text-align: center; padding: 3rem; color: rgba(255,255,255,0.6);">
        <div style="font-size: 4rem; margin-bottom: 1rem;">🛒</div>
        <h3 style="color: white; margin-bottom: 0.5rem;">Your cart is empty</h3>
        <p>Add items from our menu to get started!</p>
      </div>
    `;
    if (cartTotal) cartTotal.textContent = 'Rs.0.00';
    return;
  }
  
  let html = '';
  cart.forEach(item => {
    const itemPrice = item.price * (1 - item.discount / 100);
    const subtotal = itemPrice * item.quantity;
    
    html += `
      <div style="background: rgba(0,0,0,0.5); padding: 1.5rem; border-radius: 1rem; margin-bottom: 1rem; border: 1px solid rgba(255,255,255,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
          <div style="flex: 1;">
            <h4 style="color: white; margin-bottom: 0.5rem;">${item.name}</h4>
            ${item.discount > 0 ? `<span style="background: #ff6b6b; color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 700;">${item.discount}% OFF</span>` : ''}
          </div>
          <button onclick="removeFromCart(${item.id})" style="background: rgba(239,68,68,0.2); color: #ef4444; border: none; padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer; font-weight: 600;">
            🗑️ Remove
          </button>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <div style="display: flex; align-items: center; gap: 1rem;">
            <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})" style="background: rgba(255,255,255,0.1); color: white; border: none; width: 2rem; height: 2rem; border-radius: 0.5rem; cursor: pointer; font-size: 1.2rem;">-</button>
            <span style="color: white; font-weight: 600; min-width: 2rem; text-align: center;">${item.quantity}</span>
            <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})" style="background: rgba(255,255,255,0.1); color: white; border: none; width: 2rem; height: 2rem; border-radius: 0.5rem; cursor: pointer; font-size: 1.2rem;">+</button>
          </div>
          
          <div style="text-align: right;">
            <div style="color: rgba(255,255,255,0.6); font-size: 0.875rem;">Rs.${itemPrice.toFixed(2)} each</div>
            <div style="color: #ffd93d; font-size: 1.25rem; font-weight: 700;">Rs.${subtotal.toFixed(2)}</div>
          </div>
        </div>
      </div>
    `;
  });
  
  cartContainer.innerHTML = html;
  if (cartTotal) cartTotal.textContent = 'Rs.' + getCartTotal().toFixed(2);
}

function showCheckoutForm() {
  document.getElementById('cart-view').style.display = 'none';
  document.getElementById('checkout-form').style.display = 'block';
  const checkoutTotal = document.getElementById('checkout-total');
  if (checkoutTotal) {
    checkoutTotal.textContent = 'Rs.' + getCartTotal().toFixed(2);
  }
}

function showCart() {
  document.getElementById('cart-view').style.display = 'block';
  document.getElementById('checkout-form').style.display = 'none';
}

function placeOrder(event) {
  event.preventDefault();
  
  const form = event.target;
  const formData = new FormData(form);
  formData.append('cart', JSON.stringify(cart));
  
  const submitBtn = form.querySelector('button[type="submit"]');
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span>⏳</span> Processing...';
  
  fetch('index.php?r=customer/placeOrder', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showNotification('Order placed successfully! Order #' + data.order_id, 'success');
      clearCart();
      form.reset();
      showCart();
      setTimeout(() => {
        showTab('home');
      }, 2000);
    } else {
      showNotification(data.message || 'Failed to place order', 'error');
    }
  })
  .catch(err => {
    showNotification('Error placing order. Please try again.', 'error');
  })
  .finally(() => {
    submitBtn.disabled = false;
    submitBtn.innerHTML = '<span>✅</span> Place Order';
  });
}

function showNotification(message, type = 'success') {
  const notification = document.createElement('div');
  notification.style.cssText = `
    position: fixed;
    top: 2rem;
    right: 2rem;
    background: ${type === 'success' ? '#10b981' : '#ef4444'};
    color: white;
    padding: 1rem 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    z-index: 10000;
    animation: slideIn 0.3s ease;
  `;
  notification.textContent = message;
  document.body.appendChild(notification);
  
  setTimeout(() => {
    notification.style.animation = 'slideOut 0.3s ease';
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', function() {
  updateCartCount();
  renderCart();
});

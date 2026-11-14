import { renderBarChart } from './simpleChart.js';

// Utility functions
function parseData(el, key) {
  try { return JSON.parse(el.dataset[key] || '[]'); } catch { return []; }
}

function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = `alert alert--${type} animate-fade-in`;
  notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
  notification.innerHTML = `
    <div style="display: flex; align-items: center; justify-content: space-between;">
      <span>${message}</span>
      <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">&times;</button>
    </div>
  `;
  document.body.appendChild(notification);
  
  setTimeout(() => {
    if (notification.parentElement) {
      notification.remove();
    }
  }, 5000);
}

// Enhanced form handling
function enhanceForm(form) {
  const submitBtn = form.querySelector('button[type="submit"]');
  const originalText = submitBtn?.innerHTML;
  
  form.addEventListener('submit', function(e) {
    if (submitBtn) {
      submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
      submitBtn.disabled = true;
      submitBtn.style.opacity = '0.7';
    }
    
    // Re-enable after 10 seconds as fallback
    setTimeout(() => {
      if (submitBtn && originalText) {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
      }
    }, 10000);
  });
  
  // Add real-time validation
  const inputs = form.querySelectorAll('input[required], select[required]');
  inputs.forEach(input => {
    input.addEventListener('blur', function() {
      if (this.value.trim() === '') {
        this.style.borderColor = 'var(--danger)';
        this.style.boxShadow = '0 0 0 3px rgb(239 68 68 / 0.1)';
      } else {
        this.style.borderColor = 'var(--success)';
        this.style.boxShadow = '0 0 0 3px rgb(16 185 129 / 0.1)';
      }
    });
    
    input.addEventListener('input', function() {
      if (this.style.borderColor) {
        this.style.borderColor = '';
        this.style.boxShadow = '';
      }
    });
  });
}

// Enhanced table interactions
function enhanceTable(table) {
  const rows = table.querySelectorAll('tbody tr');
  
  rows.forEach(row => {
    row.addEventListener('mouseenter', function() {
      this.style.backgroundColor = 'var(--gray-50)';
      this.style.transform = 'scale(1.01)';
      this.style.transition = 'all 0.2s ease';
    });
    
    row.addEventListener('mouseleave', function() {
      this.style.backgroundColor = '';
      this.style.transform = '';
    });
  });
}

// Search functionality
function addSearchFunctionality() {
  const searchInputs = document.querySelectorAll('input[type="search"]');
  
  searchInputs.forEach(input => {
    input.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const table = this.closest('.card').querySelector('table');
      
      if (table) {
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          if (text.includes(searchTerm)) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      }
    });
  });
}

// Smooth scrolling for anchor links
function addSmoothScrolling() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
}

// Auto-save functionality for forms
function addAutoSave() {
  const forms = document.querySelectorAll('form');
  
  forms.forEach(form => {
    const inputs = form.querySelectorAll('input, select, textarea');
    const formId = form.id || 'form_' + Math.random().toString(36).substr(2, 9);
    
    // Load saved data
    inputs.forEach(input => {
      const savedValue = localStorage.getItem(`${formId}_${input.name}`);
      if (savedValue && input.type !== 'password') {
        input.value = savedValue;
      }
    });
    
    // Save data on input
    inputs.forEach(input => {
      input.addEventListener('input', function() {
        if (this.type !== 'password') {
          localStorage.setItem(`${formId}_${this.name}`, this.value);
        }
      });
    });
    
    // Clear saved data on successful submit
    form.addEventListener('submit', function() {
      inputs.forEach(input => {
        localStorage.removeItem(`${formId}_${input.name}`);
      });
    });
  });
}

// Main initialization
document.addEventListener('DOMContentLoaded', () => {
  // Initialize charts
  const sales = document.getElementById('salesChart');
  if (sales) {
    renderBarChart(sales, parseData(sales, 'labels'), parseData(sales, 'values'), { 
      title: 'Sales Revenue Trend',
      color: '#6366f1'
    });
  }

  const wasteIng = document.getElementById('wasteIngChart');
  if (wasteIng) {
    renderBarChart(wasteIng, parseData(wasteIng, 'labels'), parseData(wasteIng, 'values'), { 
      title: 'Waste Cost by Ingredient',
      color: '#ef4444'
    });
  }

  const forecast = document.getElementById('forecastChart');
  if (forecast) {
    renderBarChart(forecast, parseData(forecast, 'labels'), parseData(forecast, 'values'), { 
      title: 'Demand Forecast',
      color: '#10b981'
    });
  }
  
  // Enhance all forms
  document.querySelectorAll('form').forEach(enhanceForm);
  
  // Enhance all tables
  document.querySelectorAll('table').forEach(enhanceTable);
  
  // Add search functionality
  addSearchFunctionality();
  
  // Add smooth scrolling
  addSmoothScrolling();
  
  // Add auto-save
  addAutoSave();
  
  // Add loading states to buttons
  document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('click', function() {
      if (this.type === 'submit' || this.href) {
        this.style.transform = 'scale(0.98)';
        setTimeout(() => {
          this.style.transform = '';
        }, 150);
      }
    });
  });
  
  // Add keyboard shortcuts
  document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + K for search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
      e.preventDefault();
      const searchInput = document.querySelector('input[type="search"]');
      if (searchInput) {
        searchInput.focus();
      }
    }
    
    // Escape to close modals/alerts
    if (e.key === 'Escape') {
      document.querySelectorAll('.alert').forEach(alert => {
        if (alert.style.position === 'fixed') {
          alert.remove();
        }
      });
    }
  });
  
  // Show welcome message for new users
  if (window.location.search.includes('welcome=1')) {
    showNotification('Welcome to Food Waste Management System! 🎉', 'success');
  }
  
  // Add tooltips to buttons and links
  document.querySelectorAll('[title]').forEach(element => {
    element.addEventListener('mouseenter', function() {
      this.style.position = 'relative';
    });
  });
  
  console.log('🍽️ Food Waste Management System - Professional UI Loaded');
});

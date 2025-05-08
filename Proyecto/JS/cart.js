// /xampp/htdocs/UrbanJ/js/cart.js

document.addEventListener('DOMContentLoaded', () => {
    // Mostrar/ocultar dropdown
    const btn = document.getElementById('cart-btn');
    const dd  = document.getElementById('cart-dropdown');
    btn?.addEventListener('click', e => {
      e.stopPropagation();
      dd.style.display = dd.style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', () => {
      dd.style.display = 'none';
    });
  
    // Llamada genérica
    async function postJSON(url, data) {
      const res = await fetch(url, {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify(data)
      });
      return res.json();
    }
  
    // Función global para añadir al carrito
    window.addToCart = async function(productId, qty = 1) {
      try {
        // Ruta relativa desde /UrbanJ/index.php => api/cart_add.php
        const res = await postJSON('api/cart_add.php', { id: productId, qty });
        if (res.success) {
          // Actualiza todos los badges
          document.querySelectorAll('.nav__badge').forEach(b => {
            b.textContent = res.totalQty;
          });
        } else {
          alert('Error: ' + res.msg);
        }
      } catch (err) {
        console.error(err);
        alert('Error al conectar con el servidor.');
      }
    };
  
    // Comprar ahora: añade + redirige
    window.comprar = async function(productId) {
      await window.addToCart(productId, 1);
      window.location.href = 'carrito.php';
    };
  });
  
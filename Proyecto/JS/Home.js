// /UrbanJ/js/Home.js

document.addEventListener('DOMContentLoaded', () => {
  // Scroll horizontal con mouse click + drag
  document.querySelectorAll('.carousel, .productos-carousel').forEach(carousel => {
    let isDown = false, startX, scrollLeft;

    carousel.addEventListener('mousedown', e => {
      isDown = true;
      carousel.classList.add('active');
      startX = e.pageX - carousel.offsetLeft;
      scrollLeft = carousel.scrollLeft;
    });

    carousel.addEventListener('mouseleave', () => {
      isDown = false;
      carousel.classList.remove('active');
    });

    carousel.addEventListener('mouseup', () => {
      isDown = false;
      carousel.classList.remove('active');
    });

    carousel.addEventListener('mousemove', e => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - carousel.offsetLeft;
      const walk = (x - startX) * 1;
      carousel.scrollLeft = scrollLeft - walk;
    });
  });
});

// ✅ Añadir al carrito
function addToCart(id) {
  fetch('/UrbanJ/api/cart_add.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'id=' + encodeURIComponent(id)
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Producto añadido al carrito');
      updateCartCount();
    } else {
      alert('Error al añadir el producto');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('No se pudo conectar con el servidor.');
  });
}

// ✅ Comprar: Añadir y redirigir
function comprar(id) {
  addToCart(id);
  setTimeout(() => {
    window.location.href = "/UrbanJ/carrito.php";
  }, 400);
}

// ✅ Actualizar número del carrito
function updateCartCount() {
  fetch('/UrbanJ/api/cart_count.php')
    .then(res => res.json())
    .then(data => {
      const badge = document.querySelector('.cart-count');
      if (badge) {
        badge.textContent = data.total || 0;
      }
    })
    .catch(err => console.error('Error actualizando contador:', err));
}

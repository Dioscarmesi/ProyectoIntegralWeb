document.addEventListener('DOMContentLoaded', () => {
  // Remover item desde carrito
  document.querySelectorAll('.btn-remove').forEach(btn => {
    btn.addEventListener('click', async e => {
      const tr = e.target.closest('tr');
      const productId = tr.dataset.id;
      await postJSON('api/cart_remove.php', { id: productId });
      tr.remove();
      recalcTotal();
      updateHeaderCart(); 
    });
  });

  // Cambiar cantidad en carrito
  document.querySelectorAll('.cart-qty').forEach(input => {
    input.addEventListener('change', async e => {
      let qty = parseInt(e.target.value);
      if (qty < 1) qty = 1;
      e.target.value = qty;
      const tr = e.target.closest('tr');
      const productId = tr.dataset.id;
      const res = await postJSON('api/cart_add.php', { id: productId, qty });
      const price = parseFloat(tr.querySelector('td:nth-child(2)').textContent.replace('$',''));
      tr.querySelector('.cart-sub').textContent = '$' + (price * qty).toFixed(2);
      recalcTotal();
      updateHeaderCart();
    });
  });
  
  // Añadir al carrito desde tienda con SweetAlert2
  document.querySelectorAll('.form-add-cart').forEach(form => {
    form.addEventListener('submit', async e => {
      e.preventDefault();
      const productId = form.dataset.id;

      const res = await postJSON('api/cart_add.php', {
        id: parseInt(productId),
        qty: 1
      });
      if (res.success) {
        Swal.fire({
          toast: true,
          icon: 'success',
          title: 'Producto añadido al carrito',
          position: 'top-end',
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true
        });
        updateHeaderCart(res.totalQty);
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Hubo un error al añadir el producto.'
        });
      }
    });
  });
});

// Recalcula el total del carrito
function recalcTotal() {
  let sum = 0;
  document.querySelectorAll('.cart-sub').forEach(td => {
    sum += parseFloat(td.textContent.replace('$',''));
  });
  document.getElementById('cart-total').textContent = sum.toFixed(2);
}

// Actualiza el badge del carrito en el header
async function updateHeaderCart(qty = null) {
  if (qty !== null) {
    document.querySelectorAll('.nav__badge').forEach(b => b.textContent = qty);
    return;
  }

  const res = await postJSON('api/cart_add.php', { id: 0, qty: 0 }); // Dummy
  document.querySelectorAll('.nav__badge').forEach(b => b.textContent = res.totalQty);
}

// Enviar JSON por POST
async function postJSON(url, data) {
  const r = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  return r.json();
}

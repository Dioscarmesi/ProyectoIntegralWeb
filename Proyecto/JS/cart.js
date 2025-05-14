document.addEventListener('DOMContentLoaded', () => {
  // Remover item
  document.querySelectorAll('.btn-remove').forEach(btn => {
    btn.addEventListener('click', async e => {
      const tr = e.target.closest('tr');
      const productId = tr.dataset.id;
      await postJSON('api/cart_remove.php', { id: productId });
      // refrescar fila o recargar página
      tr.remove();
      recalcTotal();
      updateHeaderCart(); 
    });
  });

  // Cambiar cantidad
  document.querySelectorAll('.cart-qty').forEach(input => {
    input.addEventListener('change', async e => {
      let qty = parseInt(e.target.value);
      if (qty < 1) qty = 1;
      e.target.value = qty;
      const tr = e.target.closest('tr');
      const productId = tr.dataset.id;
      const res = await postJSON('api/cart_add.php', { id: productId, qty });
      // actualizar subtotal y header badge
      const price = parseFloat(tr.querySelector('td:nth-child(2)').textContent.replace('$',''));
      tr.querySelector('.cart-sub').textContent = '$' + (price*qty).toFixed(2);
      recalcTotal();
      updateHeaderCart();
    });
  });
});

// Helper para recalcular total en el pie
function recalcTotal() {
  let sum = 0;
  document.querySelectorAll('.cart-sub').forEach(td => {
    sum += parseFloat(td.textContent.replace('$',''));
  });
  document.getElementById('cart-total').textContent = sum.toFixed(2);
}

// Header badge
async function updateHeaderCart() {
  const res = await postJSON('api/cart_add.php', {id:0, qty:0}); // devuelve { totalQty }
  document.querySelectorAll('.nav__badge').forEach(b => b.textContent = res.totalQty);
}

// Llamada genérica
async function postJSON(url, data) {
  const r = await fetch(url, {
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body: JSON.stringify(data)
  });
  return r.json();
}

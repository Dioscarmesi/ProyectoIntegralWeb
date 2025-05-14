// /UrbanJ/js/TablaProductos.js
document.addEventListener('DOMContentLoaded', () => {
  // Borrar producto con confirm
  document.querySelectorAll('.btn-borrar').forEach(btn => {
    btn.addEventListener('click', e => {
      if (!confirm('¿Seguro quieres borrar este producto?')) return;
      const id = btn.dataset.id;
      fetch(`borrar_producto.php?id=${id}`)
        .then(r => r.json())
        .then(res => {
          if (res.success) location.reload();
          else alert('Error al borrar.');
        });
    });
  });

  // Modal “Ver” producto
  document.querySelectorAll('.btn-ver').forEach(btn => {
    btn.addEventListener('click', async () => {
      const id = btn.dataset.id;
      const resp = await fetch(`leer_producto.php?id=${id}`);
      const html = await resp.text();
      const modal = document.createElement('div');
      modal.className = 'modal-overlay';
      modal.innerHTML = html;
      document.body.appendChild(modal);
      modal.addEventListener('click', e => {
        if (e.target === modal || e.target.classList.contains('close-modal')) {
          modal.remove();
        }
      });
    });
  });
});

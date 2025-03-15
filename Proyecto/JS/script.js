document.addEventListener("DOMContentLoaded", function() {
    cargarProductos();
});
 
// Productos 
const productos = [
{ nombre: "Producto 1", precio: 100, descripcion: "Descripción 1", imagen: "https://via.placeholder.com/150" },
{ nombre: "Producto 2", precio: 200, descripcion: "Descripción 2", imagen: "https://via.placeholder.com/150" },
{ nombre: "Producto 3", precio: 300, descripcion: "Descripción 3", imagen: "https://via.placeholder.com/150" }
];
 
function cargarProductos() {
    const container = document.getElementById("productos-container");
    productos.forEach(producto => {
        let div = document.createElement("div");
        div.classList.add("producto");
        div.innerHTML = `
            <img src="${producto.imagen}" alt="${producto.nombre}">
            <h3>${producto.nombre}</h3>
            <p>${producto.descripcion}</p>
            <p><strong>${producto.precio}$</strong></p>
            <button onclick="agregarAlCarrito(${producto.precio})">Comprar</button>
        `;
        container.appendChild(div);
    });
}
 
let totalCarrito = 0;
function agregarAlCarrito(precio) {
    totalCarrito += precio;
    document.getElementById("cart-count").innerText = totalCarrito;
}
 
// Cambiar login y registro
function mostrarFormulario(tipo) {
    document.getElementById("form-login").classList.toggle("hidden", tipo !== "login");
    document.getElementById("form-registro").classList.toggle("hidden", tipo !== "registro");
}
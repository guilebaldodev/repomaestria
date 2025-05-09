document.addEventListener("DOMContentLoaded", () => {
    const tabla = document.getElementById("tablaUsuarios");
    const form = document.getElementById("formAgregarUsuario");

    function cargarUsuarios() {
        fetch("obtener_usuarios.php")
            .then(res => res.json())
            .then(data => {
                tabla.innerHTML = "";
                data.forEach(usuario => {
                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td>${usuario.id}</td>
                        <td>${usuario.nombre}</td>
                        <td>${usuario.correo}</td>
                        <td>${usuario.rol}</td>
                        <td>${usuario.estado}</td>
                        <td><button onclick="eliminarUsuario(${usuario.id})">Eliminar</button></td>
                    `;
                    tabla.appendChild(fila);
                });
            });
    }

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const datos = new FormData(form);
        fetch("agregar_usuario.php", {
            method: "POST",
            body: datos
        })
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            form.reset();
            cargarUsuarios();
        });
    });

    window.eliminarUsuario = (id) => {
        if (confirm("¿Seguro que deseas eliminar este usuario?")) {
            fetch("eliminar_usuario.php?id=" + id)
                .then(res => res.text())
                .then(msg => {
                    alert(msg);
                    cargarUsuarios();
                });
        }
    };

    cargarUsuarios();
});
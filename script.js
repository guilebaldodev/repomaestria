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
                    <td>
  
                    <button class="btn btn-warning btn-sm me-2"
        data-bs-toggle="modal"
        data-bs-target="#modalEditarUsuario"
        onclick="prepararEdicion(${usuario.id}, '${usuario.nombre}', '${usuario.correo}')">
    Editar
</button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(${usuario.id})">Eliminar</button>
                    </td>
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

    window.prepararEdicion = (id, nombre, correo) => {
    document.getElementById("editarId").value = id;
    document.getElementById("editarNombre").value = nombre;
    document.getElementById("editarCorreo").value = correo;
};

document.getElementById("formEditarUsuario").addEventListener("submit", function(e) {
    e.preventDefault();
    const datos = new FormData(this);
    fetch("editar_usuario.php", {
        method: "POST",
        body: datos
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        cargarUsuarios();
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarUsuario'));
        modal.hide();
    });
});

    cargarUsuarios();
});
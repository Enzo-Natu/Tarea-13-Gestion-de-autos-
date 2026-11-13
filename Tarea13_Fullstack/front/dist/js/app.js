const api = "/api/index.php?path=";
const lista = document.getElementById("lista-vehiculos");
const mensaje = document.getElementById("mensaje");

function mostrarPrecio(precio) {
    if (precio === null || precio === "") {
        return "No indicado";
    }
    return "$ " + Number(precio).toLocaleString("es-UY", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

async function cargarVehiculos() {
    try {
        const respuesta = await fetch(api + "vehiculos");
        const vehiculos = await respuesta.json();

        if (!respuesta.ok) {
            throw new Error("No se pudieron cargar los vehículos.");
        }

        if (vehiculos.length === 0) {
            lista.innerHTML = '<tr><td colspan="6">No hay vehículos registrados.</td></tr>';
            return;
        }

        lista.innerHTML = vehiculos.map(auto => `
            <tr>
                <td>${auto.nombre_marca}</td>
                <td>${auto.modelo}</td>
                <td>${auto.anio ?? "No indicado"}</td>
                <td>${mostrarPrecio(auto.precio)}</td>
                <td><span class="estado">${auto.estado}</span></td>
                <td><a class="enlace" href="dist/paginas/vehiculo.html?id=${auto.id_vehiculo}">Ver / modificar</a></td>
            </tr>
        `).join("");
    } catch (error) {
        mensaje.textContent = error.message;
        mensaje.className = "mensaje error";
        lista.innerHTML = '<tr><td colspan="6">No fue posible cargar los datos.</td></tr>';
    }
}

cargarVehiculos();

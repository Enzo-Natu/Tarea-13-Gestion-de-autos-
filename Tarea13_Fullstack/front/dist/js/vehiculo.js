const api = "/api/index.php?path=";
const formulario = document.getElementById("form-vehiculo");
const mensaje = document.getElementById("mensaje");
const selectorMarca = document.getElementById("id_marca");
const idVehiculo = new URLSearchParams(window.location.search).get("id");

async function cargarDatos() {
    if (!idVehiculo) {
        mensaje.textContent = "No se indicó qué vehículo consultar.";
        mensaje.className = "mensaje error";
        formulario.hidden = true;
        return;
    }

    try {
        const [respuestaAuto, respuestaMarcas] = await Promise.all([
            fetch(api + "vehiculos/" + idVehiculo),
            fetch(api + "marcas")
        ]);

        const auto = await respuestaAuto.json();
        const marcas = await respuestaMarcas.json();

        if (!respuestaAuto.ok) {
            throw new Error(auto.mensaje || "No se encontró el vehículo.");
        }

        marcas.forEach(marca => {
            const opcion = document.createElement("option");
            opcion.value = marca.id_marca;
            opcion.textContent = marca.nombre_marca;
            selectorMarca.appendChild(opcion);
        });

        selectorMarca.value = auto.id_marca;
        document.getElementById("modelo").value = auto.modelo;
        document.getElementById("anio").value = auto.anio ?? "";
        document.getElementById("precio").value = auto.precio ?? "";
        document.getElementById("estado").value = auto.estado;
    } catch (error) {
        mensaje.textContent = error.message;
        mensaje.className = "mensaje error";
        formulario.hidden = true;
    }
}

formulario.addEventListener("submit", async function(evento) {
    evento.preventDefault();

    const datos = {
        id_marca: selectorMarca.value,
        modelo: document.getElementById("modelo").value,
        anio: document.getElementById("anio").value,
        precio: document.getElementById("precio").value,
        estado: document.getElementById("estado").value
    };

    try {
        const respuesta = await fetch(api + "vehiculos/" + idVehiculo, {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        });
        const resultado = await respuesta.json();

        if (!respuesta.ok) {
            throw new Error(resultado.mensaje || "No se pudieron guardar los cambios.");
        }

        mensaje.textContent = resultado.mensaje;
        mensaje.className = "mensaje correcto";
    } catch (error) {
        mensaje.textContent = error.message;
        mensaje.className = "mensaje error";
    }
});

cargarDatos();

const api = "/api/index.php?path=";
const formulario = document.getElementById("form-agregar");
const mensaje = document.getElementById("mensaje");
const selectorMarca = document.getElementById("id_marca");

async function cargarMarcas() {
    try {
        const respuesta = await fetch(api + "marcas");
        const marcas = await respuesta.json();

        if (!respuesta.ok) {
            throw new Error("No se pudieron cargar las marcas.");
        }

        marcas.forEach(marca => {
            const opcion = document.createElement("option");
            opcion.value = marca.id_marca;
            opcion.textContent = marca.nombre_marca;
            selectorMarca.appendChild(opcion);
        });
    } catch (error) {
        mensaje.textContent = error.message;
        mensaje.className = "mensaje error";
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
        const respuesta = await fetch(api + "vehiculos", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        });
        const resultado = await respuesta.json();

        if (!respuesta.ok) {
            throw new Error(resultado.mensaje || "No se pudo guardar el vehículo.");
        }

        mensaje.textContent = resultado.mensaje;
        mensaje.className = "mensaje correcto";
        formulario.reset();
    } catch (error) {
        mensaje.textContent = error.message;
        mensaje.className = "mensaje error";
    }
});

cargarMarcas();

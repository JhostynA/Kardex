<?php require_once '../../contenido.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center text-light bg-dark p-3 rounded">Registrar Producto</h1>
    <ol class="breadcrumb mb-4 bg-light p-2 rounded shadow-sm">
        <li class="breadcrumb-item active">Productos en el sistema</li>
    </ol>
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-header bg-gradient-primary text-black">
            <h5 class="m-0">Complete los datos</h5>
        </div>
        <div class="card-body p-4">
            <form action="" id="form-registro-productos" autocomplete="off">

                <div class="row g-4">
                    <div class="col-md-6 mb-3">
                        <div class="form-floating shadow-sm">
                            <select name="idtipoproducto" id="idtipoproducto" class="form-select" required>
                                <option value="">Seleccione</option>
                                <option value="1">Analgesico</option>
                                <option value="2">Antibiotico</option>
                                <option value="3">Antihistaminico</option>
                            </select>
                            <label for="idtipoproducto">Tipo Producto</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-floating shadow-sm">
                            <select name="idmarca" id="idmarca" class="form-select" required>
                                <option value="">Seleccione</option>
                                <option value="1">ABBOTT</option>
                                <option value="2">ABBOTT SIMILAC</option>
                                <option value="3">ABBVIE SPAIN</option>
                            </select>
                            <label for="idmarca">Laboratorio</label>
                        </div>
                    </div>
                </div> 

                <hr>

                <div class="row g-4">
                    <div class="col-md-6 mb-3">
                        <div class="form-floating shadow-sm">
                            <select name="idtipopresentacion" id="idtipopresentacion" class="form-select" required>
                                <option value="">Seleccione</option>
                                <option value="1">Tableta</option>
                                <option value="2">Capsula</option>
                                <option value="3">Jarabe</option>
                            </select>
                            <label for="idtipopresentacion">Tipo Presentación</label>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-floating shadow-sm">
                            <input type="text" class="form-control" maxlength="50" id="descripcion" required>
                            <label for="descripcion">Descripción</label>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" id="registrar-producto" class="btn btn-success btn-sm">Registrar Producto</button>
                    <button type="reset" class="btn btn-warning btn-sm">Cancelar proceso</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../footer.php'; ?>



<script>
  document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("#form-registro-productos");

    async function registrarProducto(){
      const params = new FormData();
      params.append("operation", "add");
      params.append("idtipoproducto", document.querySelector("#idtipoproducto").value);
      params.append("idmarca", document.querySelector("#idmarca").value);
      params.append("idtipopresentacion", document.querySelector("#idtipopresentacion").value);
      params.append("descripcion", document.querySelector("#descripcion").value);

      const options = {
        method: "POST",
        body: params
      };

      const response = await fetch(`../../controllers/producto.controller.php`, options);
      return response.json();
    }

    async function verificarProducto(){
      const params = new URLSearchParams();
      params.append("operation", "verify");
      params.append("idtipoproducto", document.querySelector("#idtipoproducto").value);
      params.append("idmarca", document.querySelector("#idmarca").value);
      params.append("idtipopresentacion", document.querySelector("#idtipopresentacion").value);
      params.append("descripcion", document.querySelector("#descripcion").value);

      const response = await fetch(`../../controllers/producto.controller.php?${params}`);
      return response.json();
    }

    form.addEventListener("submit", async (event) => {
      event.preventDefault();

      const verifyResponse = await verificarProducto();
      if (verifyResponse.exists) {
        alert("El producto ya existe.");
      } else {
        const registerResponse = await registrarProducto();
        if (registerResponse.idproducto) {
          alert("Producto registrado con éxito.");
          form.reset();
        } else {
          alert("Error al registrar el producto.");
        }
      }
    });
  });
</script>

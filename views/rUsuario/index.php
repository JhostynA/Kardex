<?php require_once '../../contenido.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center text-light bg-dark p-3 rounded">Registrar Usuarios</h1>
    <ol class="breadcrumb mb-4 bg-light p-2 rounded shadow-sm">
        <li class="breadcrumb-item active">Personas con acceso al sistema</li>
    </ol>
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-header bg-gradient-primary text-black">
            <h5 class="m-0">Complete los datos</h5>
        </div>
        <div class="card-body p-4">
            <form action="" id="form-registro-usuarios" autocomplete="off">
                <div class="row g-4">
                    <div class="col-md-4 mb-3">
                        <div class="input-group shadow-sm">
                            <div class="form-floating flex-grow-1">
                                <input type="tel" class="form-control" maxlength="8" minlength="8" pattern="[0-9]+" title="Solo números" id="nrodocumento" autofocus required>
                                <label for="nrodocumento">Número documento</label>
                            </div>
                            <button class="btn btn-outline-secondary" type="button" id="buscar-nrodocumento">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-floating shadow-sm">
                            <input type="text" class="form-control" maxlength="9" id="telefonoP">
                            <label for="telefonoP">Teléfono Principal</label>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-floating shadow-sm">
                            <input type="text" class="form-control" maxlength="9" id="telefonoS">
                            <label for="telefonoS">Teléfono Secundario</label>
                        </div>
                    </div>
                </div> 

                <div class="row g-4">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating shadow-sm">
                            <input type="text" class="form-control" maxlength="50" id="apellidoP" required>
                            <label for="apellidoP">Apellido Paterno</label>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-floating shadow-sm">
                            <input type="text" class="form-control" maxlength="50" id="apellidoM" required>
                            <label for="apellidoM">Apellido Materno</label>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="form-floating shadow-sm">
                            <input type="text" class="form-control" maxlength="50" id="nombres" required>
                            <label for="nombres">Nombres</label>
                        </div>
                    </div>
                </div> 

                <hr>

                <div class="row g-4">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating shadow-sm">
                            <input type="text" id="nomusuario" maxlength="150" class="form-control" required>
                            <label for="nomusuario">Nombre Usuario</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating shadow-sm">
                            <input type="password" id="passusuario" maxlength="20" minlength="8" class="form-control" required>
                            <label for="passusuario">Contraseña</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-floating shadow-sm">
                            <select name="idro" id="idrol" class="form-select" required>
                                <option value="">Seleccione</option>
                                <option value=1>Administrador</option>
                                <option value=2>Farmacéutico</option>
                                <option value=3>Asistente de Farmacia</option>
                                <option value=4>Cajero</option>
                            </select>
                            <label for="idrol">Roles</label>
                        </div>
                    </div>
                </div> 

                <div class="text-end">
                    <button type="submit" id="registrar-usuario" class="btn btn-success btn-sm">Registrar usuario</button>
                    <button type="reset" class="btn btn-warning btn-sm">Cancelar proceso</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php require_once '../../footer.php'; ?>


<script>
  document.addEventListener("DOMContentLoaded", () => {
    
    const nrodocumento = document.querySelector("#nrodocumento");
    
    let idpersona = -1;
    let datosNuevos = true; 
    
    async function registrarPersona(){
      
      const params = new FormData();
      params.append("operation", "add");
      params.append("apepaterno", document.querySelector("#apellidoP").value);
      params.append("apematerno", document.querySelector("#apellidoM").value);
      params.append("nombres", document.querySelector("#nombres").value);
      params.append("nrodocumento", document.querySelector("#nrodocumento").value);
      params.append("telprincipal", document.querySelector("#telefonoP").value);
      params.append("telsecundario", document.querySelector("#telefonoS").value);


      const options = {
        method: "POST",
        body: params
      };

      const idpersona = await fetch(`../../controllers/persona.controller.php`, options)
      return idpersona.json();
    }

    async function registrarColaborador(idpersona){
      const params = new FormData();
      params.append("operation", "add");
      params.append("idpersona", idpersona);
      params.append("idrol", document.querySelector("#idrol").value);
      params.append("nomusuario", document.querySelector("#nomusuario").value);
      params.append("passusuario", document.querySelector("#passusuario").value);

      const options = {
        method: "POST",
        body: params
      }

      const idcolaboradores = await fetch(`../../controllers/colaboradores.controller.php`, options);
      return idcolaboradores.json();
    }

    async function buscarDocumento(){
      const params = new URLSearchParams();
      params.append("operation", "searchByDoc");
      params.append("nrodocumento", nrodocumento.value);

      const response = await fetch(`../../controllers/persona.controller.php?${params}`);
      return response.json();
    }


    function validarDocumento(response){
      if (response.length == 0){
        //No encontramos la persona
        document.querySelector("#apellidoP").value = ``;
        document.querySelector("#apellidoM").value = ``;
        document.querySelector("#nombres").value = ``;
        document.querySelector("#telefonoP").value = ``;
        document.querySelector("#telefonoS").value = ``;
        adPersona(true); 
        adUsuario(true);
        datosNuevos = true; 
        idpersona = -1;     
        document.querySelector("#telefonoP").focus();
      }else{
        
        datosNuevos = false;
        idpersona = response[0].idpersona;
        document.querySelector("#apellidoP").value = response[0].apepaterno;
        document.querySelector("#apellidoM").value = response[0].apematerno;
        document.querySelector("#nombres").value =response[0].nombres;
        document.querySelector("#telefonoP").value = response[0].telprincipal;
        document.querySelector("#telefonoS").value = response[0].telsecundario;
        adPersona(false); 
        
        
        if (response[0].idcolaboradores === null){
          
          adUsuario(true);
        }else{
          
          adUsuario(false);
          alert("Esta persona ya cuenta con un perfil de usuario");
        }
      }
    }

    
    nrodocumento.addEventListener("keypress", async (event) => {
      if (event.keyCode == 13){
        const response = await buscarDocumento();
        validarDocumento(response);
      }
    });
    
    document.querySelector("#buscar-nrodocumento").addEventListener("click", async () => {
      const response = await buscarDocumento();
      validarDocumento(response);
    });


    function adPersona(sw = false){
      if (!sw){
        document.querySelector("#apellidoP").setAttribute("disabled", true);
        document.querySelector("#apellidoM").setAttribute("disabled", true);
        document.querySelector("#nombres").setAttribute("disabled", true);
        document.querySelector("#telefonoP").setAttribute("disabled", true);
        document.querySelector("#telefonoS").setAttribute("disabled", true);

      }else{
        document.querySelector("#apellidoP").removeAttribute("disabled");
        document.querySelector("#apellidoM").removeAttribute("disabled");
        document.querySelector("#nombres").removeAttribute("disabled");
        document.querySelector("#telefonoP").removeAttribute("disabled");
        document.querySelector("#telefonoS").removeAttribute("disabled");
      }
    }

    function adUsuario(sw = false){
      if (!sw){
        document.querySelector("#idrol").setAttribute("disabled", true);
        document.querySelector("#nomusuario").setAttribute("disabled", true);
        document.querySelector("#passusuario").setAttribute("disabled", true);
        document.querySelector("#registrar-usuario").setAttribute("disabled", true);
      }else{
        document.querySelector("#idrol").removeAttribute("disabled");
        document.querySelector("#nomusuario").removeAttribute("disabled");
        document.querySelector("#passusuario").removeAttribute("disabled");
        document.querySelector("#registrar-usuario").removeAttribute("disabled");
      }
    }

    document.querySelector("#form-registro-usuarios").addEventListener("submit", async (event) => {
      event.preventDefault();

      if (confirm("¿Está seguro de proceder?")){
        
        let response1;  
        let response2;  

        if (datosNuevos){
          response1 = await registrarPersona(); 
          idpersona = response1.idpersona;      
        }

        
        if (idpersona == -1){
          alert("No se pudo registrar los datos del usuario, verifique DNI")
        }else{
          
          response2 = await registrarColaborador(idpersona);
          if (response2.idcolaboradores == -1){
            alert("No se pudo crear tu cuenta de usuario, verifique EMAIL");
          }else{
            
            document.querySelector("#form-registro-usuarios").reset();
          }
        }
      }
    });

    adPersona();

  })
</script>

</body>
</html>
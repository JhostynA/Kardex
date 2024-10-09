<?php require_once '../../contenido.php'; 
require_once '../../models/ListarU.php'; 

$usuariosModel = new ListarU();
$usuarios = $usuariosModel->listarUsuarios();
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="text-center">Lista de Usuarios</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="tabla-usuario">
                    <thead>
                        <tr>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Nombres</th>
                            <th>Número de Documento</th>
                            <th>Nombre de Usuario</th>
                            <th>Teléfono Principal</th>
                            <th>Teléfono Secundario</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['apepaterno'] ?></td>
                            <td><?= $usuario['apematerno'] ?></td>
                            <td><?= $usuario['nombres'] ?></td>
                            <td><?= $usuario['nrodocumento'] ?></td>
                            <td><?= $usuario['nomusuario'] ?></td>
                            <td><?= $usuario['telprincipal'] ?></td>
                            <td><?= $usuario['telsecundario'] ?></td>
                            <td><?= $usuario['rol'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div> 
    </div> 
</div>


<?php require_once '../../footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script src="//cdn.datatables.net/2.0.5/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>

<script>
    $(document).ready( function() {
        $('#tabla-usuario').DataTable();
    });

    function renderDataTable(){
        const dtContactos = new DataTable("#tabla-usuario", {
        language: { url: "<?= $host ?>/js/Spanish.json" }
        });
    }

    renderDataTable();
</script>
</body>
</html>

<?php 
require_once '../../contenido.php'; 
require_once '../../models/ListarP.php'; 

$productoModel = new ListarP();
$productos = $productoModel->listarProductos();
?>

<div class="container mt-5">
    <div class="card">
        <h2 class="text-center" style="color: #007bff;">Lista de Productos</h2>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tabla-productos" data-order='[[0, "desc"]]' data-page-length='10' style="width: 100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Presentación</th>
                            <th>Marca</th>
                            <th>Tipo Producto</th>
                            <th>Stock Actual</th>
                            <th>Manejar Kardex</th>
                            <th>Historial</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= $producto['idproducto'] ?></td>
                            <td><?= $producto['descripcion'] ?></td>
                            <td><?= $producto['tipopresentacion'] ?></td>
                            <td><?= $producto['marca'] ?></td>
                            <td><?= $producto['tipoproducto'] ?></td>
                            <td><?= $producto['stock_actual'] ?></td>
                            <td>
                                <button class="btn btn-primary" onclick="abrirModal(<?= $producto['idproducto'] ?>, <?= $producto['stock_actual'] ?>)">Manejar Kardex</button>
                            </td>
                            <td>
                                <button class="btn btn-primary" onclick="abrirHistorial(<?= $producto['idproducto'] ?>)">Historial</button>
                            </td>
                            
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKardex" tabindex="-1" aria-labelledby="modalKardexLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKardexLabel">Manejo de Kardex</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formKardex">
                    <input type="hidden" id="idproducto">
                    <input type="hidden" id="stock_actual">
                    <div class="mb-3">
                        <label for="tipomovimiento" class="form-label">Tipo de Movimiento</label>
                        <select class="form-select" id="tipomovimiento" required>
                            <option value="entrada">Entrada</option>
                            <option value="salida">Salida</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecharegistro" class="form-label">Fecha de Registro</label>
                        <input type="date" class="form-control" id="fecharegistro" required>
                    </div>
                </form>
            </div>
            <div id="alertaStock" class="alert alert-danger d-none" role="alert">
                La cantidad ingresada supera el stock actual del producto.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarKardex()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHistorial" tabindex="-1" aria-labelledby="modalHistorialLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHistorialLabel">Historial de Movimientos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered" id="tabla-historia" data-order='[[0, "desc"]]' data-page-length='10' style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Nombre del Producto</th>
                            <th>Stock</th>
                            <th>Tipo Movimiento</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody id="tablaHistorialBody">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<?php require_once '../../footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.datatables.net/2.0.5/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>


<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script> 


<script>

    function renderDataTable(){
        const dtProductos = new DataTable("#tabla-productos", {
        language: { url: "<?= $host ?>/js/Spanish.json" }
        });
    }

    function abrirModal(idproducto, stockactual) {
        $('#idproducto').val(idproducto);
        $('#stock_actual').val(stockactual);
        $('#modalKardex').modal('show');
    }

    function guardarKardex() {
        const idproducto = $('#idproducto').val();
        const tipomovimiento = $('#tipomovimiento ').val();
        const cantidad = $('#cantidad').val();
        const fecharegistro = $('#fecharegistro').val();
        const stockactual = parseInt($('#stock_actual').val()); 

        if (tipomovimiento === 'salida' && parseInt(cantidad) > stockactual) {
            $('#alertaStock').removeClass('d-none'); 
            return; 
        } else {
            $('#alertaStock').addClass('d-none'); 
        }

        $.ajax({
            url: '../../controllers/kardex.controller.php',
            type: 'POST',
            data: {
                idproducto: idproducto,
                tipomovimiento: tipomovimiento,
                cantidad: cantidad,
                fecharegistro: fecharegistro
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.success) {
                    $('#modalKardex').modal('hide');
                    location.reload();
                } else {
                    alert('Error al guardar el movimiento del Kardex');
                }
            },
            error: function(xhr, status, error) {
                alert('Error al guardar el movimiento del Kardex');
            }
        });
    }

    function abrirHistorial(idProducto) {
        $.ajax({
            url: '../../controllers/kardexH.controller.php',
            type: 'POST',
            data: { idProducto: idProducto },
            success: function(response) {
                const historial = JSON.parse(response);
                const tablaBody = $('#tablaHistorialBody');
                tablaBody.empty(); 
                historial.forEach((movimiento) => {
                    tablaBody.append(`
                        <tr>
                            <td>${movimiento.nombre_producto}</td>
                            <td>${movimiento.stock_anterior}</td>
                            <td>${movimiento.tipomovimiento}</td>
                            <td>${movimiento.cantidad}</td>
                            <td>${movimiento.fecharegistro}</td>
                        </tr>
                    `);
                });
            },
            error: function(xhr, status, error) {
                alert('Error al cargar el historial.');
            }
        });
        $('#modalHistorial').modal('show');
    }

    renderDataTable();
</script>


</body>
</html>

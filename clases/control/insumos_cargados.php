<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit(); } 
?>
<div class="panel panel-default">
<div class="panel-body" id="Panel1" style="height:150px;">
<table class="table table-striped table-hover">
<thead>
<tr class="active">
<th>Nombre</th>
<th>Cantidad Consumida</th>
<th>Eliminar</th>
</tr>
</thead>
<tbody>
<?php
$sqlinsumos = "SELECT
tb_stock.id_stock AS id,
concat(tb_insumo.nombre,' ',tb_marca.nombre) AS nombre,
tb_stock.egreso AS cantidad
FROM
tb_stock
INNER JOIN tb_insumo ON tb_stock.id_insumo = tb_insumo.id_insumo
INNER JOIN tb_marca ON tb_marca.id_marca = tb_insumo.id_marca
WHERE
tb_stock.estado = '0'
ORDER BY
tb_stock.id_stock ASC

";
$rsinsumos = mysqli_query($conexion, $sqlinsumos);
$cantidad =  mysqli_num_rows($rsinsumos);
if ($cantidad > 0) { // si existen insumos con de esa finca se muestran, de lo contrario queda en blanco  
$contador = 0;
while ($datos = mysqli_fetch_array($rsinsumos)){
$nombreinsumo=utf8_encode($datos['nombre']);
$cantidad=utf8_encode($datos['cantidad']);
$id=$datos['id'];
$contador = $contador + 1;
echo '
<tr>
<td>'.$nombreinsumo.'</td>
<td>'.$cantidad.'</td>
<td><button type="button" id="boton_eleminar" class="ver_modal ver_modal-danger ver_modal-xs" value="'.$id.'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>
</td>
</tr>
';
}   
// $idinicial=$id-$contador+1;
// $idfinal=$id;
// echo '<input type="text" class="form-control" id="idinicial" value="'.$idinicial.'">
// <input type="text" class="form-control" id="idfinal" value="'.$idfinal.'">'; 
}
?>
</tbody>
</table> 
<?php
if ($cantidad == 0){
// echo "La finca no tiene insumos cargados.";
?>
<script type="text/javascript">
$('#div_insumos_cargados').html('')
</script>
<?php
}
?>
</div>
</div>
<script type="text/javascript">
$(function() {
$('.ver_modal-danger').click(function() {
var numero = $(this).val()
var pars = "id=" + numero + "&";
$("#div_insumos_cargados").html('<div class="text-center"><div class="loadingsm"></div></div>');
$.ajax({
url : "clases/elimina/elimina_insumo.php",
data : pars,
dataType : "json",
type : "get",
success: function(data){
if (data.success == 'true') {
$("#div_insumos_cargados").load("clases/control/insumos_cargados.php");
} else {
$('#div_insumos_cargados').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error reintente!</div>');        
setTimeout("$('#mensaje_general').alert('close')", 2000);
}
}
});
})
})
</script>
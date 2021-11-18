<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
?>

<div class="panel panel-default">

<div class="panel-body" id="Panel1" style="height:220px">
<table class="table table-striped table-hover">
  <thead>
    <tr class="active">
      <th>Insumo</th>
      <th>Marca</th>
      <th>Cantidad</th>
      <th>U. Medida</th>
      </tr>
  </thead>
  <tbody>
<?php

include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}

$insumo=$_REQUEST['dato_insumo'];

$consulta_insumos = "";
if ($insumo != "0") {
$consulta_insumos = "AND tb_stock.id_insumo = '$insumo'";
}
        


$sqlinsumo = "SELECT
tb_insumo.nombre as insumo,
tb_marca.nombre as marca,
tb_stock.saldo as cantidad,
tb_unidad.nombre as unidad
FROM
tb_stock
LEFT JOIN tb_insumo ON tb_insumo.id_insumo = tb_stock.id_insumo
LEFT JOIN tb_unidad ON tb_unidad.id_unidad = tb_insumo.id_unidad
LEFT JOIN tb_marca ON tb_marca.id_marca = tb_insumo.id_marca
WHERE
tb_stock.id_stock IN ((SELECT MAX(tb_stock.id_stock ) FROM tb_stock GROUP BY tb_stock.id_insumo)) $consulta_insumos
ORDER BY
tb_insumo.nombre asc";

$rsinsumo = mysqli_query($conexion, $sqlinsumo);
$cantidad =  mysqli_num_rows($rsinsumo);
if ($cantidad > 0) { // si existen insumo con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rsinsumo)){
$insumo=utf8_encode($datos['insumo']);
$marca=$datos['marca'];
$cantidad=utf8_encode($datos['cantidad']);
$unidad=utf8_encode($datos['unidad']);

echo '
<tr>
<td>'.$insumo.'</td>
<td>'.$marca.'</td>
<td>'.$cantidad.'</td>
<td>'.$unidad.'</td>    
</tr>
';
?>
<script type="text/javascript">
document.getElementById("botonExcel1").style.visibility = "visible";
</script>
<?php
} 
}

?>

</tbody>
</table> 
<?php
if ($cantidad == 0){
echo "No hay registros";
?>
<script type="text/javascript">
document.getElementById("botonExcel1").style.visibility = "hidden";
</script>
<?php
}
?>


<script type="text/javascript">


$(function() {
        $('.form-control').change(function() {

          document.getElementById("botonExcel1").style.visibility = "hidden";
          
        })
      })


</script>

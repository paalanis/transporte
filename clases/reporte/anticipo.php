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

<!-- <div class="panel panel-default">

<div class="panel-body" id="Panel1" style="height:220px"> -->
<table class="table table-striped table-hover">
  <thead>
    <tr class="active">
      <th>Fecha</th>
      <th>Apellido, Nombre</th>
      <th>DNI</th>
      <th>Tipo anticipo</th>
      <th>Monto</th>
      <th>Liquidado</th>
      <th>Observación</th>
      <th>Eliminar</th>
      </tr>
  </thead>
  <tbody>
<?php

include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}

        $desde=$_REQUEST['dato_desde'];
        $hasta=$_REQUEST['dato_hasta'];
        $chofer=$_REQUEST['dato_chofer'];
        
        $consulta_chofer = "";

        if ($chofer != "0") {
        $consulta_chofer = "AND tb_transportista.id_transportista = '$chofer'";
        }



$sqlanticipos = "SELECT
tb_anticipo.id_anticipo as id,
DATE_FORMAT(tb_anticipo.fecha, '%d/%m/%Y') AS fecha,
Concat(tb_transportista.apellido,', ',tb_transportista.nombre) as chofer,
tb_transportista.dni as dni,
tb_tipo_anticipo.tipo_anticipo as tipo,
tb_anticipo.monto as monto,
tb_anticipo.obs as obs,
IF(tb_anticipo.id_liquidacion > 0, CONCAT('SI - ',tb_liquidacion.numero),'NO') AS liquida,
tb_anticipo.id_liquidacion as estado
FROM
tb_anticipo
LEFT JOIN tb_tipo_anticipo ON tb_anticipo.id_tipo_anticipo = tb_tipo_anticipo.id_tipo_anticipo
LEFT JOIN tb_transportista ON tb_anticipo.id_transportista = tb_transportista.id_transportista
LEFT JOIN tb_liquidacion ON tb_liquidacion.id_liquidacion = tb_anticipo.id_liquidacion
WHERE
tb_anticipo.fecha BETWEEN '$desde' AND '$hasta' $consulta_chofer
ORDER BY
tb_anticipo.fecha DESC";

$rsanticipos = mysqli_query($conexion, $sqlanticipos);
$cantidad =  mysqli_num_rows($rsanticipos);
if ($cantidad > 0) { // si existen anticipos con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rsanticipos)){
$fecha=utf8_encode($datos['fecha']);
$chofer=utf8_encode($datos['chofer']);
$dni=utf8_encode($datos['dni']);
$tipo=utf8_encode($datos['tipo']);
$monto=utf8_encode($datos['monto']);
$obs=utf8_encode($datos['obs']);
$id=utf8_encode($datos['id']);
$liquida=utf8_encode($datos['liquida']);
$estado=utf8_encode($datos['estado']);

echo '
<tr>
<td>'.$fecha.'</td>
<td>'.$chofer.'</td>
<td>'.$dni.'</td>
<td>'.$tipo.'</td>
<td>'.$monto.'</td>
<td>'.$liquida.'</td>
<td>'.$obs.'</td>';

if($estado == 0){ echo '
<td><button type="button" name="'.$id.'" id="boton_e" class="boton_eliminar boton_eliminar-danger boton_eliminar-xs">
    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
    </button></td>    
</tr>';}else{echo "";};
?>
<script type="text/javascript">
// document.getElementById("botonExcel1").style.visibility = "visible";
</script>
<?php
} 
}

?>
</tbody>
<h3>Cantidad total de anticipos <span class="label label-default"><?php echo $cantidad?></span></h3>
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


 $(document).ready(function () {
 $('#div_mensaje_general').html('')
  });


$(function() {
        $('.boton_eliminar-danger').click(function() {

        var id = $(this).attr('name')

        var pars = "id=" + id + "&"

        $("#div_mensaje_general").html('<div class="text-center"><div class="loadingsm"></div></div>');
        // alert(pars)
        $.ajax({
            url : "clases/elimina/anticipo.php",
            data : pars,
            dataType : "json",
            type : "get",

            success: function(data){
                
              if (data.success == 'true') {

                $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" id="cruz_msj" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Se eliminó anticipo!</div>');       
                $("#cruz_msj").focus()
                setTimeout("$('#mensaje_general').alert('close')", 1000);
                $("#div_reporte").html('<div class="text-center"><div class="loadingsm"></div></div>');
                setTimeout("reporte('anticipo')", 2000);
            
              }
              if (data.success == 'false') {
                $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" id="cruz_msj" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>El anticipo se encuentra liquidado, no puede elimiarse!</div>');       
                $("#cruz_msj").focus()
                setTimeout("$('#mensaje_general').alert('close')", 2000);
              }
            
            }

          });
   
        })
      })

$(function() {
        $('.form-control').change(function() {

          document.getElementById("botonExcel1").style.visibility = "hidden";
          
        })
      })


</script>

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
      <th>Parte</th>
      <th>Fecha</th>
      <th>Maquinaria</th>
      <th>Service</th>
      <th>Hora realizado</th>
      <th>Fuera de Rango</th>
      <th>Obs</th>
      <th>Ver</th>
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
        $maquinaria=$_REQUEST['dato_maquinaria'];
        
        $consulta_maquinaria = "";

        if ($maquinaria != "0") {
        $consulta_maquinaria = "AND tb_mantenimiento.id_maquinaria = '$maquinaria'";
        }



$sqlmaquinaria = "SELECT
tb_mantenimiento.id_global AS parte,
DATE_FORMAT(tb_mantenimiento.fecha, '%d/%m/%Y') AS fecha,
tb_maquinaria.nombre AS maquinaria,
GROUP_CONCAT(DISTINCT tb_tipo_mantenimiento.nombre) AS service,
tb_mantenimiento.hora_actual AS hora_realizado,
tb_mantenimiento.fuera_rango AS fuera_rango,
tb_mantenimiento.obs AS obs
FROM
tb_mantenimiento
LEFT JOIN tb_maquinaria ON tb_maquinaria.id_maquinaria = tb_mantenimiento.id_maquinaria
LEFT JOIN tb_tipo_mantenimiento ON tb_tipo_mantenimiento.id_tipo_mantenimiento = tb_mantenimiento.id_tipo_mantenimiento
WHERE
tb_mantenimiento.fecha BETWEEN '$desde' AND '$hasta' $consulta_maquinaria
GROUP BY
tb_mantenimiento.id_global
ORDER BY
parte ASC,
service ASC
";

$rsmaquinaria = mysqli_query($conexion, $sqlmaquinaria);
$cantidad =  mysqli_num_rows($rsmaquinaria);
if ($cantidad > 0) { // si existen maquinaria con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rsmaquinaria)){
$parte=utf8_encode($datos['parte']);
$fecha=utf8_encode($datos['fecha']);
$maquinaria=$datos['maquinaria'];
$service=utf8_encode($datos['service']);
$hora_realizado=utf8_encode($datos['hora_realizado']);
$fuera_rango=utf8_encode($datos['fuera_rango']);
$obs=utf8_encode($datos['obs']);

echo '
<tr>
<td>'.$parte.'</td>
<td>'.$fecha.'</td>
<td>'.$maquinaria.'</td>
<td>'.$service.'</td>
<td>'.$hora_realizado.'</td>
<td>'.$fuera_rango.'</td>
<td>'.$obs.'</td>
<td></td>
<td><button type="button" name="'.$parte.'" id="boton_e" class="boton_eliminar boton_eliminar-danger boton_eliminar-xs">
    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
    </button></td>    
</tr>
';
?>
<script type="text/javascript">
// document.getElementById("botonExcel1").style.visibility = "visible";
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


 $(document).ready(function () {
 $('#div_mensaje_general').html('')
  });


$(function() {
        $('.boton_eliminar-danger').click(function() {

        var id_global = $(this).attr('name')

        var pars = "id_global=" + id_global + "&"

        $("#div_mensaje_general").html('<div class="text-center"><div class="loadingsm"></div></div>');

        $.ajax({
            url : "clases/elimina/mantenimiento.php",
            data : pars,
            dataType : "json",
            type : "get",

            success: function(data){
                
              if (data.success == 'true') {

                $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" id="cruz_msj" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Se eliminó maquinaria!</div>');       
                $("#cruz_msj").focus()
                setTimeout("$('#mensaje_general').alert('close')", 1000);
                setTimeout("reporte('mantenimiento')", 2000);
            
              } else {
                $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" id="cruz_msj" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error reintente!</div>');       
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

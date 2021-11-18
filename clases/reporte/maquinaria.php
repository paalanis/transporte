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
      <th>Fecha</th>
      <th>Maquinaria</th>
      <th>Marca</th>
      <th>H-Anterior</th>
      <th>H-Actual</th>
      <th>Uso</th>
      <th>Daños</th>
      <th>Obs</th>
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
        $consulta_maquinaria = "AND tb_horas_maquinaria.id_maquinaria = '$maquinaria'";
        }



$sqlmaquinaria = "SELECT
DATE_FORMAT(tb_horas_maquinaria.fecha, '%d/%m/%Y') AS fecha,
tb_maquinaria.nombre AS maquinaria,
tb_marca.nombre AS marca,
tb_horas_maquinaria.hora_anterior AS anterior,
tb_horas_maquinaria.hora_actual AS actual,
tb_horas_maquinaria.hora_actual-tb_horas_maquinaria.hora_anterior AS uso,
IF(tb_horas_maquinaria.danio = '0', 'NO', 'SI') AS danio,
tb_horas_maquinaria.obs AS obs,
tb_horas_maquinaria.id_horas_maquinaria as id_hora
FROM
tb_horas_maquinaria
LEFT JOIN tb_maquinaria ON tb_maquinaria.id_maquinaria = tb_horas_maquinaria.id_maquinaria
LEFT JOIN tb_marca ON tb_marca.id_marca = tb_maquinaria.id_marca
WHERE
tb_horas_maquinaria.fecha BETWEEN '$desde' AND '$hasta' $consulta_maquinaria
ORDER BY
tb_horas_maquinaria.fecha DESC
";

$rsmaquinaria = mysqli_query($conexion, $sqlmaquinaria);
$cantidad =  mysqli_num_rows($rsmaquinaria);
if ($cantidad > 0) { // si existen maquinaria con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rsmaquinaria)){
$fecha=utf8_encode($datos['fecha']);
$maquinaria=$datos['maquinaria'];
$marca=utf8_encode($datos['marca']);
$anterior=utf8_encode($datos['anterior']);
$actual=utf8_encode($datos['actual']);
$uso=utf8_encode($datos['uso']);
$danio=utf8_encode($datos['danio']);
$obs=utf8_decode($datos['obs']);
$id_hora=utf8_encode($datos['id_hora']);

echo '
<tr>
<td>'.$fecha.'</td>
<td>'.$maquinaria.'</td>
<td>'.$marca.'</td>
<td>'.$anterior.'</td>
<td>'.$actual.'</td>
<td>'.$uso.'</td>
<td>'.$danio.'</td>
<td>'.$obs.'</td>
<td><button type="button" name="'.$id_hora.'" id="boton_e" class="boton_eliminar boton_eliminar-danger boton_eliminar-xs">
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

        var id = $(this).attr('name')

        var pars = "id=" + id + "&"

        $("#div_mensaje_general").html('<div class="text-center"><div class="loadingsm"></div></div>');
        //alert(pars)
        $.ajax({
            url : "clases/elimina/maquinaria.php",
            data : pars,
            dataType : "json",
            type : "get",

            success: function(data){
                
              if (data.success == 'true') {

                $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" id="cruz_msj" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Se eliminó maquinaria!</div>');       
                $("#cruz_msj").focus()
                setTimeout("$('#mensaje_general').alert('close')", 1000);
                setTimeout("reporte('maquinaria')", 2000);
            
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

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
      <th>Numero</th>
      <th>Fecha</th>
      <th>Apellido, Nombre</th>
      <th>Tipo</th>
      <th>Monto</th>
      <th>Eliminar</th>
      <th>Imprimir</th>  
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
tb_liquidacion.id_liquidacion AS id,
tb_liquidacion.numero AS numero,
DATE_FORMAT(tb_liquidacion.fecha, '%d/%m/%y') AS fecha,
CONCAT(tb_transportista.apellido,', ',tb_transportista.nombre) AS chofer,
tb_servicio.nombre AS tipo,
tb_liquidacion.monto_final AS monto_final
FROM
tb_liquidacion
INNER JOIN tb_transportista ON tb_transportista.id_transportista = tb_liquidacion.id_transportista
INNER JOIN tb_servicio ON tb_servicio.id_servicio = tb_liquidacion.id_servicio
WHERE
tb_liquidacion.fecha BETWEEN '$desde' AND '$hasta' $consulta_chofer
ORDER BY
numero DESC";

$rsanticipos = mysqli_query($conexion, $sqlanticipos);
$cantidad =  mysqli_num_rows($rsanticipos);
if ($cantidad > 0) { // si existen anticipos con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rsanticipos)){
$id=utf8_encode($datos['id']);
$numero=utf8_encode($datos['numero']);
$fecha=utf8_encode($datos['fecha']);
$chofer=utf8_encode($datos['chofer']);
$tipo=utf8_encode($datos['tipo']);
$monto_final=utf8_encode($datos['monto_final']);

echo '
<tr>
<td>'.$numero.'</td>
<td>'.$fecha.'</td>
<td>'.$chofer.'</td>
<td>'.$tipo.'</td>
<td>'.$monto_final.'</td>
<td><button type="button" name="'.$id.'" id="boton_e" class="boton_eliminar boton_eliminar-danger boton_eliminar-xs">
    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
    </button></td>
<td id="div_reimprimir'.$id.'"><button onclick="reimprime('.$id.')" class="ver_modal ver_modal-info ver_modal-xs" id="" value="" type="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button></td>    
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
<h3>Cantidad total de liquidaciones <span class="label label-default"><?php echo $cantidad?></span></h3>
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
            url : "clases/elimina/liquidacion.php",
            data : pars,
            dataType : "json",
            type : "get",

            success: function(data){
                
              if (data.success == 'true') {

                $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" id="cruz_msj" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Se eliminó liquidacion!</div>');       
                $("#cruz_msj").focus()
                setTimeout("$('#mensaje_general').alert('close')", 1000);
                $("#div_reporte").html('<div class="text-center"><div class="loadingsm"></div></div>');
                setTimeout("reporte('liquidacion')", 2000);
            
              }
              if (data.success == 'false') {
                $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" id="cruz_msj" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error reintente!</div>');       
                $("#cruz_msj").focus()
                setTimeout("$('#mensaje_general').alert('close')", 2000);
              }
            
            }

          });
   
        })
      })


function reimprime(id){

    var num = id.toString()

    $("#div_reimprimir"+num+"").html('<div class="text-center"><div class="loadingsm"></div></div>');
     location.href = 'http://localhost/gmo/clases/pdf/liquidacion.php/?dato='+num+''
    $("#div_reimprimir"+num+"").html('<button onclick="reimprime('+num+')" class="ver_modal ver_modal-info ver_modal-xs" id="" value="" type="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>');
}

$(function() {
        $('.form-control').change(function() {

          document.getElementById("botonExcel1").style.visibility = "hidden";
          
        })
      })


</script>

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

<!-- 
<table id="Exportar_a_Excel1"> -->

<table class="table table-striped table-hover">
<thead>
<tr>
<th data-campo='Fecha'>Fecha</th>
<th data-campo='Chofer'>Chofer</th>
<th data-campo='Remito'>Remito</th>
<th data-campo='Carga'>Carga</th>
<th data-campo='Servicio'>Servicio</th>
<th data-campo='Origen'>Origen</th>
<th data-campo='Destino'>Destino</th>
<th data-campo='Liquidado'>Liquidado</th>
<th data-campo='Ver'> <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></th>
<th data-campo='Modificar'> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></th>
<th data-campo='Eliminar'> <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
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
        $servicio=$_REQUEST['dato_servicior'];
        $transportista=$_REQUEST['dato_transportista'];
        $origen=$_REQUEST['dato_origen'];
        $destino=$_REQUEST['dato_destino'];
        
        $consulta_servicio = "";
        $consulta_transportista = "";
        $consulta_origen = ""; 
        $consulta_destino = "";

        if ($servicio != "0") {
        $consulta_servicio = "AND tb_viaje.id_servicio = '$servicio'";
        }
        if ($transportista != "0") {
        $consulta_transportista = "AND tb_viaje.id_transportista = '$transportista' ";
        }
        if ($origen != "0") {
        $consulta_origen = "AND tb_viaje.id_origen = '$origen' ";
        }
        if ($destino != "0") {
        $consulta_destino = "AND tb_viaje.id_destino = '$destino' ";
        }



$sqlviaje = "SELECT
tb_viaje.id_viaje AS id_viaje,
DATE_FORMAT(tb_viaje.fecha, '%d/%m/%Y') AS fecha,
CONCAT(tb_transportista.apellido,', ', tb_transportista.nombre) AS chofer,
tb_transportista.dni AS dni,
tb_viaje.remito AS remito,
tb_origen.nombre AS origen,
tb_destino.nombre AS destino,
tb_servicio.nombre AS servicio,
tb_tipo_carga.tipo_carga AS carga,
IF(tb_viaje.id_liquidacion > 0, CONCAT('SI - ',tb_liquidacion.numero),'NO') AS liquida,
tb_viaje.id_liquidacion as estado
FROM
tb_viaje
LEFT JOIN tb_origen ON tb_origen.id_origen = tb_viaje.id_origen
LEFT JOIN tb_transportista ON tb_transportista.id_transportista = tb_viaje.id_transportista
LEFT JOIN tb_servicio ON tb_servicio.id_servicio = tb_viaje.id_servicio
LEFT JOIN tb_destino ON tb_viaje.id_destino = tb_destino.id_destino
LEFT JOIN tb_liquidacion ON tb_liquidacion.id_liquidacion = tb_viaje.id_liquidacion
LEFT JOIN tb_tipo_carga ON tb_tipo_carga.id_tipo_carga = tb_viaje.id_tipo_carga
WHERE
tb_viaje.fecha BETWEEN '$desde' AND '$hasta' $consulta_servicio$consulta_transportista$consulta_origen$consulta_destino
ORDER BY
remito DESC,      
fecha DESC";

$rsviaje = mysqli_query($conexion, $sqlviaje);
$cantidad =  mysqli_num_rows($rsviaje);
if ($cantidad > 0) { // si existen viaje con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rsviaje)){
$fecha=utf8_encode($datos['fecha']);
$chofer=utf8_encode($datos['chofer']);
$carga=utf8_encode($datos['carga']);
$remito=utf8_encode($datos['remito']);
$origen=utf8_decode($datos['origen']);
$destino=utf8_decode($datos['destino']);
$servicio_=utf8_encode($datos['servicio']);  
$id_viaje=utf8_encode($datos['id_viaje']);
$liquida=utf8_encode($datos['liquida']);
$estado=utf8_encode($datos['estado']);

echo '
<tr>
<td data-campo="Fecha">'.$fecha.'</td>
<td data-campo="Chofer">'.$chofer.'</td>
<td data-campo="Remito">'.$remito.'</td>
<td data-campo="DNI">'.$carga.'</td>
<td data-campo="Servicio">'.$servicio_.'</td>
<td data-campo="Origen">'.$origen.'</td>
<td data-campo="Destino">'.$destino.'</td>
<td data-campo="Liquidado">'.$liquida.'</td>
<td data-campo="Ver"><button type="button" name="'.$id_viaje.'_ver" id="boton_m" class="ver_modal ver_modal-info ver_modal-xs">
    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
    </button></td>';
if ($estado == 0){echo '<td><button class="ver_modifica ver_modifica-info ver_modifica-xs" id="'.$id_viaje.'" value="'.$id_viaje.'" type="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></td>
<td data-campo="Eliminar"><button type="button" name="'.$id_viaje.'_ver" id="boton_e" class="boton_eliminar boton_eliminar-danger boton_eliminar-xs">
    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
    </button></td>    
</tr>';
}else{echo "";};
?>
<script type="text/javascript">
// document.getElementById("botonExcel1").style.visibility = "visible";
</script>
<?php
} 
}

?>

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
</tbody>
<h3>Cantidad total de viajes <span class="label label-default"><?php echo $cantidad?></span></h3>
</table> 

<br></br>

<script type="text/javascript">


 $(document).ready(function () {
 $('#div_mensaje_general').html('')
  });


$(function() {
        $('.boton_eliminar-danger').click(function() {

        var id = $(this).attr('name')

        var pars = "id=" + id + "&"

        $("#div_mensaje_general").html('<div class="text-center"><div class="loadingsm"></div></div>');

        $.ajax({
            url : "clases/elimina/viaje.php",
            data : pars,
            dataType : "json",
            type : "get",

            success: function(data){
                
              if (data.success == 'true') {
              
                $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" id="cruz_msj" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Se eliminó viaje!</div>');       
                $("#cruz_msj").focus()
                setTimeout("$('#mensaje_general').alert('close')", 1000);
                $("#div_reporte").html('<div class="text-center"><div class="loadingsm"></div></div>');
                setTimeout("reporte('viaje')", 2000);
            
              } 
              if (data.success == 'false') {

                $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" id="cruz_msj" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>El viaje se encuentra liquidado, no puede elimiarse!</div>');       
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

$(function() {
        $('.ver_modal-info').click(function() {

        var id = $(this).attr('name')
                 
         $("#div_reporte").html('<div class="text-center"><div class="loadingsm"></div></div>');
         $("#div_reporte").load("clases/reporte/viaje-modal.php", {id:id});
          
        })
      })

$(function() {

    $('.ver_modifica-info').click(function(){

      var id = $(this).val()
      var desde = $("#dato_desde").val()
      var hasta = $("#dato_hasta").val()
      var servicio_r = $("#dato_servicior").val()
      var transportista = $("#dato_transportista").val()

      $("#panel_inicio").html('<div class="text-center"><div class="loadingsm"></div></div>');
      $('#panel_inicio').load("clases/modifica/upd-viaje.php", {id:id, dato_desde:desde, dato_hasta:hasta, dato_servicior:servicio_r, dato_transportista:transportista});

    })
  })

</script>

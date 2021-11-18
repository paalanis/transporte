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
//include '../../conexion/conexion.php';
//ANTICIPOS
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}

        $desde=$_REQUEST['dato_desde'];
        $hasta=$_REQUEST['dato_hasta'];
        $transportista=$_REQUEST['dato_transportista'];
        
        $consulta_transportista = "";

        if ($transportista != "0") {
        $consulta_transportista = "AND tb_transportista.id_transportista = '$transportista'";
        }



$sqlanticipos = "SELECT
tb_anticipo.id_anticipo as id,
DATE_FORMAT(tb_anticipo.fecha, '%d/%m/%Y') AS fecha,
tb_tipo_anticipo.tipo_anticipo as tipo,
tb_anticipo.monto as monto,
tb_anticipo.obs as obs
FROM
tb_anticipo
LEFT JOIN tb_tipo_anticipo ON tb_anticipo.id_tipo_anticipo = tb_tipo_anticipo.id_tipo_anticipo
LEFT JOIN tb_transportista ON tb_anticipo.id_transportista = tb_transportista.id_transportista
WHERE
tb_anticipo.fecha BETWEEN '$desde' AND '$hasta' AND tb_anticipo.id_liquidacion = 0 $consulta_transportista
ORDER BY
tb_anticipo.fecha DESC";

$rsanticipos = mysqli_query($conexion, $sqlanticipos);
$cantidad_anticipos =  mysqli_num_rows($rsanticipos);

$contador = 0;

//VIAJES

include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}

        $desde=$_REQUEST['dato_desde'];
        $hasta=$_REQUEST['dato_hasta'];
        $servicio=$_REQUEST['dato_servicio'];
        $transportista=$_REQUEST['dato_transportista'];
        
        $consulta_servicio = "";
        $consulta_transportista = "";


        if ($servicio != "0") {
        $consulta_servicio = "AND tb_viaje.id_servicio = '$servicio'";
        }
        if ($transportista != "0") {
        $consulta_transportista = "AND tb_viaje.id_transportista = '$transportista' ";
        }



$sqlviaje = "SELECT
tb_viaje.id_viaje AS id_viaje,
DATE_FORMAT(tb_viaje.fecha, '%d/%m/%Y') AS fecha,
tb_viaje.remito AS remito,
tb_origen.nombre AS origen,
tb_destino.nombre AS destino,
tb_tipo_camion.tipo_camion as camion,
IFNULL(tb_lista_precios.precio,0) AS precio,
IF(tb_lista_precios.precio > 0,'checked', '') AS estado
FROM
tb_viaje
LEFT JOIN tb_lista_precios ON tb_lista_precios.id_origen = tb_viaje.id_origen AND tb_lista_precios.id_destino = tb_viaje.id_destino AND tb_lista_precios.id_tipo_camion = tb_viaje.id_tipo_camion
LEFT JOIN tb_origen ON tb_origen.id_origen = tb_viaje.id_origen
LEFT JOIN tb_destino ON tb_destino.id_destino = tb_viaje.id_destino
LEFT JOIN tb_servicio ON tb_servicio.id_servicio = tb_viaje.id_servicio
LEFT JOIN tb_tipo_camion ON tb_tipo_camion.id_tipo_camion = tb_viaje.id_tipo_camion
LEFT JOIN tb_transportista ON tb_viaje.id_transportista = tb_transportista.id_transportista
WHERE
tb_viaje.fecha BETWEEN '$desde' AND '$hasta' AND tb_viaje.id_liquidacion = 0 $consulta_servicio$consulta_transportista
ORDER BY
tb_viaje.fecha DESC
";

$rsviaje = mysqli_query($conexion, $sqlviaje);
$cantidad_viajes =  mysqli_num_rows($rsviaje);

?>

<div class="modal-footer">
<div class="col-lg-1"></div>
<div class="col-lg-5">
  <ul class="list-group">
  <li class="list-group-item list-group-item-default">Monto total de viajes</span></li>
  <li class="list-group-item list-group-item-danger"> - Comisión %</li>
  <li class="list-group-item list-group-item-default">Total neto a pagar</li>
  <li class="list-group-item list-group-item-default"> + IVA %</li>
  <li class="list-group-item list-group-item-default">Total facturado</li>
  <li class="list-group-item list-group-item-danger"> - Anticipos</span></li>
  <li class="list-group-item list-group-item-default">A pagar</li>
  <li class="list-group-item list-group-item-danger"> - Retención</li>
  <li class="list-group-item list-group-item-info">Total a pagar</li>
</ul>
</div>
<div class="col-lg-2">
<ul class="list-group">
  <li class="list-group-item list-group-item-default">-</li>
  <li class="list-group-item list-group-item-danger"><input style="height:20px" value="10" type="number" class="form-control" id="valor-comision" name='valor-comision' aria-describedby="basic-addon1" onchange="totales()"></li>
  <li class="list-group-item list-group-item-default">-</li>
  <li class="list-group-item list-group-item-default"><input style="height:20px" value="21" type="text" class="form-control" id="valor-iva" name='valor-iva' aria-describedby="basic-addon1" onchange="totales()"></li>
  <li class="list-group-item list-group-item-default">-</li>
  <li class="list-group-item list-group-item-danger">-</li>
  <li class="list-group-item list-group-item-default">-</li>
  <li class="list-group-item list-group-item-danger"><input style="height:20px" value="0.25" type="text" class="form-control" id="valor-retencion" name='valor-retencion' aria-describedby="basic-addon1" onchange="totales()"></li>
  <li class="list-group-item list-group-item-info">-</li>
</ul>
</div>
<div class="col-lg-2">
<ul class="list-group">
  <li class="list-group-item list-group-item-default"><input style="height:20px" type="text" class="form-control" id="dato_monto-viajes" name='monto-viajes' value='0' aria-describedby="basic-addon1" readonly></li>
  <li class="list-group-item list-group-item-danger"><input style="height:20px" type="text" class="form-control" id="dato_monto-comision" value='0' name='monto-comision' aria-describedby="basic-addon1" readonly></li>
  <li class="list-group-item list-group-item-default"><input style="height:20px" type="text" class="form-control" id="dato_monto-sub1" value='0' name='monto-sub1' aria-describedby="basic-addon1" readonly></li>
  <li class="list-group-item list-group-item-default"><input style="height:20px" type="text" class="form-control" id="dato_monto-iva" value='0' name='monto-iva' aria-describedby="basic-addon1" readonly></li>
  <li class="list-group-item list-group-item-default"><input style="height:20px" type="text" class="form-control" id="dato_monto-sub2" value='0' name='monto-sub2' aria-describedby="basic-addon1" readonly></li>
  <li class="list-group-item list-group-item-danger"><input style="height:20px" type="text" class="form-control" id="dato_monto-anticipo" value='0' name='monto-anticipo' aria-describedby="basic-addon1" readonly></li>
  <li class="list-group-item list-group-item-default"><input style="height:20px" type="text" class="form-control" id="dato_monto-sub3" value='0' name='monto-sub3' aria-describedby="basic-addon1" readonly></li>
  <li class="list-group-item list-group-item-danger"><input style="height:20px" type="text" class="form-control" id="dato_monto-retencion" value='0' name='monto-retencion' aria-describedby="basic-addon1" readonly></li>
  <li class="list-group-item list-group-item-info"><input style="height:20px" type="text" class="form-control" id="dato_monto-final" value='0' name='monto-final' aria-describedby="basic-addon1" readonly></li>
</ul>
</div>

<div class="col-lg-2" id="div_crear">
<button type="button" id="boton_crear" class="btn btn-primary" disabled onclick="liquidacion('liquidacion')"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar</button></div>
</div>

</div>

<div class="alert alert-warning" role="alert"><h4>Detalle de Anticipos <span class="label label-default"><?php echo $cantidad_anticipos?></span></h4></div>

<table class="table table-striped table-hover">
  <thead>
    <tr class="active">
      <th>Fecha</th>
      <th>Tipo anticipo</th>
      <th>Monto $</th>
      <th>Aprobado</th>
      <th>Monto Aprobado</th>
      </tr>
  </thead>
  <tbody>
<?php



if ($cantidad_anticipos> 0) { // si existen anticipos con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rsanticipos)){
$fecha=utf8_encode($datos['fecha']);
$tipo=utf8_encode($datos['tipo']);
$monto=utf8_encode($datos['monto']);
$obs=utf8_encode($datos['obs']);
$id_anticipo=utf8_encode($datos['id']);

$contador = $contador + 1;

echo '
<tr>
<td>'.$fecha.'</td>
<td>'.$tipo.'</td>
<td><input type="text" class="form-control input-sm" style="width:120px" id="dato_monto'.$contador.'" value="'.$monto.'" aria-describedby="basic-addon1" readonly></td>
<td data-campo="ok"><input type="checkbox" class="flat" id="dato_'.$contador.'" value="'.$id_anticipo.'" aria-describedby="basic-addon1"></td>    
<td><input type="text" class="form-control input-sm" style="width:120px" id="dato_aprobado'.$contador.'" value="0" name="anticipo" aria-describedby="basic-addon1" required disabled onchange="suma()"></td>
</tr>
';
} 
}

$contador_fin_anticipo = $contador;
$contador_inicio_viajes = $contador+1;
?>
</tbody>
</table> 

<?php
if ($cantidad_anticipos == 0){
echo "No hay registros<br></br>";
}
?>

    <div class="alert alert-warning" role="alert"><h4>Detalle de Viajes <span class="label label-default"><?php echo $cantidad_viajes?></span></h4></div>
  <!-- <h4 class="modal-title">Detalle de Viajes</h4> -->
 

<table class="table table-striped table-hover">
<thead>
<tr class="active">
<th data-campo='Fecha'>Fecha</th>
<th data-campo='Remito'>Remito</th>
<th data-campo='Origen'>Origen</th>
<th data-campo='Destino'>Destino</th>
<th data-campo='Tipo'>Tipo</th>
<th data-campo='Destino'>Precio Lista</th>
<th data-campo='Destino'>Aprobado</th>
<th data-campo='Destino'>Precio Aprobado</th>
</tr>
</thead>
<tbody>
<?php



if ($cantidad_viajes > 0) { // si existen viaje con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rsviaje)){
$fecha=utf8_encode($datos['fecha']);
$remito=utf8_encode($datos['remito']);
$origen=utf8_decode($datos['origen']);
$destino=utf8_decode($datos['destino']);
$camion=utf8_encode($datos['camion']);
$precio=utf8_encode($datos['precio']);
$id_viaje=utf8_encode($datos['id_viaje']);
$estado=utf8_encode($datos['estado']);

$contador = $contador + 1;

echo '
<tr>
<td data-campo="Fecha">'.$fecha.'</td>
<td data-campo="Remito">'.$remito.'</td>
<td data-campo="Origen">'.$origen.'</td>
<td data-campo="Destino">'.$destino.'</td>
<td data-campo="Destino">'.$camion.'</td>
<td data-campo="precio1"><input type="text" style="width:120px" class="form-control input-sm" id="dato_monto'.$contador.'" value="'.$precio.'" name="" aria-describedby="basic-addon1" readonly></td>
<td data-campo="ok"><input type="checkbox" class="flat"  id="dato_'.$contador.'" value="'.$id_viaje.'" aria-describedby="basic-addon1" '.$estado.'></td>
<td data-campo="precio2"><input type="number" style="width:120px" class="form-control input-sm" id="dato_aprobado'.$contador.'" value="0" name="viaje" aria-describedby="basic-addon1" required disabled onchange="suma()"></td>
</tr>
';
} 
$idinicial=1;
$idfinal=$contador;

echo'
<input type="hidden" class="form-control" id="dato_idinicial" value="'.$idinicial.'">
<input type="hidden" class="form-control" id="dato_finalanticipo" value="'.$contador_fin_anticipo.'">
<input type="hidden" class="form-control" id="dato_inicioviaje" value="'.$contador_inicio_viajes.'">
<input type="hidden" class="form-control" id="dato_idfinal" value="'.$idfinal.'">
'; 

}
?>
</tbody>
</table> 


<?php
if ($cantidad_viajes == 0){
echo "No hay registros<br></br>";
}
?>

<script type="text/javascript">


// iCheck
$(document).ready(function() {
    if ($("input.flat")[0]) {
        $(document).ready(function () {
            $('input.flat').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    }
});
// /iCheck

 $(document).ready(function () {
 $('#div_mensaje_general').html('')

  $('.flat:checked').each(
      function() {
          var numero = $(this).attr('id').split('_',2);
          $('#dato_aprobado'+numero[1]).attr('disabled', false);
          $('#dato_aprobado'+numero[1]).val($('#dato_monto'+numero[1]).val())

         // alert("El checkbox con valor " + numero + " está seleccionado");
      }
  );
  suma();

  });

function suma(){

    var idinicial = $('#dato_idinicial').val();
    var idfinal = $('#dato_idfinal').val();
    var sumaanticipo = 0;
    var sumaviaje = 0;

    for (var i = idinicial; i <= idfinal; i++) {
        
      var tipo = $('#dato_aprobado'+i).attr('name')

      if (tipo == 'anticipo') {
      
        var precio = $('#dato_aprobado'+i).val();
        var sumaanticipo = parseFloat(precio) + sumaanticipo;
        
        $('#dato_monto-anticipo').val(sumaanticipo);

       // alert(sumaanticipo)
          
      }

      if (tipo == 'viaje') {
      
        var precio = $('#dato_aprobado'+i).val();
        var sumaviaje = parseFloat(precio) + sumaviaje;
        
        $('#dato_monto-viajes').val(sumaviaje);
       // alert(suma)
          
      }
  }

  totales()
}


$('table input').on('ifChecked', function () {
    checkState = '';
    $(this).parent().parent().parent().addClass('selected');
    //var estado = $('#dato_cb'+numero).prop('checked')
    var numero = $(this).attr('id').split('_',2) 
    //alert(numero) 
    // var id = $(this).attr('name')
    // $(this).val(id)
    // var valor = $(this).val()
    $('#dato_aprobado'+numero[1]).attr('disabled', false);
    $('#dato_aprobado'+numero[1]).val($('#dato_monto'+numero[1]).val())
    //alert(numero) 

    suma()
    //totales()
});

$('table input').on('ifUnchecked', function () {
    checkState = '';
    $(this).parent().parent().parent().removeClass('selected');
    var numero = $(this).attr('id').split('_',2) 
    //alert(numero)
    // var id = $(this).attr('name')
    // $(this).val('0')
    // var valor = $(this).val() 
    $('#dato_aprobado'+numero[1]).attr('disabled', true);
    $('#dato_aprobado'+numero[1]).val('0')
   
    suma()
    //totales()
});


function totales() {
  
  $('#boton_crear').attr('disabled', true);

  var viajes = parseFloat($('#dato_monto-viajes').val())
   
  var retencion = $('#dato_monto-retencion').val()
  var totalfinal = $('#dato_monto-final').val()

  var porc_iva = parseFloat($('#valor-iva').val())
  var porc_com = parseFloat($('#valor-comision').val())
  var porc_ret = parseFloat($('#valor-retencion').val())

  //valor comision y sub1
    var calc_com = viajes*porc_com/100
  $('#dato_monto-comision').val(calc_com.toFixed(2))
  $('#dato_monto-sub1').val(viajes-calc_com)

  //valor iva y sub2
  var sub1 = parseFloat($('#dato_monto-sub1').val())
  var calc_iva = sub1*porc_iva/100
  $('#dato_monto-iva').val(calc_iva.toFixed(2))
  var iva = parseFloat($('#dato_monto-iva').val())
  $('#dato_monto-sub2').val(sub1+iva)

  //sub 3
  var sub2 = parseFloat($('#dato_monto-sub2').val())
  var anticipo = parseFloat($('#dato_monto-anticipo').val())
  var calc_sub3 = sub2-anticipo
  $('#dato_monto-sub3').val(calc_sub3.toFixed(2))

  //retencion y total final 
  var sub3 = parseFloat($('#dato_monto-sub3').val())
  calc_ret = sub3*porc_ret/100
  $('#dato_monto-retencion').val(calc_ret.toFixed(2))
  var retencion = parseFloat($('#dato_monto-retencion').val())
  var calc_final = sub3-retencion
  $('#dato_monto-final').val(calc_final.toFixed(2))

  $('#boton_crear').attr('disabled', false);


}

</script>

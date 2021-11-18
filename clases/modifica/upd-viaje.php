<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../../index.php");
}
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}

$id=$_REQUEST['id'];
$desde=$_REQUEST['dato_desde'];
$hasta=$_REQUEST['dato_hasta'];
$servicio=$_REQUEST['dato_servicior'];
$transportista=$_REQUEST['dato_transportista'];

$sqlmodifica = "SELECT
tb_viaje.id_viaje AS id_viaje,
DATE_FORMAT(tb_viaje.fecha, '%Y-%m-%d') AS fecha,
tb_viaje.id_origen AS id_origen,
tb_origen.nombre AS origen,
tb_viaje.id_destino AS id_destino,
tb_destino.nombre AS destino,
tb_viaje.id_transportista AS id_chofer,
CONCAT(tb_transportista.apellido, ', ', tb_transportista.nombre) AS chofer,
tb_viaje.id_tipo_remito AS id_tipo_rto,
tb_tipo_remito.tipo_remito AS tipo_rto,
tb_viaje.remito AS remito,
tb_viaje.id_servicio AS id_servicio,
tb_servicio.nombre AS servicio,
tb_viaje.id_tipo_camion AS id_tipo_camion,
tb_tipo_camion.tipo_camion AS tipo_camion,
tb_viaje.patente_chasis AS patente_cha,
tb_viaje.patente_equipo AS patente_equ,
tb_viaje.id_tipo_carga AS id_tipo_carga,
tb_tipo_carga.tipo_carga AS tipo_carga,
tb_viaje.cantidad AS cantidad,
IF(tb_viaje.descarga_manual = 1,'checked','') AS descarga,
tb_viaje.descarga_manual as estado_descarga,
tb_viaje.km AS km,
tb_viaje.obs AS obs
FROM
tb_viaje
LEFT JOIN tb_origen ON tb_origen.id_origen = tb_viaje.id_origen
LEFT JOIN tb_destino ON tb_destino.id_destino = tb_viaje.id_destino
LEFT JOIN tb_transportista ON tb_transportista.id_transportista = tb_viaje.id_transportista
LEFT JOIN tb_tipo_remito ON tb_tipo_remito.id_tipo_rermito = tb_viaje.id_tipo_remito
LEFT JOIN tb_servicio ON tb_servicio.id_servicio = tb_viaje.id_servicio
LEFT JOIN tb_tipo_camion ON tb_viaje.id_tipo_camion = tb_tipo_camion.id_tipo_camion
LEFT JOIN tb_tipo_carga ON tb_tipo_carga.id_tipo_carga = tb_viaje.id_tipo_carga
WHERE
tb_viaje.id_viaje = '$id'";

$rsmodifica = mysqli_query($conexion, $sqlmodifica);
$datos_modifica = mysqli_fetch_array($rsmodifica);

$sqltransportista = "SELECT
tb_transportista.id_transportista as id,
CONCAT(tb_transportista.apellido, ', ', tb_transportista.nombre) as transportista
FROM
tb_transportista";
$rstransportista = mysqli_query($conexion, $sqltransportista);
$sqlservicio = "SELECT
tb_servicio.id_servicio as id,
tb_servicio.nombre as servicio
FROM
tb_servicio";
$rsservicio = mysqli_query($conexion, $sqlservicio);
$sqlorigen = "SELECT
tb_origen.id_origen AS id,
tb_origen.nombre AS origen
FROM
tb_origen
ORDER BY
origen ASC";
$rsorigen = mysqli_query($conexion, $sqlorigen);
$sqldestino = "SELECT
tb_destino.id_destino AS id,
tb_destino.nombre AS destino
FROM
tb_destino
ORDER BY
destino ASC";
$rsdestino = mysqli_query($conexion, $sqldestino);
$sqltipocamion = "SELECT
tb_tipo_camion.id_tipo_camion AS id,
tb_tipo_camion.tipo_camion AS camion
FROM
tb_tipo_camion
ORDER BY
camion ASC
";
$rstipocamion = mysqli_query($conexion, $sqltipocamion);
$sqltipocarga = "SELECT
tb_tipo_carga.id_tipo_carga AS id,
tb_tipo_carga.tipo_carga AS carga
FROM
tb_tipo_carga
ORDER BY
carga ASC";
$rstipocarga = mysqli_query($conexion, $sqltipocarga);
$sqltiporemito = "SELECT
tb_tipo_remito.id_tipo_rermito AS id,
tb_tipo_remito.tipo_remito AS tipo_rto
FROM
tb_tipo_remito
ORDER BY
tipo_rto ASC";
$rstiporemito = mysqli_query($conexion, $sqltiporemito);
?>
<form class="form-horizontal" id="formulario_nuevo" role="form" onsubmit="event.preventDefault(); modifica('viaje')">

 <div class="row">
 <div class="col-lg-2"><h4><span class="label label-default">Modificar Viaje</span></h4><br></div> 
 <div class="col-lg-8">
  <div class="well bs-component">
   <fieldset>
    <input type="hidden" class="form-control" autocomplete="off" id="dato_id" value="<?php echo $id;?>" aria-describedby="basic-addon1">
    <input type="hidden" class="form-control" autocomplete="off" id="dato_desde" value="<?php echo $desde;?>" aria-describedby="basic-addon1">
    <input type="hidden" class="form-control" autocomplete="off" id="dato_hasta" value="<?php echo $hasta;?>" aria-describedby="basic-addon1">
    <input type="hidden" class="form-control" autocomplete="off" id="dato_servicior" value="<?php echo $servicio;?>" aria-describedby="basic-addon1">
    <input type="hidden" class="form-control" autocomplete="off" id="dato_transportista" value="<?php echo $transportista;?>" aria-describedby="basic-addon1">
     <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: right;">ORIGEN</label>
        <div class="col-lg-10">
          <div class="input-group">
            <select class="form-control" id="dato_origen" required>   
              <option value="<?php echo $datos_modifica['id_origen'];?>" ><?php echo utf8_decode($datos_modifica['origen']);?></option>
              <?php
              while ($datos = mysqli_fetch_array($rsorigen)){
              $origen=$datos['origen'];
              $id=$datos['id'];
              echo utf8_decode('<option value='.$id.'>'.$origen.'</option>');
              }
              ?>
            </select>
            <?php  if (strtolower($_SESSION['tipo_user']) == 'admin') {
            echo'
            <div class="input-group-btn">
              <button class="btn btn-default btn-sm" id="boton_origen" type="button" data-toggle="modal" data-whatever="origen" data-target="#modal_nuevo">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</button>
            </div>';
            }else{
            echo'
            <div class="input-group-btn">
              <button class="btn btn-default btn-sm" id="boton_origen" type="button">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Consulte por nuevos</button>
            </div>'; 
            };?>
          </div>
        </div>        
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: right;">DESTINO</label>
        <div class="col-lg-10">
          <div class="input-group">
            <select class="form-control" id="dato_destino" required>   
              <option value="<?php echo $datos_modifica['id_destino'];?>" ><?php echo utf8_decode($datos_modifica['destino']);?></option>
              <?php
              while ($datos = mysqli_fetch_array($rsdestino)){
              $destino=$datos['destino'];
              $id=$datos['id'];
              echo utf8_decode('<option value='.$id.'>'.$destino.'</option>');
              }
              ?>
            </select>
            <?php  if (strtolower($_SESSION['tipo_user']) == 'admin') {
            echo'
            <div class="input-group-btn">
              <button class="btn btn-default btn-sm" id="boton_destino" type="button" data-toggle="modal" data-whatever="destino" data-target="#modal_nuevo">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</button>
            </div>';
            }else{
            echo'
            <div class="input-group-btn">
              <button class="btn btn-default btn-sm" id="boton_origen" type="button">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Consulte por nuevos</button>
            </div>';
            };?>
          </div>
          </div>        
      </div>

      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label" style="text-align: right;">FECHA</label>
        <div class="col-lg-10">
          <input type="date" class="form-control" id="dato_fecha" value="<?php echo $datos_modifica['fecha'];?>" aria-describedby="basic-addon1"  autofocus="" required>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" style="text-align: right;" class="col-lg-2 control-label">CHOFER</label>
        <div class="col-lg-7">
          <select class="form-control" id="dato_chofer" required>
          <option value="<?php echo $datos_modifica['id_chofer'];?>"><?php echo utf8_encode($datos_modifica['chofer']);?></option>   
              <?php
              if (strtolower($_SESSION['tipo_user']) == 'admin') {
              while ($datos = mysqli_fetch_array($rstransportista)){
              $transportista=$datos['transportista'];
              $id=$datos['id'];
              echo utf8_encode('<option value='.$id.'>'.$transportista.'</option>');
              }
              }else{
              echo utf8_encode('<option value='.$_SESSION['id_chofer'].'>'.$_SESSION['chofer'].'</option>');
              }
              ?>
          </select>
        </div>
        <div class="col-lg-3">
          <input type="text" class="form-control" autocomplete="off" id="campo_dni" placeholder="DNI" value="" aria-describedby="basic-addon1" disabled>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label" style="text-align: right;">REMITO</label>
        <div class="col-lg-4">
          <input type="number" min="0" class="form-control" autocomplete="off" id="dato_remito" placeholder="Número" value="<?php echo $datos_modifica['remito'];?>" aria-describedby="basic-addon1" required>
        </div>
        <div class="col-lg-1" id="control_ciu_rto"></div>
        <label for="inputPassword" class="col-lg-2 control-label" style="text-align: right;">TIPO</label>
        <div class="col-lg-3">
          <select class="form-control" id="dato_remito-tipo" required>
          <option value='<?php echo $datos_modifica['id_tipo_rto'];?>'><?php echo $datos_modifica['tipo_rto'];?></option>  
              <?php
              while ($datos = mysqli_fetch_array($rstiporemito)){
              $tiporemito=utf8_encode($datos['tipo_rto']);
              $id=$datos['id'];
              echo utf8_encode('<option value='.$id.'>'.$tiporemito.'</option>');
              }
              ?>
            </select>
        </div>
      </div>

      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: right;">SERVICIO</label>
        <div class="col-lg-10">
          <select class="form-control" id="dato_servicio" required>   
              <option value="<?php echo $datos_modifica['id_servicio'];?>" ><?php echo $datos_modifica['servicio'];?></option>
              <?php
              while ($sql_servicio = mysqli_fetch_array($rsservicio)){
                $id= $sql_servicio['id'];
                $servicio = $sql_servicio['servicio'];
                echo utf8_encode('<option value='.$id.'>'.$servicio.'</option>');
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: right;">CAMIÓN</label>
        <div class="col-lg-4">
          <select class="form-control" id="dato_camion" required>   
              <option value="<?php echo $datos_modifica['id_tipo_camion'];?>" ><?php echo $datos_modifica['tipo_camion'];?></option>
              <?php
              while ($datos = mysqli_fetch_array($rstipocamion)){
              $tipocamion=utf8_encode($datos['camion']);
              $id=$datos['id'];
              echo utf8_encode('<option value='.$id.'>'.$tipocamion.'</option>');
              }
              ?>
            </select>
        </div>
        <div class="col-lg-3">
          <input type="text" class="form-control" autocomplete="off" id="dato_patente-1" placeholder="Patente chasis" value="<?php echo $datos_modifica['patente_cha'];?>" aria-describedby="basic-addon1" required disabled>
        </div>
        <div class="col-lg-3">
          <input type="text" class="form-control" autocomplete="off" id="dato_patente-2" placeholder="Patente equipo" value="<?php echo $datos_modifica['patente_equ'];?>" aria-describedby="basic-addon1" required disabled>
        </div>
      </div>  
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: right;">TIPO CARGA</label>
        <div class="col-lg-2">
          <select class="form-control" id="dato_carga-tipo" required>   
              <option value="<?php echo $datos_modifica['id_tipo_carga'];?>" ><?php echo $datos_modifica['tipo_carga'];?></option>
              <?php
              while ($datos = mysqli_fetch_array($rstipocarga)){
              $tipocarga=utf8_encode($datos['carga']);
              $id=$datos['id'];
              echo utf8_encode('<option value='.$id.'>'.$tipocarga.'</option>');
              }
              ?>
            </select>
        </div>
        <div class="col-lg-3">
          <input type="number" class="form-control" autocomplete="off" id="dato_cantidad" min="0" placeholder="Cantidad" value="<?php echo $datos_modifica['cantidad'];?>" aria-describedby="basic-addon1" required disabled>
        </div>
        <label  class="col-lg-3 control-label" style="text-align: right;">DESCARGA MANUAL</label>
        <div class="col-lg-2">
          <input type="checkbox" class="form-control" autocomplete="off" id="dato_manual" value="<?php echo $datos_modifica['estado_descarga'];?>" aria-describedby="basic-addon1" <?php echo $datos_modifica['descarga'];?> disabled>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: right;">KILÓMETROS</label>
        <div class="col-lg-4">
          <input type="number" class="form-control" min="1" autocomplete="off" id="dato_km" value="<?php echo $datos_modifica['km'];?>" aria-describedby="basic-addon1" required>
        </div>
        <label  class="col-lg-2 control-label" style="text-align: right;">OBSERVACIÓN</label>
        <div class="col-lg-4">
          <textarea class="form-control" autocomplete="off" rows="1" id="dato_obs"><?php echo $datos_modifica['obs'];?></textarea>
        </div>
      </div>
      
   </fieldset>

    </div>
 
 </div>
 <div class="col-lg-2"></div> 
 </div>  


 <div class="modal fade" id="modal_nuevo" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" id="cruz_modal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control" min="1" autocomplete="off" id="modal-nuevo" value="" aria-describedby="basic-addon1" autofocus>
      </div>
      <div class="modal-footer"><div class="text-center" id="cargando"></div>
        <button type="button" class="btn btn-default" id="salir_modal" data-dismiss="modal">Salir</button>
        <button type="button" class="btn btn-primary" id="guardar-nuevo" >Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

 <div class="modal-footer">
  <div class="col-lg-2"></div> 
        <div class="form-group form-group-sm">
        <div class="col-lg-4">
          <div align="center" id="div_mensaje_general">
          </div>
        </div>
        <div class="col-lg-4">
          <div align="right">
          <button type="button" id="boton_salir" onclick="inicio()" class="btn btn-default">Salir</button>
          <button type="submit" id="boton_guardar" class="btn btn-primary">Guardar</button>  
          </div>
        </div>
      </div>
  <div class="col-lg-2"></div>  
  </div>

</form>
<script type="text/javascript">

function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}

$(function() { 
        $('#dato_patente-1').blur(function() {
          var patente = $(this).val().toUpperCase()
          if (patente != ''){
             var cero = pad(patente,7)  
             $(this).val(cero)
          }
        })
      })

$(function() { 
        $('#dato_patente-2').blur(function() {
          var patente = $(this).val().toUpperCase()
          if (patente != ''){
             var cero = pad(patente,7)  
             $(this).val(cero)
          }
        })
      })

$(document).ready(function () {
  
  $('#dato_patente-1').mask("LLMMNMM", {'translation': {L: {pattern: /[A-Za-z]/}, N: {pattern: /[0-9]/}, M: {pattern: /[A-Za-z0-9]/}}});
  $('#dato_patente-2').mask("LLMMNMM", {'translation': {L: {pattern: /[A-Za-z]/}, N: {pattern: /[0-9]/}, M: {pattern: /[A-Za-z0-9]/}}});
  
  document.getElementById('dato_manual').disabled =false;
  });

$('#modal_nuevo').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('.modal-title').text('Nuevo ' + recipient)
  modal.find('.btn-primary').attr('name', recipient)
})

  $('#modal_nuevo').on('hidden.bs.modal', function (event) {
  $('#panel_inicio').load('clases/nuevo/viaje.php')
})

$(function() { 
        $('#guardar-nuevo').click(function() {

          var nuevo = $(this).attr('name')
          var dato = $('#modal-nuevo').val()

          if (dato != ''){
          var pars = "dato=" + dato + "&" + "nuevo=" + nuevo + "&";

         $("#cargando").html('<div class="text-center"><div class="loadingsm"></div></div>');

      $.ajax({
            url : "clases/guardar/origen-destino.php",
            data : pars,
            dataType : "json",
            type : "get",

            success: function(data){
                
              if (data.success == 'true') {
               $("#cargando").html('Se cargó el '+nuevo+': '+data.dato);
               $('#modal-nuevo').val('')
               setTimeout("$('#cargando').html('')", 2000);
              }else {
               $("#cargando").html('');
               $('#modal-nuevo').val('')
              }
            }
           });
          }
         })
       })
    
$(function() { 
        $('#dato_chofer').change(function() {
          var chofer = $(this).val()
          if (chofer != ''){
          var pars = "id=" + chofer + "&";
          // $("#control_ciu_rto").html('<div class="text-center"><div class="loadingsm"></div></div>');
      $.ajax({
            url : "clases/control/dni.php",
            data : pars,
            dataType : "json",
            type : "get",
            success: function(data){
              if (data.success == 'true') {
                $("#campo_dni").val(data.dni);
              }else {
                $("#campo_dni").val('Sin DNI');
              }
            }
           });
          }else{
            $("#campo_dni").val('');
          }
         })
       })
      

$(function() { 
        $('#dato_camion').change(function() {
          var carga = $(this).val()
          if (carga != ''){
            if (carga == "2") {
              document.getElementById('dato_patente-1').disabled =false;
              document.getElementById('dato_patente-2').disabled =false;
            }else{
              document.getElementById('dato_patente-1').disabled =false;
              document.getElementById('dato_patente-2').disabled =true;
              $('#dato_patente-2').val('')
            }
          }else{
            document.getElementById('dato_patente-1').disabled =true;
            document.getElementById('dato_patente-2').disabled =true;
            $('#dato_patente-1').val('')
            $('#dato_patente-2').val('')
          }
        })
      })

$(function() { 
        $('#dato_carga-tipo').change(function() {
          var carga = $(this).val()
          if (carga != ''){
            if (carga == "3") {
              document.getElementById('dato_cantidad').disabled =true;
              document.getElementById('dato_manual').disabled =false;
               $('#dato_cantidad').val('')
            }else{
              document.getElementById('dato_cantidad').disabled =false;
              document.getElementById('dato_manual').disabled =true;
              $('#dato_manual').prop('checked', false)
            }
          }else{
            document.getElementById('dato_cantidad').disabled =true;
            $('#dato_manual').prop('checked', false)
            document.getElementById('dato_manual').disabled =true;
            $('#dato_cantidad').val('')
          }
        })
      })

$(function() { 
        $('#dato_manual').change(function() {
          var carga = $(this).prop('checked')
          if (carga == true) {
            $(this).val('1')
          }else{
            $(this).val('0')
          }
        })
      })


  </script>



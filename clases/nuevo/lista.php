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
date_default_timezone_set("America/Argentina/Mendoza");
$hoy = date("Y-m-d");
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
// $sqltiporemito = "SELECT
// tb_tipo_remito.id_tipo_rermito AS id,
// tb_tipo_remito.tipo_remito AS tipo_rto
// FROM
// tb_tipo_remito
// ORDER BY
// tipo_rto ASC";
// $rstiporemito = mysqli_query($conexion, $sqltiporemito);
?>
<form class="form-horizontal" id="formulario_nuevo" role="form" onsubmit="event.preventDefault(); nuevo('lista')">

 <div class="modal-header">
   <h4 class="modal-title">Agregar  Lista de Precios</h4>
</div>
<br> 
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-5">
   <fieldset>
      
     <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: right;">ORIGEN</label>
        <div class="col-lg-10">
          <div class="input-group">
            <select class="form-control" id="dato_origen" required>   
              <option value="" ></option>
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
              <option value="" ></option>
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


    <!--   

      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: right;">SERVICIO</label>
        <div class="col-lg-10">
          <select class="form-control" id="dato_servicio" required>   
              <option value="" ></option>
              <?php
              while ($sql_servicio = mysqli_fetch_array($rsservicio)){
                $id= $sql_servicio['id'];
                $servicio = $sql_servicio['servicio'];
                echo utf8_encode('<option value='.$id.'>'.$servicio.'</option>');
              }
              ?>
            </select> 
        </div>
      </div> -->

       <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: right;">CAMIÓN</label>
        <div class="col-lg-10">
          <select class="form-control" id="dato_camion" required>   
              <option value="" ></option>
              <?php
              while ($datos = mysqli_fetch_array($rstipocamion)){
              $tipocamion=utf8_encode($datos['camion']);
              $id=$datos['id'];
              echo utf8_encode('<option value='.$id.'>'.$tipocamion.'</option>');
              }
              ?>
            </select>
        </div>       
      </div>
  
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: right;">PRECIO</label>
        <div class="col-lg-4">
          <input type="number" class="form-control" min="1" autocomplete="off" id="dato_precio" value="" aria-describedby="basic-addon1" required>
        </div>
        <label  class="col-lg-2 control-label" style="text-align: right;">OBSERVA</label>
        <div class="col-lg-4">
          <textarea class="form-control" autocomplete="off" rows="1" id="dato_obs"></textarea>
        </div>
      </div>
      
   </fieldset>

    </div>
    <div class="col-lg-7">
      
 <fieldset>

     <div class="panel panel-default">

      <div class="panel-body" id="Panel1" style="height:230px">
      <table class="table table-striped table-hover">
        <thead>
          <tr class="active">
            <th>Tipo</th>
            <th>Origen</th>
            <th>Destino</th>
            <th>Precio</th>
            <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
  
              $sqllista = "SELECT
              tb_origen.nombre AS origen,
              tb_destino.nombre AS destino,
              tb_tipo_camion.tipo_camion AS tipo,
              tb_lista_precios.precio AS precio,
              tb_lista_precios.id_lista_precios AS id
              FROM
              tb_lista_precios
              INNER JOIN tb_origen ON tb_origen.id_origen = tb_lista_precios.id_origen
              INNER JOIN tb_destino ON tb_destino.id_destino = tb_lista_precios.id_destino
              INNER JOIN tb_tipo_camion ON tb_tipo_camion.id_tipo_camion = tb_lista_precios.id_tipo_camion
              ORDER BY
              tb_lista_precios.id_origen ASC";

              $rslista = mysqli_query($conexion, $sqllista);
              
              $cantidad =  mysqli_num_rows($rslista);

              if ($cantidad > 0) { // si existen transportista con de esa transportista se muestran, de lo contrario queda en blanco  
             
              while ($datos = mysqli_fetch_array($rslista)){
              $id=utf8_encode($datos['id']);
              $origen=utf8_decode($datos['origen']);
              $destino=utf8_decode($datos['destino']);
              $tipo=utf8_decode($datos['tipo']);
              $precio=utf8_decode($datos['precio']);
                            
              echo '

              <tr>
                <td>'.$tipo.'</td>
                <td>'.$origen.'</td>
                <td>'.$destino.'</td>
                <td>'.$precio.'</td>
                <td data-campo="Eliminar"><button type="button" name="'.$id.'" id="boton_e" class="boton_eliminar boton_eliminar-danger boton_eliminar-xs">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </button></td>   
                </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay maquinarias cargadas.";
              }
      ?>
      </div>
      </div>      
      
         
   </fieldset>


    </div>  
 </div>
 
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
        <div class="form-group form-group-sm">
        <div class="col-lg-7">
          <div align="center" id="div_mensaje_general">
          </div>
        </div>
        <div class="col-lg-5">
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
  $('#panel_inicio').load('clases/nuevo/lista.php')
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
        $('.boton_eliminar-danger').click(function() {

        var id = $(this).attr('name')

        var pars = "id=" + id + "&"

        $("#div_mensaje_general").html('<div class="text-center"><div class="loadingsm"></div></div>');
        // alert(pars)
        $.ajax({
            url : "clases/elimina/lista.php",
            data : pars,
            dataType : "json",
            type : "get",

            success: function(data){
                
               if (data.success == 'true') {
                  $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Se eliminó lista!</div>');
                  setTimeout("$('#panel_inicio').load('clases/nuevo/lista.php')", 1050);
                  setTimeout("$('#mensaje_general').alert('close')", 2000);


                } else {
                  $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error reintente!</div>');        
                  setTimeout("$('#mensaje_general').alert('close')", 2000);
                }
            
            }

          });
   
        })
      })


  </script>



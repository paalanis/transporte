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
$sqltipoanticipo = "SELECT
tb_tipo_anticipo.id_tipo_anticipo AS id,
tb_tipo_anticipo.tipo_anticipo AS anticipo
FROM
tb_tipo_anticipo
ORDER BY
anticipo ASC";
$rstipoanticipo = mysqli_query($conexion, $sqltipoanticipo);
?>
<form class="form-horizontal" id="formulario_nuevo" role="form" onsubmit="event.preventDefault(); nuevo('anticipo')">

 <div class="row">
 <div class="col-lg-2"><h4><span class="label label-default">Nuevo Anticipo</span></h4><br></div> 
 <div class="col-lg-8">
  <div class="well bs-component">
   <fieldset>
      
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label" style="text-align: right;">FECHA</label>
        <div class="col-lg-10">
          <input type="date" class="form-control" id="dato_fecha" value="<?php echo $hoy;?>" aria-describedby="basic-addon1"  autofocus="" required>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" style="text-align: right;" class="col-lg-2 control-label">CHOFER</label>
        <div class="col-lg-7">
          <select class="form-control" id="dato_chofer" required>
              <?php
              if (strtolower($_SESSION['tipo_user']) == 'admin') {
              echo "<option value='0'></option>";
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
        <!-- <div class="col-lg-1" id="control_ciu_rto"></div> -->
        <label for="inputPassword" class="col-lg-2 control-label" style="text-align: right;">TIPO</label>
        <div class="col-lg-5">
          <select class="form-control" id="dato_anticipo" required> 
           <option value='0'></option>  
              <?php
              while ($datos = mysqli_fetch_array($rstipoanticipo)){
              $tipoanticipo=utf8_encode($datos['anticipo']);
              $id=$datos['id'];
              echo utf8_encode('<option value='.$id.'>'.$tipoanticipo.'</option>');
              }
              ?>
            </select>
        </div>
        <label for="inputPassword" class="col-lg-2 control-label" style="text-align: right;">MONTO</label>
        <div class="col-lg-3">
          <input type="number" min="0" class="form-control" autocomplete="off" id="dato_monto" placeholder="Número" value="" aria-describedby="basic-addon1" required>
        </div>
      </div>

     
     
     
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: right;">OBSERVACIÓN</label>
        <div class="col-lg-10">
          <textarea class="form-control" autocomplete="off" rows="1" id="dato_obs"></textarea>
        </div>
      </div>
      
   </fieldset>

    </div>
 
 </div>
 <div class="col-lg-2"></div> 
 </div>  


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
  
  </script>



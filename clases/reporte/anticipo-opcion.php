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
$sqltransportista = "SELECT
tb_transportista.id_transportista as id,
CONCAT(tb_transportista.apellido, ', ', tb_transportista.nombre) as transportista
FROM
tb_transportista";
$rstransportista = mysqli_query($conexion, $sqltransportista);
?>

<form class="form-horizontal" id="formulario_reporte" method="post" action="">
 
<div class="modal-header">
   <h4 class="modal-title">Reporte Anticipos</h4>
</div>
<br>

 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-3 control-label" >Fecha desde</label>
        <div class="col-lg-4">
          <input type="date" class="form-control" id="dato_desde" name='desde' aria-describedby="basic-addon1" required autofocus="">
        </div>
        <label for="inputPassword" class="col-lg-1 control-label">Hasta</label>
        <div class="col-lg-4">
          <input type="date" class="form-control" id="dato_hasta" name='hasta' aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>

     </fieldset>
 </div>

 <div class="col-lg-6">
    <fieldset>
    <div class="form-group form-group-sm">
        <label for="inputPassword" style="text-align: left;" class="col-lg-2 control-label">Chofer</label>
        <div class="col-lg-10">
          <select class="form-control" id="dato_chofer" name='chofer' required>   
              <option value="0" ></option>
              <?php
              while ($datos = mysqli_fetch_array($rstransportista)){
              $transportista=utf8_encode($datos['transportista']);
              $id=$datos['id'];
              echo '<option value='.$id.'>'.$transportista.'</option>';
              }
              ?>
          </select>
        </div>
    
   
   </fieldset>
  </div> 
</div>  
</div>

 <div
  class="modal-footer">
        <div class="form-group form-group-sm">
        <div class="col-lg-7">
          <div align="center" id="div_mensaje_general">
          </div>
        </div>
        <div class="col-lg-5">
          <div align="right">
          <button type="submit" class="btn btn-info" id="botonExcel1" aria-label="Left Align">
          <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Descargar</button>
          <button type="button" id="boton_salir" onclick="inicio()" class="btn btn-default">Salir</button> 
          <button type="button" id="boton_buscar" class="btn btn-primary" onclick="reporte('anticipo')">Buscar</button>   
          </div>
        </div>
      </div>  
  </div>

<div id="div_reporte"></div>

</form>

<script type="text/javascript">

  $(document).ready(function () {
      
   document.getElementById("botonExcel1").style.visibility = "hidden";
   $('mensaje_general').alert('close');

   })

</script>
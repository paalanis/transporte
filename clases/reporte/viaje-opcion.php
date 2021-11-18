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
$sqlservicio = "SELECT
              tb_servicio.id_servicio as id,
              tb_servicio.nombre as servicio
              FROM
              tb_servicio";
$rsservicio = mysqli_query($conexion, $sqlservicio);
$sqldestino = "SELECT
              tb_destino.id_destino as id,
              tb_destino.nombre as destino
              FROM
              tb_destino
              order by
              destino asc";
$rsdestino = mysqli_query($conexion, $sqldestino);
$sqlorigen = "SELECT
              tb_origen.id_origen as id,
              tb_origen.nombre as origen
              FROM
              tb_origen
              order by
              origen asc";
$rsorigen = mysqli_query($conexion, $sqlorigen);

?>

<form class="form-horizontal" id="formulario_reporte" method="post" action="class/transporte/reporte_excel.php">
 
<!-- <h4><span class="label label-default">Reporte de viajes</span></h4> -->

<div class="modal-header">
   <h4 class="modal-title">Reporte de viajes</h4>
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
      <div class="form-group form-group-sm">
        <label  class="col-lg-3 control-label">Origen</label>
        <div class="col-lg-9">
          <select class="form-control" id="dato_origen" name='origen'>   
              <option value="0" ></option>
              <?php
              while ($datos = mysqli_fetch_array($rsorigen)){
              $origen=utf8_encode($datos['origen']);
              $id=$datos['id'];
              echo utf8_encode('<option value='.$id.'>'.$origen.'</option>');
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-3 control-label">Tipo de servicio</label>
        <div class="col-lg-9">
          <select class="form-control" id="dato_servicior" name='servicio'>   
              <option value="0" ></option>
              <?php
              while ($datos = mysqli_fetch_array($rsservicio)){
              $servicio=utf8_encode($datos['servicio']);
              $id=$datos['id'];
              echo utf8_encode('<option value='.$id.'>'.$servicio.'</option>');
              }
              ?>
            </select>
        </div>
      </div>
     </fieldset>
 </div>

 <div class="col-lg-6">
    <fieldset>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Chofer</label>
        <div class="col-lg-10">
          <select class="form-control" id="dato_transportista" name='transportista'>   
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
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-3 control-label">Destino</label>
        <div class="col-lg-9">
          <select class="form-control" id="dato_destino" name='destino'>   
              <option value="0" ></option>
              <?php
              while ($datos = mysqli_fetch_array($rsdestino)){
              $destino=utf8_encode($datos['destino']);
              $id=$datos['id'];
              echo utf8_encode('<option value='.$id.'>'.$destino.'</option>');
              }
              ?>
            </select>
        </div>
      </div>       
   </fieldset>
  </div> 
</div>  
</div>

<div class="modal-footer">
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
          <button type="button" id="boton_buscar" class="btn btn-primary" onclick="reporte('viaje')">Buscar</button>  
          </div>
          
        </div>
      </div> 
</div>

<div id="div_reporte">
</div>
</form>

<script type="text/javascript">

  $(document).ready(function () {
      
   document.getElementById("botonExcel1").style.visibility = "hidden";
   $('mensaje_general').alert('close');

   })

  $('#myModal').on('hidden.bs.modal', function (e) {
alert('funcion')
//reporte('viaje')
})

</script>
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


?>

<form class="form-horizontal" id="formulario_reporte" method="post" action="">

<!-- <h4><span class="label label-default">Reporte de viajes</span></h4> -->

<div class="modal-header">
   <h4 class="modal-title">Liquidación a transportistas</h4>
</div>
<br>
<!-- <input type="text" value="liquidacion" class="form-control" id="liquidacion" aria-describedby="basic-addon1"><input type="text" value="17" class="form-control" id="id_liq" aria-describedby="basic-addon1">
<div class="col-lg-10"><div id="mensaje_general" class="alert alert-info alert-dismissible" role="alert">Se ha creado con éxito la liquidación N° <span class="badge">'+data.numero+'</span></div></div><div id="div_boton_imprimir" class="col-lg-2"><button type="button" id="boton_imprimir" class="btn btn-primary btn-lg" onclick="imprimepdf('liquidacion','10')" >Imprimir PDF <span class="glyphicon glyphicon-print" aria-hidden="true"></span></button></div>
 -->
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
        <label  class="col-lg-3 control-label">Tipo de servicio</label>
        <div class="col-lg-9">
          <select class="form-control" id="dato_servicio" name='servicio'>   
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
              while ($datos = mysqli_fetch_array($rstransportista)){
              $transportista=$datos['transportista'];
              $id=$datos['id'];
              echo utf8_encode('<option value='.$id.'>'.$transportista.'</option>');
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
          <button type="button" id="boton_salir" onclick="inicio()" class="btn btn-default">Salir</button> 
          <button type="button" id="boton_buscar" class="btn btn-primary" onclick="reporte('nuevo-liquidacion')">Borrador</button>
          </div>
          
        </div>
      </div> 
</div>

<div id="div_reporte">
</div>
</form>


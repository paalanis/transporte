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
$id=$_REQUEST['id'];
$sqlservicio = "SELECT
tb_tipo_mantenimiento.id_tipo_mantenimiento AS id_mantenimiento,
tb_tipo_mantenimiento.nombre AS nombre,
tb_tipo_mantenimiento.horas AS horas,
tb_tipo_mantenimiento.tolerancia AS tolerancia                             
FROM
tb_tipo_mantenimiento
WHERE
tb_tipo_mantenimiento.id_tipo_mantenimiento = '$id'
ORDER BY
nombre ASC";
$rsservicio = mysqli_query($conexion, $sqlservicio);
while ($datos = mysqli_fetch_array($rsservicio)){
$nombre=utf8_decode($datos['nombre']);
$horas=utf8_encode($datos['horas']);
$tolerancia=utf8_encode($datos['tolerancia']);
$id_servicio=utf8_encode($datos['id_mantenimiento']);
}
?>
<form class="form-horizontal" role="form" id="formulario_nuevo" onsubmit="event.preventDefault(); modifica('tipo-servicio')">

<div class="modal-header">
   <h4 class="modal-title">Modificar Tipo de Service</h4>
</div>
<br>

 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" value="<?php echo $nombre;?>" id="dato_nombre" aria-describedby="basic-addon1" required autofocus="">
          <input type="hidden" class="form-control" autocomplete="off" value="<?php echo $id_servicio;?>" id="dato_id" aria-describedby="basic-addon1">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Horas</label>
        <div class="col-lg-10">
          <input type="number" class="form-control" min="1" autocomplete="off" value="<?php echo $horas;?>" id="dato_horas" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>

      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Horas de Tolerancia</label>
        <div class="col-lg-10">
          <input type="number" class="form-control" min="0" autocomplete="off" value="<?php echo $tolerancia;?>" id="dato_tolerancia" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>      
      
   </fieldset>
 
 </div>
 <div class="col-lg-6">
 
   <fieldset>
      
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
          <button type="submit" id="boton_guardar" class="btn btn-primary">Guardar</button>  
          </div>
        </div>
      </div>  
  </div>

</form>

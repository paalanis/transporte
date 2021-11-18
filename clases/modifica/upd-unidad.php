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
$id_unidad=$_REQUEST['id'];
$sqlunidad = "SELECT
tb_unidad.nombre as nombre,
tb_unidad.id_unidad as id_unidad
FROM
tb_unidad
WHERE
tb_unidad.id_unidad = '$id_unidad'
ORDER BY
tb_unidad.nombre ASC
";
$rsunidad = mysqli_query($conexion, $sqlunidad);
while ($datos = mysqli_fetch_array($rsunidad)){
$nombre=utf8_encode($datos['nombre']);
$id_unidad=utf8_encode($datos['id_unidad']);
}
?>

<form class="form-horizontal" role="form" id="formulario_nuevo" onsubmit="event.preventDefault(); modifica('unidad')">

<div class="modal-header">
   <h4 class="modal-title">Modificar Unidades de Medida</h4>
</div>
<br>

 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="dato_nombre" value="<?php echo $nombre;?>" aria-describedby="basic-addon1" required autofocus="">
          <input type="hidden" class="form-control" autocomplete="off" id="dato_id" value="<?php echo $id_unidad;?>" aria-describedby="basic-addon1">
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

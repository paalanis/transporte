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
$id_transportista=$_REQUEST['id'];
$sqltransportista = "SELECT
tb_transportista.id_transportista AS id_transportista,
tb_transportista.nombre AS nombre,
tb_transportista.apellido AS apellido,
tb_transportista.dni AS dni,
tb_transportista.usuario AS usuario,
tb_transportista.pass AS pass
FROM
tb_transportista
WHERE
tb_transportista.id_transportista = '$id_transportista'
ORDER BY
apellido ASC";
$rstransportista = mysqli_query($conexion, $sqltransportista);
while ($datos = mysqli_fetch_array($rstransportista)){
$id_transportista=utf8_encode($datos['id_transportista']);
$nombre=utf8_encode($datos['nombre']);
$apellido=utf8_encode($datos['apellido']);
$dni=utf8_decode($datos['dni']);
$usuario=utf8_decode($datos['usuario']);
$pass=utf8_decode($datos['pass']);
}             
?>
<form class="form-horizontal" role="form" id="formulario_nuevo" onsubmit="event.preventDefault(); modifica('transportista')">
 <div class="modal-header">
   <h4 class="modal-title">Modificar Transportista</h4>
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
          <input type="hidden" class="form-control" autocomplete="off" id="dato_id" value="<?php echo $id_transportista;?>" aria-describedby="basic-addon1">
        </div>
      </div>
     
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Apellido</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" value="<?php echo $apellido;?>" autocomplete="off" id="dato_apellido" aria-describedby="basic-addon1" required >
        </div>
      </div>

      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">DNI</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" value="<?php echo $dni;?>" autocomplete="off" id="dato_dni" aria-describedby="basic-addon1" required >
        </div>
      </div>

      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Usuario</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" value="<?php echo $usuario;?>" autocomplete="off" id="dato_usuario" aria-describedby="basic-addon1" required >
        </div>
      </div>

      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Password</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" value="<?php echo $pass;?>" autocomplete="off" id="dato_pass" aria-describedby="basic-addon1" required >
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

<script type="text/javascript">
  
  $(document).ready(function () {
 
  $('#dato_dni').mask("00000000", {clearIfNotMatch: true});
  $('#dato_pass').mask("LL0000",{'translation': {L: {pattern: /[a-z]/}},clearIfNotMatch: true});

  });
  
</script>
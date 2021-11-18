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
$sqlmarca = "SELECT
tb_marca.id_marca as id_marca,
tb_marca.nombre as nombre
FROM tb_marca
order by
nombre asc";
$rsmarca = mysqli_query($conexion, $sqlmarca); 
$id_maquinaria=$_REQUEST['id'];
$sqlmaquinaria = "SELECT
tb_maquinaria.id_maquinaria AS id_maquinaria,
tb_maquinaria.nombre AS maquinaria,
tb_maquinaria.tipo AS tipo,
tb_marca.nombre AS marca2,
tb_maquinaria.id_marca as id_marca
FROM
tb_maquinaria
LEFT JOIN tb_marca ON tb_marca.id_marca = tb_maquinaria.id_marca
WHERE
tb_maquinaria.id_maquinaria = '$id_maquinaria'
ORDER BY
maquinaria ASC";
$rsmaquinaria = mysqli_query($conexion, $sqlmaquinaria);
while ($datos = mysqli_fetch_array($rsmaquinaria)){
$maquinaria=utf8_decode($datos['maquinaria']);
$id_maquinaria=utf8_encode($datos['id_maquinaria']);
$tipo=utf8_decode($datos['tipo']);
$marca2=utf8_decode($datos['marca2']);
$id_marca=utf8_decode($datos['id_marca']);
}             
?>
<form class="form-horizontal" role="form" id="formulario_nuevo" onsubmit="event.preventDefault(); modifica('maquinaria')">
 <div class="modal-header">
   <h4 class="modal-title">Modificar Maquinaria</h4>
</div>
<br> 
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="dato_nombre" value="<?php echo $maquinaria;?>" aria-describedby="basic-addon1" required autofocus="">
          <input type="hidden" class="form-control" autocomplete="off" id="dato_id" value="<?php echo $id_maquinaria;?>" aria-describedby="basic-addon1">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Marca</label>
        <div class="col-lg-10">
          <select class="form-control" id="dato_marca" required>   
              <option value="<?php echo $id_marca;?>"><?php echo $marca2;?></option>
              <?php
              while ($sql_marca = mysqli_fetch_array($rsmarca)){
                $idmarca= $sql_marca['id_marca'];
                $marca = $sql_marca['nombre'];

                echo utf8_decode('<option value='.$idmarca.'>'.$marca.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Tipo</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" value="<?php echo $tipo;?>" autocomplete="off" id="dato_tipo" aria-describedby="basic-addon1" required autofocus="">
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

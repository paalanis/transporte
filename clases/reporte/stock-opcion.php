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
$sqlinsumos = "SELECT
tb_insumo.id_insumo as id,
CONCAT(tb_insumo.nombre,' ',tb_marca.nombre) as insumo
FROM
tb_insumo
LEFT JOIN tb_marca ON tb_marca.id_marca = tb_insumo.id_marca
ORDER BY
tb_insumo.nombre ASC
";
$rsinsumos = mysqli_query($conexion, $sqlinsumos);
?>

<form class="form-horizontal" id="formulario_reporte" method="post" action="clases/reporte/stock-excel.php">
 
<div class="modal-header">
   <h4 class="modal-title">Reporte de Existencias</h4>
</div>
<br>

 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Lista de Insumos</label>
        <div class="col-lg-10">
          <select class="form-control" id="dato_insumo" name='dato_insumo'>   
              <option value="0" ></option>
              <?php
              while ($sql_insumos = mysqli_fetch_array($rsinsumos)){
                $idinsumos= $sql_insumos['id'];
                $insumos = $sql_insumos['insumo'];
                echo utf8_encode('<option value='.$idinsumos.'>'.$insumos.'</option>');
              }
              ?>
            </select>
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
          <button type="submit" class="btn btn-info" id="botonExcel1" aria-label="Left Align">
          <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Descargar</button>
          <button type="button" id="boton_salir" onclick="inicio()" class="btn btn-default">Salir</button> 
          <button type="button" id="boton_buscar" class="btn btn-primary" onclick="reporte('stock')">Buscar</button>   
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
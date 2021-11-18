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
$sqlunidad = "SELECT
tb_unidad.id_unidad as id_unidad,
tb_unidad.nombre as nombre
FROM tb_unidad
order by
nombre asc";
$rsunidad = mysqli_query($conexion, $sqlunidad);  
?>
<form class="form-horizontal" role="form" id="formulario_nuevo" onsubmit="event.preventDefault(); nuevo('insumo')">
 
 <div class="modal-header">
   <h4 class="modal-title">Agregar Insumo</h4>
</div>
<br>

 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-6">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="dato_nombre" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Unidad de medida</label>
        <div class="col-lg-10">
          <select class="form-control" id="dato_unidad" required>   
              <option value=""></option>
              <?php
              while ($sql_unidad = mysqli_fetch_array($rsunidad)){
                $idunidad= $sql_unidad['id_unidad'];
                $unidad = $sql_unidad['nombre'];

                echo utf8_encode('<option value='.$idunidad.'>'.$unidad.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Marca</label>
        <div class="col-lg-10">
          <select class="form-control" id="dato_marca" required>   
              <option value=""></option>
              <?php
              while ($sql_marca = mysqli_fetch_array($rsmarca)){
                $idmarca= $sql_marca['id_marca'];
                $marca = $sql_marca['nombre'];

                echo utf8_encode('<option value='.$idmarca.'>'.$marca.'</option>');
                
              }
              ?>
            </select>
        </div>
      </div>
      
   </fieldset>
 
 </div>
 <div class="col-lg-6">
 
   <fieldset>

     <div class="panel panel-default">

      <div class="panel-body" id="Panel1" style="height:230px">
      <table class="table table-striped table-hover">
        <thead>
          <tr class="active">
            <th>Insumo</th>
            <th>Unidad</th>
            <th>Marca</th>
            <th>Modificar</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
 
              $sqlinsumo = "SELECT
                            tb_insumo.id_insumo AS id_insumo,
                            tb_insumo.nombre AS insumo,
                            tb_unidad.nombre AS unidad,
                            tb_marca.nombre AS marca
                            FROM
                            tb_insumo
                            LEFT JOIN tb_marca ON tb_marca.id_marca = tb_insumo.id_marca
                            LEFT JOIN tb_unidad ON tb_insumo.id_unidad = tb_unidad.id_unidad
                            ORDER BY
                            insumo ASC";

              $rsinsumo = mysqli_query($conexion, $sqlinsumo);
              
              $cantidad =  mysqli_num_rows($rsinsumo);

              if ($cantidad > 0) { // si existen insumo con de esa insumo se muestran, de lo contrario queda en blanco  
             
              while ($datos = mysqli_fetch_array($rsinsumo)){
              $insumo=utf8_decode($datos['insumo']);
              $id_insumo=utf8_encode($datos['id_insumo']);
              $unidad=utf8_encode($datos['unidad']);
              $marca=utf8_decode($datos['marca']);
                            
              echo '

              <tr>
                <td>'.$insumo.'</td>
                <td>'.$unidad.'</td>
                <td>'.$marca.'</td>
                <td><button class="ver_modal ver_modal-info ver_modal-xs" id="'.$id_insumo.'" value="'.$id_insumo.'" type="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></td>
                </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay insumos cargados.";
              }
      ?>
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
          <button type="submit" id="boton_guardar" class="btn btn-primary">Guardar</button>  
          </div>
        </div>
      </div>  
  </div>

</form>

<script type="text/javascript">
  
  $(function() {

    $('.ver_modal').click(function(){

      var id = $(this).val()
    

      $("#panel_inicio").html('<div class="text-center"><div class="loadingsm"></div></div>');
      $('#panel_inicio').load("clases/modifica/upd-insumo.php", {id:id});

    })
  })

</script>
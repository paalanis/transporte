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
?>

<form class="form-horizontal" role="form" id="formulario_nuevo" onsubmit="event.preventDefault(); nuevo('unidad')">

<div class="modal-header">
   <h4 class="modal-title">Agregar Unidades de Medida</h4>
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

   </fieldset>
 </div>
 <div class="col-lg-6">
   <fieldset>

      <div class="panel panel-default">

      <div class="panel-body" id="Panel1" style="height:225px">
      <table class="table table-striped table-hover">
        <thead>
          <tr class="active">
            <th>Nombre</th>
            <th>Modificar</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
        
               $sqlunidad = "SELECT
                            tb_unidad.nombre as unidad,
                            tb_unidad.id_unidad as id_unidad
                            FROM
                            tb_unidad
                            ORDER BY
                            tb_unidad.nombre ASC
                            ";
              $rsunidad = mysqli_query($conexion, $sqlunidad);
              
              $cantidad =  mysqli_num_rows($rsunidad);

              if ($cantidad > 0) { // si existen unidad con de esa unidad se muestran, de lo contrario queda en blanco  
             
              while ($datos = mysqli_fetch_array($rsunidad)){
              $unidad=utf8_encode($datos['unidad']);
              $id_unidad=utf8_encode($datos['id_unidad']);
              echo '

              <tr>
                <td>'.$unidad.'</td>
                <td><button class="ver_modal ver_modal-info ver_modal-xs" id="'.$id_unidad.'" value="'.$id_unidad.'" type="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></td>
              </tr>
              ';
              if ($cantidad == 0){

                echo "No hay unidades cargadas.";
              }
          
              }   
              }
              ?>
        </tbody>
      </table> 
     
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
      $('#panel_inicio').load("clases/modifica/upd-unidad.php", {id:id});

    })
  })

</script>
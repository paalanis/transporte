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

<form class="form-horizontal" role="form" id="formulario_nuevo" onsubmit="event.preventDefault(); nuevo('marca')">

<div class="modal-header">
   <h4 class="modal-title">Agregar Marca</h4>
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
              
               $sqlmarca = "SELECT
                            tb_marca.nombre as marca,
                            tb_marca.id_marca as id_marca
                            FROM
                            tb_marca
                            ORDER BY
                            tb_marca.nombre ASC
                            ";
              $rsmarca = mysqli_query($conexion, $sqlmarca);
              
              $cantidad =  mysqli_num_rows($rsmarca);

              if ($cantidad > 0) { // si existen marca con de esa marca se muestran, de lo contrario queda en blanco  
             
              while ($datos = mysqli_fetch_array($rsmarca)){
              $marca=utf8_decode($datos['marca']);
              $id_marca=utf8_encode($datos['id_marca']);
              echo '

              <tr>
                <td>'.$marca.'</td>
                <td><button class="ver_modal ver_modal-info ver_modal-xs" id="'.$id_marca.'" value="'.$id_marca.'" type="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></td>
              </tr>
              ';
              
          
              }   
              }
              
              ?>
        </tbody>
      </table> 
      <?php
      if ($cantidad == 0){
        echo "No hay marcas cargadas.";
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
      $('#panel_inicio').load("clases/modifica/upd-marca.php", {id:id});

    })
  })

</script>
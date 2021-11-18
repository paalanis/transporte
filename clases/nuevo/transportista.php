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
<form class="form-horizontal" role="form" id="formulario_nuevo" onsubmit="event.preventDefault(); nuevo('transportista')">
 <div class="modal-header">
   <h4 class="modal-title">Agregar Transportista</h4>
</div>
<br> 
 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-5">
   <fieldset>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Nombre</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="dato_nombre" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Apellido</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" id="dato_apellido" aria-describedby="basic-addon1" required autofocus="">
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">DNI</label>
        <div class="col-lg-10">
          <div class="input-group">
            <input type="text" class="form-control" autocomplete="off" placeholder="Sin puntos ni comas" id="dato_dni" aria-describedby="basic-addon1" required>
         <div class="input-group-btn">
          <button class="btn btn-default btn-sm" id="crear_usr_pas" type="button" data-toggle="modal" data-whatever="origen" data-target="#modal_nuevo">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Crear usuario y password</button>
         </div>
         </div>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Usuario</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" placeholder="" id="dato_usuario" aria-describedby="basic-addon1" required>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label">Password</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" autocomplete="off" placeholder="" id="dato_pass" aria-describedby="basic-addon1" required >
        </div>
      </div>
   </fieldset>
 
 </div>
 <div class="col-lg-7">
 
   <fieldset>

     <div class="panel panel-default">

      <div class="panel-body" id="Panel1" style="height:230px">
      <table class="table table-striped table-hover">
        <thead>
          <tr class="active">
            <th>Apellido</th>
            <th>Nombre</th>
            <th>DNI</th>
            <th>Usuario</th>
            <th>Password</th>
            <th>Modificar</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
  
              $sqltransportista = "SELECT
              tb_transportista.id_transportista AS id_transportista,
              tb_transportista.nombre AS nombre,
              tb_transportista.apellido AS apellido,
              tb_transportista.dni AS dni,
              tb_transportista.usuario AS usuario,
              tb_transportista.pass AS pass
              FROM
              tb_transportista
              ORDER BY
              apellido ASC
              ";

              $rstransportista = mysqli_query($conexion, $sqltransportista);
              
              $cantidad =  mysqli_num_rows($rstransportista);

              if ($cantidad > 0) { // si existen transportista con de esa transportista se muestran, de lo contrario queda en blanco  
             
              while ($datos = mysqli_fetch_array($rstransportista)){
              $id_transportista=utf8_encode($datos['id_transportista']);
              $apellido=utf8_encode($datos['apellido']);
              $nombre=utf8_encode($datos['nombre']);
              $dni=utf8_decode($datos['dni']);
              $usuario=utf8_decode($datos['usuario']);
              $pass=utf8_decode($datos['pass']);
                            
              echo '

              <tr>
                <td>'.$apellido.'</td>
                <td>'.$nombre.'</td>
                <td>'.$dni.'</td>
                <td>'.$usuario.'</td>
                <td>'.$pass.'</td>
                <td><button class="ver_modal ver_modal-info ver_modal-xs" id="'.$id_transportista.'" value="'.$id_transportista.'" type="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></td>
                </tr>
              ';
          
              }   
              }
              ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay maquinarias cargadas.";
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
  
  $(document).ready(function () {
 
  $('#dato_dni').mask("00000000", {clearIfNotMatch: true});
  $('#dato_pass').mask("LL0000",{'translation': {L: {pattern: /[a-z]/}},clearIfNotMatch: true});

  });
  
  $(function() {

    $('.ver_modal').click(function(){

      var id = $(this).val()
    

      $("#panel_inicio").html('<div class="text-center"><div class="loadingsm"></div></div>');
      $('#panel_inicio').load("clases/modifica/upd-transportista.php", {id:id});

    })
  })

  $(function() {

    $('#crear_usr_pas').click(function(){

      var nombre = $('#dato_nombre').val()
      var apellido = $('#dato_apellido').val()
      var dni = $('#dato_dni').val()

      var usuario = $.trim(nombre).charAt(0) + $.trim(apellido)
      var pass = $.trim(nombre).charAt(0)+$.trim(apellido).charAt(0)+$.trim(dni).substr(0,4)
      
      $('#dato_usuario').val(usuario.toLowerCase())
      $('#dato_pass').val(pass.toLowerCase())

    })
  })

</script>
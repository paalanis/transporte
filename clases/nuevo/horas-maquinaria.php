<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../../index.php");
}
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
date_default_timezone_set("America/Argentina/Mendoza");
$hoy = date("Y-m-d");
$sqlmaquinaria = "SELECT
tb_maquinaria.id_maquinaria AS id,
tb_maquinaria.nombre AS nombre
FROM
tb_maquinaria
ORDER BY
nombre ASC";
$rsmaquinaria = mysqli_query($conexion, $sqlmaquinaria);
?>
<form class="form-horizontal" id="formulario_nuevo" role="form" onsubmit="event.preventDefault(); nuevo('horas-maquinaria')">
 
<!-- <div class="modal-header">
   <h4 class="modal-title"></h4>
</div>
<br> -->

 
 <div class="row">
 <div class="col-lg-2"><h4><span class="label label-default">Horas Maquinaria</span></h4><br></div> 
 <div class="col-lg-8">
 <div class="well bs-component">
   <fieldset>
      
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label" style="text-align: right;">Fecha</label>
        <div class="col-lg-10">
          <input type="date" class="form-control" id="dato_fecha" value="<?php echo $hoy;?>" aria-describedby="basic-addon1"  autofocus="" required>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" style="text-align: right;" class="col-lg-2 control-label">Maquinaria</label>
        <div class="col-lg-10">
          <select class="form-control" id="dato_maquinaria" required>   
              <option value="0" ></option>
              <?php
              while ($datos = mysqli_fetch_array($rsmaquinaria)){
              $nombre=$datos['nombre'];
              $id=$datos['id'];
              echo utf8_decode('<option value='.$id.'>'.$nombre.'</option>');
              }
              ?>
          </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label" style="text-align: right;">Hora Anterior</label>
        <div class="col-lg-10" id="div_hora_anterior">
          <input type="number" class="form-control" autocomplete="off" id="dato_hora-anterior" placeholder="" value="" aria-describedby="basic-addon1" required>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label" style="text-align: right;">Hora Actual</label>
        <div class="col-lg-10">
          <input type="number" class="form-control" autocomplete="off" id="dato_hora-actual" placeholder="" value="" aria-describedby="basic-addon1" required>
        </div>
      </div>

      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: right;">Daños/Otros</label>
        <div class="col-lg-10">
          <select class="form-control" id="dato_danio"  required>   
              <option value="" ></option>
              <option value="1" >Si</option>
              <option value="0" >No</option>
          </select>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label" style="text-align: right;">Observaciones</label>
        <div class="col-lg-10">
          <textarea class="form-control" autocomplete="off" rows="1" id="dato_obs"></textarea>
        </div>
      </div>
      
      
   </fieldset>
   </div>
 
 </div>
 <div class="col-lg-2"></div> 
 </div>  
 
  <div class="modal-footer">
  <div class="col-lg-2"></div> 
        <div class="form-group form-group-sm">
        <div class="col-lg-4">
          <div align="center" id="div_mensaje_general">
          </div>
        </div>
        <div class="col-lg-4">
          <div align="right">
          <button type="button" id="boton_salir" onclick="inicio()" class="btn btn-default">Salir</button>
          <button type="submit" id="boton_guardar" class="btn btn-primary">Guardar</button>  
          </div>
        </div>
      </div>
  <div class="col-lg-2"></div>  
  </div>
</form>

<script>

  $(function() {
        $('#dato_maquinaria').change(function() {

          var id = $(this).val()
          pars =  "id=" + id + "&"

          // alert(pars)
              
              $("#div_hora_anterior").html('<div class="text-center"><div class="loadingsm"></div></div>');

              $.ajax({
                  url : "clases/control/hora-anterior.php",
                  data : pars,
                  dataType : "json",
                  type : "get",

                  success: function(data){
                      
                    if (data.success == 'true') {

                      $('#div_hora_anterior').html('<input type="text" class="form-control" autocomplete="off" id="dato_hora-anterior" placeholder="" value="'+data.hora+'" aria-describedby="basic-addon1" required>');        
                      
                    } else {
                      
                      $('#div_hora_anterior').html('<input type="number" class="form-control" autocomplete="off" id="dato_hora-anterior" placeholder="" value="" aria-describedby="basic-addon1" required>');
                    }
                  
                  }

              });
              
        })
      })


</script>

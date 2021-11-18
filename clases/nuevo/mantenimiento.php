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
$sqlinsumos = "SELECT
tb_insumo.id_insumo as id,
CONCAT(tb_insumo.nombre,' ',tb_marca.nombre,' - ',tb_unidad.nombre) as insumo
FROM
tb_insumo
LEFT JOIN tb_marca ON tb_marca.id_marca = tb_insumo.id_marca
LEFT JOIN tb_unidad ON tb_unidad.id_unidad = tb_insumo.id_unidad
ORDER BY
tb_insumo.nombre ASC
";
$rsinsumos = mysqli_query($conexion, $sqlinsumos);
?>
<form class="form-horizontal" id="formulario_nuevo" role="form" onsubmit="event.preventDefault(); nuevo('mantenimiento')">
<!-- 
<div class="modal-header">
   <h4 class="modal-title"></h4>
</div>
<br> -->


 <div class="row">
 <div class="col-lg-3"><h4><span class="label label-default">Mantenimiento Maquinaria</span></h4><br></div> 
 <div class="col-lg-7">
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

      <div class="panel panel-default">

    <div class="panel-body" id="Panel1" style="height:220px">
    <table class="table table-striped table-hover">
      <thead>
        <tr class="active">
          <th>#</th>
          <th>Tipo de service</th>
          <th>Horas</th>
          </tr>
      </thead>
      <tbody>
   
        <?php

        $sqlmantenimiento = "SELECT
        tb_tipo_mantenimiento.id_tipo_mantenimiento as id,
        tb_tipo_mantenimiento.nombre as mantenimiento,
        tb_tipo_mantenimiento.horas as horas
        FROM
        tb_tipo_mantenimiento
        ORDER BY
        tb_tipo_mantenimiento.nombre ASC";
        $rsmantenimiento = mysqli_query($conexion, $sqlmantenimiento);
        
        $cantidad =  mysqli_num_rows($rsmantenimiento);

        if ($cantidad > 0) { 

           $contador = 0;
  
        while ($datos = mysqli_fetch_array($rsmantenimiento)){
        $id=utf8_encode($datos['id']);
        $mantenimiento=utf8_encode($datos['mantenimiento']);
        $horas=$datos['horas'];

        $contador = $contador + 1;
   
        echo '
        <tr>
          <th><input type="checkbox" value="" name="'.$id.'" style="width: 20px; height:15px" class="cbxservice" id=dato_service-'.$contador.'></th>
          <td>'.$mantenimiento.'</td>
          <td>'.$horas.'</td>
          </td>
        </tr>
        ';    
        } 

        $idinicial=1;
        $idfinal=$contador;  

        echo '<input type="hidden" class="form-control" id="dato_id-inicial" value="'.$idinicial.'">
        <input type="hidden" class="form-control" id="dato_id-final" value="'.$idfinal.'">
        '; 
        
        }
        ?>
        </tbody>
      </table> 
      <?php
       if ($cantidad == 0){

                echo "No hay Tipo de Service cargados.";
              }
      ?>
      </div>
      </div>
      
      <div class="form-group form-group-sm">
        <label for="inputPassword" class="col-lg-2 control-label" style="text-align: right;">CuentaHoras</label>
        <div class="col-lg-10" id="div_hora_anterior">
          <input type="number" class="form-control" autocomplete="off" id="dato_hora-anterior" placeholder="" value="" aria-describedby="basic-addon1" required>
        </div>
      </div>

     <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Insumos</label>
        <div class="col-lg-6">
          <select class="form-control" id="dato_insumo" onchange="saldo()">   
              <option value=""></option>
              <?php
              while ($sql_insumos = mysqli_fetch_array($rsinsumos)){
                $idinsumos= $sql_insumos['id'];
                $insumos = $sql_insumos['insumo'];
                echo utf8_encode('<option value='.$idinsumos.'>'.$insumos.'</option>');
              }
              ?>
            </select>
            <div id="div_saldo"></div>
        </div>
        <div class="col-lg-4">
          <div class="input-group input-group-sm">
            <input class="form-control" id="dato_insumo-cantidad" autocomplete="off" type="text">
            <span class="input-group-btn">
              <button class="btn btn-default" id='boton_insumo' type="button" onclick="carga_insumo()">Ok</button>
            </span>
          </div>
        </div>
      </div>
      
      <div id="div_insumos_cargados"></div>
      
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

                      $('#div_hora_anterior').html('<input type="text" class="form-control" autocomplete="off" id="dato_hora-anterior" placeholder="" value="'+data.hora+'" aria-describedby="basic-addon1" required readonly>');        
                      
                    } else {
                      
                      $('#div_hora_anterior').html('<input type="number" class="form-control" autocomplete="off" id="dato_hora-anterior" placeholder="Colocar valor del cuenta horas al momento del service" value="" aria-describedby="basic-addon1" required>');
                    }
                  
                  }

              });
              
        })
      })


 $(function() {
        $('.cbxservice').click(function() {

          var estado = $(this).prop('checked') 
          var id = $(this).attr('name')

          if (estado == true) {

            $(this).val(id)
        
          }else{
            $(this).val('0')
          }

          var valor = $(this).val()
              
        })
      })



</script>

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
date_default_timezone_set("America/Argentina/Mendoza");
$hoy = date("Y-m-d");
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
<form class="form-horizontal" id="formulario_nuevo" role="form" onsubmit="event.preventDefault(); nuevo('remito')">
 

<div class="modal-header">
   <h4 class="modal-title">Remitos de Insumos</h4>
</div>
<br>

 <div class="well bs-component">
 <div class="row">
 <div class="col-lg-5">
   <fieldset>
      <div class="form-group form-group-sm">
        <label class="col-lg-2 control-label">Fecha</label>
        <div class="col-lg-10">
          <input type="date" class="form-control" id="dato_fecha" value="<?php echo $hoy;?>" aria-describedby="basic-addon1" required>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label  class="col-lg-2 control-label">Insumos</label>
        <div class="col-lg-6">
          <select class="form-control" id="dato_insumo" required>   
              <option value=""></option>
              <?php
              while ($sql_insumos = mysqli_fetch_array($rsinsumos)){
                $idinsumos= $sql_insumos['id'];
                $insumos = $sql_insumos['insumo'];
                echo utf8_encode('<option value='.$idinsumos.'>'.$insumos.'</option>');
              }
              ?>
            </select>
        </div>
        <div class="col-lg-4">
          <div class="input-group input-group-sm">
            <input class="form-control" autocomplete="off" placeholder="cantidad" id="dato_cantidad" type="text" required>
          </div>
        </div>
      </div>
      <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-2 control-label">Observación</label>
        <div class="col-lg-10">
          <textarea class="form-control" autocomplete="off" rows="1" id="dato_obs"></textarea>
          <span class="help-block">En caso de ser necesario detalle el remito.</span>
        </div>
      </div>
         
   </fieldset>
 
 </div>
 <div class="col-lg-7">
 
   <fieldset id="div_remitos">
    
      <div class="panel panel-default">

      <div class="panel-body" id="Panel1" style="height:216px">
      <table class="table table-striped table-hover">
        <thead>
          <tr class="active">
            <th>Fecha</th>
            <th>Insumo</th>
            <th>Marca</th>
            <th>U. Medida</th>
            <th>Cantidad</th>
            <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
         
              <?php
 
              $sqlingreso = "SELECT
      					tb_stock.id_stock as id,
      					DATE_FORMAT(tb_stock.fecha, '%d/%m/%y') as fecha,
      					tb_insumo.nombre as insumo,
                tb_stock.id_insumo as id_insumo,
      					tb_unidad.nombre as unidad,
      					Round(tb_stock.ingreso, 2) as cantidad,
                tb_marca.nombre as marca
      					FROM
      					tb_stock
      					LEFT JOIN tb_insumo ON tb_stock.id_insumo = tb_insumo.id_insumo
                LEFT JOIN tb_marca ON tb_marca.id_marca = tb_insumo.id_marca
      					LEFT JOIN tb_unidad ON tb_insumo.id_unidad = tb_unidad.id_unidad
      					WHERE
      					tb_stock.ingreso > '0'
      					ORDER BY
      					id DESC
      					LIMIT 5";
              $rsingreso = mysqli_query($conexion, $sqlingreso);
              
              $cantidad =  mysqli_num_rows($rsingreso);

              if ($cantidad > 0) { // si existen ingreso con de esa ingreso se muestran, de lo contrario queda en blanco  
                
              while ($datos = mysqli_fetch_array($rsingreso)){
              $fecha=utf8_encode($datos['fecha']);
              $insumo=utf8_encode($datos['insumo']);
              $id_insumo=utf8_encode($datos['id_insumo']);
              $unidad=utf8_encode($datos['unidad']);
              $cantidad=utf8_encode($datos['cantidad']);
              $id=utf8_encode($datos['id']);
              $marca=utf8_encode($datos['marca']);
              
              echo '

              <tr>
                <td>'.$fecha.'</td>
                <td>'.$insumo.'</td>
                <td>'.$marca.'</td>
                <td>'.$unidad.'</td>
                <td>'.$cantidad.'</td>
                <td><button type="button" class="ver_modal ver_modal-danger ver_modal-xs" id="'.$id.'" name="'.$id_insumo.'" value="'.$id.'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>
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
  $(document).ready(function () {
  
  $('#ingreso_cantidad').mask("##.00", {reverse: true});
 
  });

  $(function() {
        $('.ver_modal-danger').click(function() {

           var id_remito = $(this).val()
           var id_insumo = $(this).attr('name')
                     
           var pars = "id=" + id_remito + "&" + "id_insumo=" + id_insumo + "&";

          $('#div_remitos').html('<div class="text-center"><div class="loadingsm"></div></div>');
          $.ajax({
              url : "clases/elimina/remito.php",
              data : pars,
              dataType : "json",
              type : "get",

              success: function(data){
                  
                if (data.success == 'true') {
                  $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Se eliminó remito!</div>');
                  setTimeout("$('#panel_inicio').load('clases/nuevo/remito.php')", 1050);
                  setTimeout("$('#mensaje_general').alert('close')", 2000);


                } else {
                  $('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error reintente!</div>');        
                  setTimeout("$('#mensaje_general').alert('close')", 2000);
                }
              
              }

          });

              
        })
      })

 </script>
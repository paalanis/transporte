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
$id=$_POST['id'];

$sqlviaje = "SELECT
        DATE_FORMAT(tb_viaje.fecha, '%d/%m/%Y') AS fecha,
        CONCAT(tb_transportista.apellido,', ', tb_transportista.nombre) AS chofer,
        tb_transportista.dni AS dni,
        tb_origen.nombre AS origen,
        tb_destino.nombre AS destino,
        tb_viaje.remito AS remito,
        tb_servicio.nombre AS servicio,
        tb_tipo_camion.tipo_camion AS tipo_camion,
        tb_viaje.patente_chasis AS pat_c,
        tb_viaje.patente_equipo AS pat_e,
        tb_tipo_carga.tipo_carga AS carga,
        tb_viaje.cantidad AS cantidad,
        IF(tb_viaje.descarga_manual = '0', 'NO', 'SI') AS descarga,
        tb_viaje.km AS km,
        tb_viaje.obs AS obs,
        tb_viaje.id_global AS parte
        FROM
        tb_viaje
        LEFT JOIN tb_origen ON tb_origen.id_origen = tb_viaje.id_origen
        LEFT JOIN tb_transportista ON tb_transportista.id_transportista = tb_viaje.id_transportista
        LEFT JOIN tb_servicio ON tb_servicio.id_servicio = tb_viaje.id_servicio
        LEFT JOIN tb_tipo_camion ON tb_tipo_camion.id_tipo_camion = tb_viaje.id_tipo_camion
        LEFT JOIN tb_tipo_carga ON tb_tipo_carga.id_tipo_carga = tb_viaje.id_tipo_carga
        LEFT JOIN tb_destino ON tb_viaje.id_destino = tb_destino.id_destino
        WHERE
        tb_viaje.id_viaje = '$id'";

$rsviaje = mysqli_query($conexion, $sqlviaje);
$cantidad =  mysqli_num_rows($rsviaje);
if ($cantidad > 0) { // si existen viaje con de esa finca se muestran, de lo contrario queda en blanco  
while ($datos = mysqli_fetch_array($rsviaje)){
$fecha=utf8_encode($datos['fecha']);
$chofer=utf8_encode($datos['chofer']);
$dni=utf8_encode($datos['dni']);
$origen=utf8_decode($datos['origen']);
$destino=utf8_decode($datos['destino']);
$remito=utf8_encode($datos['remito']);
$servicio=utf8_encode($datos['servicio']);
$tipo_camion=utf8_encode($datos['tipo_camion']);
$pat_c=utf8_encode($datos['pat_c']);
$pat_e=utf8_encode($datos['pat_e']);
$carga=utf8_encode($datos['carga']);
$cantidad=utf8_encode($datos['cantidad']);
$descarga=utf8_encode($datos['descarga']);
$km=utf8_encode($datos['km']);
$obs=utf8_encode($datos['obs']);
$parte=utf8_encode($datos['parte']);
} 
}

?>
<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h4 class="modal-title" id="myModalLabel">Reporte viaje n° <?php echo $parte; ?></h4>
        <!--  <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button> -->
      </div>
      <div class="modal-body">

       <!-- <div class="panel-body" id="Panel1"> -->

       <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Fecha</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_fecha" value="<?php echo $fecha;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Chofer</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_chofer" autocomplete="off" value="<?php echo $chofer;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">DNI</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_dni" autocomplete="off" value="<?php echo $dni;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Origen</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_origen" autocomplete="off" value="<?php echo $origen;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Destino</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_destino" autocomplete="off" value="<?php echo $destino;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

       <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Remito N°</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_remito" autocomplete="off" value="<?php echo $remito;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>       

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Servicio</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_servicio" autocomplete="off" value="<?php echo $servicio;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Tipo de camión</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_tipo_camion" autocomplete="off" value="<?php echo $tipo_camion;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4  control-label">Patente chasis</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_pat_c" autocomplete="off" value="<?php echo $pat_c;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4  control-label">Patente equipo</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_pat_e" autocomplete="off" value="<?php echo $pat_e;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4  control-label">Carga</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_carga" autocomplete="off" value="<?php echo $carga;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4  control-label">Cantidad</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_cantidad" autocomplete="off" value="<?php echo $cantidad;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4  control-label">Tipo descarga</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_descarga" autocomplete="off" value="<?php echo $descarga;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Kilómetros</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_km" autocomplete="off" value="<?php echo $km;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>

        <div class="form-group form-group-sm">
        <label for="textArea" class="col-lg-4 control-label">Observación</label>
        <div class="col-lg-8">
          <input type="text" class="form-control" id="modal_obs" autocomplete="off" value="<?php echo $obs;?>" aria-describedby="basic-addon1" disabled>
          </div>
        </div>
       
   <!--    </div> -->

      </div>
      <div class="modal-footer" style="padding:5px">
        <button type="button" id="boton_cerrar" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$('#myModal').modal('show')
$('#myModal').on('hidden.bs.modal', function (e) {
reporte('viaje')
})
</script>

<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: index.php");
}
include 'conexion/conexion.php';
if (mysqli_connect_errno()) {
printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="Pablo Alanis" content="">
    <link rel="shortcut icon" href="images/gmo-sidebar.png" type="image/x-icon">


    <title>GMO Logística</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="css/bootswatch.scss" type="text/css" rel="stylesheet">
    <link href="css/bootswatch.less" type="text/css" rel="stylesheet">
    <link href="css/cargando.css" rel="stylesheet">
    <link href="css/formato.css" rel="stylesheet">
    <link href="css/tablas.css" rel="stylesheet">
    <link href="css/green.css" rel="stylesheet">

  </head>

  <body>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><img style="max-width:30px; margin-top: -14px;  margin-right: 0px;"
             src="images/logo.png"></a>
         <!--  <a class="navbar-brand" href="#">Desarrollos</a> -->

        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="/gmo">Inicio</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-e xpanded="false">Transporte <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#" class="menu" title="nuevo_viaje" onmouseup="cerrar()">Nuevo viaje</a></li>
                <li><a href="#" class="menu" title="reporte_viaje-opcion" onmouseup="cerrar()">Reporte de viajes</a></li>
           <?php  if (strtolower($_SESSION['tipo_user']) == 'admin') {
            echo '  
                <li role="separator" class="divider"></li>
                <li><a href="#" class="menu" title="nuevo_lista" onmouseup="cerrar()">Lista de precios</a></li>                               
                <li class="disabled"><a href="#" class="menu" title="construccion_construccion" onmouseup="cerrar()" >Liquidacíon a clientes</a></li>
                <li class="disabled"><a href="#" class="menu" title="construccion_construccion" onmouseup="cerrar()">Reporte</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" class="menu" title="nuevo_anticipo" onmouseup="cerrar()">Nuevo anticipo</a></li>
                <li><a href="#" class="menu" title="reporte_anticipo-opcion" onmouseup="cerrar()">Reporte anticipos</a></li>
                <li><a href="#" class="menu" title="nuevo_liquidacion" onmouseup="cerrar()">Liquidacíon a transportistas</a></li>
                <li><a href="#" class="menu" title="reporte_liquidacion-opcion" onmouseup="cerrar()">Reporte liquidaciones</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Servicios <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li class="disabled"><a href="#" class="menu" title="construccion_construccion" onmouseup="cerrar()">Nuevo servicio</a></li>
                <li role="separator" class="divider"></li>
                <li class="disabled"><a href="#" class="menu" title="construccion_construccion" onmouseup="cerrar()">Lista de precios</a></li>
                <li class="disabled"><a href="#" class="menu" title="construccion_construccion" onmouseup="cerrar()" >Nueva factura</a></li>
                <li class="disabled"><a href="#" class="menu" title="construccion_construccion" onmouseup="cerrar()">Reporte</a></li>
              </ul>
            </li>
                ';
                }else{echo '</ul></li>';}
            ?>
            <?php  if (strtolower($_SESSION['tipo_user']) == 'admin' || strtolower($_SESSION['tipo_user']) == 'empleado' ) {
            echo '
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Maquinarias <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#" class="menu" title="nuevo_horas-maquinaria" onmouseup="cerrar()">Horas Maquinaria</a></li>
                <li><a href="#" class="menu" title="nuevo_mantenimiento" onmouseup="cerrar()">Mantenimiento</a></li>
                <li role="separator" class="divider"></li>
                <li class="disabled"><a href="#" class="menu" title="construccion_construccion" onmouseup="cerrar()">Alertas de mantenimiento</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" class="menu" title="reporte_maquinaria-opcion" onmouseup="cerrar()">Reporte Horas Maquinaria</a></li>
                <li><a href="#" class="menu" title="reporte_mantenimiento-opcion" onmouseup="cerrar()">Reporte Mantenimientos</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Insumos <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#" class="menu" title="nuevo_remito" onmouseup="cerrar()">Remitos</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#"  class="menu" title="reporte_stock-opcion" onmouseup="cerrar()">Reporte de existencias</a></li>
                <li class="disabled"><a href="#" class="menu" title="construccion_construccion" onmouseup="cerrar()">Reporte de consumos</a></li>                
              </ul>
            </li>
            ';
                }else{echo '</ul></li>';}
            ?>
            <?php  if (strtolower($_SESSION['tipo_user']) == 'admin') {
            echo '
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Altas <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#" class="menu" title="nuevo_transportista" onmouseup="cerrar()">Transportista</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" class="menu" title="nuevo_origen" onmouseup="cerrar()">Origen</a></li>
                <li><a href="#" class="menu" title="nuevo_destino" onmouseup="cerrar()">Destino</a></li>
                <li role="separator" class="divider"></li>
                <li class="disabled"><a href="#" class="menu" title="construccion_construccion" onmouseup="cerrar()">Clientes</a></li>                
                <li role="separator" class="divider"></li>
                <li class="disabled"><a href="#" class="menu" title="construccion_construccion" onmouseup="cerrar()">Tipo de servicio</a></li>
                <li class="disabled"><a href="#" class="menu" title="construccion_construccion" onmouseup="cerrar()">Tipo de carga</a></li>
                <li class="disabled"><a href="#" class="menu" title="construccion_construccion" onmouseup="cerrar()">Tipo de cosecha</a></li>
                <li class="disabled"><a href="#" class="menu" title="construccion_construccion" onmouseup="cerrar()">Tipo de anticipos</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" class="menu" title="nuevo_maquinaria" onmouseup="cerrar()">Maquinarias</a></li>
                <li><a href="#" class="menu" title="nuevo_tipo-servicio" onmouseup="cerrar()">Tipos de Service</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" class="menu" title="nuevo_insumo" onmouseup="cerrar()">Insumos</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" class="menu" title="nuevo_unidad" onmouseup="cerrar()">Unidades</a></li>
                <li><a href="#" class="menu" title="nuevo_marca" onmouseup="cerrar()">Marcas</a></li>
              </ul>
            </li>                
              ';
                }else{echo '</ul></li>';}
            ?>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="navbar-brand" ><?php echo utf8_encode($_SESSION['usuario'])?></li>     
            <li class="active"><a href="conexion/logout.php">Salir <span class="sr-only"></span></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="page-header"></div>

    <div class="container" id="panel_inicio">
      
      <!-- Aqui se cargan los paneles de trabajo -->
      <div class="text-center">
      <img style="max-width:-webkit-fill-available; margin-top: 100px;  margin-right: 0px;" src="images/logogmo.png">
      </div>

    </div>

    <!-- <footer class="footer">
      <div class="container">
        <p class="text-muted">Desarrollado por apss.com.ar</p>
      </div>
    </footer> -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/fx.js"></script>
    <script src="js/bootstrap-toggle.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/icheck.min.js"></script>
    <script type="text/javascript">
    function cerrar(){
      $('.navbar-collapse').collapse('hide');
    }
    </script>
  </body>
</html>

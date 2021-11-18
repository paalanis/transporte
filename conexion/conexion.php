<?php
  $dbhost = 'localhost';
  $dbuser = 'root';
  $dbpassword = '';
  $database = 'gmo';
  $conexion = @mysqli_connect($dbhost,$dbuser,$dbpassword,$database);
  // if (mysqli_connect_errno()) {
  //  printf("La conexión con el servidor de base de datos falló: %s\n", mysqli_connect_error());
  //  exit();
// }
?>
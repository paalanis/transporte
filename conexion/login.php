<?php
$usuario=$_POST['usuario'];
$pass=$_POST['pass'];
if (isset($usuario) && isset($pass)) {
include 'conexion.php';
$sqlusuario = "SELECT
			tb_usuarios.id_usuario AS id_usuario,
			tb_usuarios.nombre AS usuario,
			tb_usuarios.data_base AS basedatos,
			tb_usuarios.tipo_user AS tipo_user,
			tb_transportista.id_transportista as id_chofer,
			tb_transportista.usuario AS usuario_chofer,
			CONCAT(tb_transportista.apellido, ', ', tb_transportista.nombre) as chofer
			FROM
			tb_usuarios
			LEFT JOIN tb_transportista ON tb_usuarios.id_usuario = tb_transportista.id_usuario
			WHERE
            tb_usuarios.nombre = '$usuario' AND
            tb_usuarios.pass = '$pass' OR
            tb_transportista.usuario = '$usuario' AND
			tb_transportista.pass = '$pass'
            ";
$rsusuario = mysqli_query($conexion, $sqlusuario);            
if (mysqli_num_rows($rsusuario) > 0) {
$sql_usuario = mysqli_fetch_array($rsusuario);
$tipo_user= $sql_usuario['tipo_user'];
$basedatos= $sql_usuario['basedatos'];
$usuario= $sql_usuario['usuario'];
$id_usuario= $sql_usuario['id_usuario'];
$id_chofer= $sql_usuario['id_chofer'];
$chofer= $sql_usuario['chofer'];
$usuario_chofer= $sql_usuario['usuario_chofer'];
session_start();
$_SESSION['usuario']=$usuario;
$_SESSION['tipo_user']=$tipo_user;
$_SESSION['basedatos']=$basedatos;
$_SESSION['id_usuario']=$id_usuario;
$_SESSION['id_chofer']=$id_chofer;
$_SESSION['chofer']=$chofer;
$_SESSION['usuario_chofer']=$usuario_chofer;
?>
<script type="text/javascript">
window.location="../index2.php"
</script>
<?php
}else{
?>
<script type="text/javascript">
window.location= "../indexerror.php"
</script>
<?php
}
}
?>
<script src="../js/jquery.min.js"></script>
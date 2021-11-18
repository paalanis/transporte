<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
include '../../conexion/conexion.php';
if (mysqli_connect_errno()) {
$array=array('success'=>'false');
echo json_encode($array);
exit();
}else{

$id_global=$_REQUEST['id_global'];

mysqli_select_db($conexion,'$basedatos');
$sql = "DELETE FROM tb_mantenimiento WHERE tb_mantenimiento.id_global = '$id_global'";
mysqli_query($conexion,$sql);

		$sqlbuscaconsumos = "SELECT
				tb_stock.id_stock as id_consumo,
				tb_stock.id_insumo as id_insumo
				FROM
				tb_stock
				WHERE
				tb_stock.id_global = '$id_global'";
		$rsbuscaconsumos = mysqli_query($conexion, $sqlbuscaconsumos);
		$cantidad =  mysqli_num_rows($rsbuscaconsumos);

		if ($cantidad > 0) { // si existen buscaconsumos con de esa finca se muestran, de lo contrario queda en blanco  

			
			mysqli_select_db($conexion,'$basedatos');
			$sql = "DELETE FROM tb_stock WHERE tb_stock.id_global = '$id_global'";
			mysqli_query($conexion,$sql);


			while ($datos = mysqli_fetch_array($rsbuscaconsumos)){
			$id_consumo_inicial=$datos['id_consumo'];
			$id_insumo=$datos['id_insumo'];


			$sqlsaldo = "SELECT
					tb_stock.saldo as saldo_anterior
					FROM
					tb_stock
					INNER JOIN tb_insumo ON tb_stock.id_insumo = tb_insumo.id_insumo
					WHERE
					tb_stock.id_stock IN ((SELECT 
					MAX(tb_stock.id_stock ) 
					FROM 
					tb_stock 
					WHERE
					tb_stock.id_stock < $id_consumo_inicial
					GROUP BY
					tb_stock.id_insumo)) AND
					tb_stock.id_insumo = $id_insumo
					";
			$rssaldo = mysqli_query($conexion, $sqlsaldo);
			$cantidad =  mysqli_num_rows($rssaldo);

			if ($cantidad > 0) { // si existen saldo con de esa finca se muestran, de lo contrario queda en blanco  

			$datos = mysqli_fetch_array($rssaldo);
			$saldo_anterior=utf8_encode($datos['saldo_anterior']);

			}else{

				$saldo_anterior=0;
			}

			// Buscamos los ingresos y egresos posteriores al remito eliminado y actualizamos saldos

			$sql_ing_eg = "SELECT
							tb_stock.id_stock as id_consumo_final,	
							tb_stock.ingreso as ingreso,
							tb_stock.egreso as egreso
							FROM
							tb_stock
							WHERE
							tb_stock.id_insumo = $id_insumo AND
							tb_stock.id_stock > $id_consumo_inicial
							";
			$rs_ing_eg = mysqli_query($conexion, $sql_ing_eg);
			$cantidad =  mysqli_num_rows($rs_ing_eg);

			if ($cantidad > 0) { // si existen _ing_eg con de esa finca se muestran, de lo contrario queda en blanco  

			 while ($datos = mysqli_fetch_array($rs_ing_eg)){
				$ingreso=utf8_encode($datos['ingreso']);
				$egreso=utf8_encode($datos['egreso']);
				$id_consumo_final=utf8_encode($datos['id_consumo_final']);  


				$saldo_actual = $saldo_anterior + $ingreso - $egreso;

				$saldo_anterior = $saldo_actual;

				mysqli_select_db($conexion,'$basedatos');
				$sql = "UPDATE tb_stock
						   SET tb_stock.saldo = $saldo_actual
						   WHERE tb_stock.id_stock = $id_consumo_final"; 
				mysqli_query($conexion, $sql);	

			 }
			}



			}

		}else{

			
		}


$array=array('success'=>'true');
echo json_encode($array);
}
?>
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if (PHP_SAPI == 'cli')
	die('Este ejemplo sólo se puede ejecutar desde un navegador Web');

/** Incluye PHPExcel */
require_once "../../excel/PHPExcel.php";
// Crear nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Propiedades del documento
$objPHPExcel->getProperties()->setCreator("Obed Alvarado")
							 ->setLastModifiedBy("Obed Alvarado")
							 ->setTitle("Office 2010 XLSX Documento de prueba")
							 ->setSubject("Office 2010 XLSX Documento de prueba")
							 ->setDescription("Documento de prueba para Office 2010 XLSX, generado usando clases de PHP.")
							 ->setKeywords("office 2010 openxml php")
							 ->setCategory("Archivo con resultado de prueba");



// Combino las celdas desde A1 hasta E1
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D1');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Reporte - Stock')
            ->setCellValue('A2', 'Nombre')
            ->setCellValue('B2', 'Marca')
            ->setCellValue('C2', 'Cantidad')
			->setCellValue('D2', 'Unidad');
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()->getStyle('A1:D2')->applyFromArray($boldArray);		

	
			
//Ancho de las columnas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);	

/*Extraer datos de MYSQL*/
	# conectare la base de datos
    
$insumo=$_REQUEST['dato_insumo'];

$consulta_insumos = "";
if ($insumo != "0") {
$consulta_insumos = "AND tb_stock.id_insumo = '$insumo'";
}

	
	include '../../conexion/conexion.php';
	if (mysqli_connect_errno()) {
	printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
	exit();
	}
	
	
	$sqlinsumo = "SELECT
	tb_insumo.nombre as insumo,
	tb_marca.nombre as marca,
	tb_stock.saldo as cantidad,
	tb_unidad.nombre as unidad
	FROM
	tb_stock
	LEFT JOIN tb_insumo ON tb_insumo.id_insumo = tb_stock.id_insumo
	LEFT JOIN tb_unidad ON tb_unidad.id_unidad = tb_insumo.id_unidad
	LEFT JOIN tb_marca ON tb_marca.id_marca = tb_insumo.id_marca
	WHERE
	tb_stock.id_stock IN ((SELECT MAX(tb_stock.id_stock ) FROM tb_stock GROUP BY tb_stock.id_insumo)) $consulta_insumos
	ORDER BY
	tb_insumo.nombre asc";

	$cel=3;//Numero de fila donde empezara a crear  el reporte

	$rsinsumo = mysqli_query($conexion, $sqlinsumo);
	while ($datos = mysqli_fetch_array($rsinsumo)){
	$insumo=utf8_encode($datos['insumo']);
	$marca=$datos['marca'];
	$cantidad=utf8_encode($datos['cantidad']);
	$unidad=utf8_encode($datos['unidad']);
		
			$a="A".$cel;
			$b="B".$cel;
			$c="C".$cel;
			$d="D".$cel;
			// Agregar datos
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($a, $insumo)
            ->setCellValue($b, $marca)
            ->setCellValue($c, $cantidad)
            ->setCellValue($d, $unidad);

			$cel+=1;
	}
			
	
/*Fin extracion de datos MYSQL*/
$rango="A2:$d";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN,'color'=>array('argb' => 'FFF')))
);
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte Stock');


// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);


// Redirigir la salida al navegador web de un cliente ( Excel5 )
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_stock.xls"');
header('Cache-Control: max-age=0');
// Si usted está sirviendo a IE 9 , a continuación, puede ser necesaria la siguiente
header('Cache-Control: max-age=1');

// Si usted está sirviendo a IE a través de SSL , a continuación, puede ser necesaria la siguiente
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
header("Location: ../../index.php");
}
if (PHP_SAPI == 'cli')
	die('Este ejemplo sólo se puede ejecutar desde un navegador Web');

/** Incluye PHPExcel */
require_once "../../classes/PHPExcel.php";
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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:L1');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Reporte - Viajes')
            ->setCellValue('A2', 'Parte n°')
            ->setCellValue('B2', 'Fecha')
            ->setCellValue('C2', 'Transportista')
			->setCellValue('D2', 'Remito')
			->setCellValue('E2', 'Tipo')
			->setCellValue('F2', 'Servicio')
			->setCellValue('G2', 'Carga')
			->setCellValue('H2', 'Tipo_carga')
			->setCellValue('I2', 'Origen')
			->setCellValue('J2', 'Destino')
			->setCellValue('K2', 'Km')
			->setCellValue('L2', 'Observación');
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()->getStyle('A1:L2')->applyFromArray($boldArray);		

	
			
//Ancho de las columnas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);	
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);

/*Extraer datos de MYSQL*/
	# conectare la base de datos
    
	$desde=$_POST['desde'];
	$hasta=$_POST['hasta'];
	$servicio=$_POST['servicio'];
    $transportista=$_POST['transportista'];
    
    $consulta_servicio = "";
    $consulta_transportista = "";


    if ($servicio != "0") {
    $consulta_servicio = "AND tb_viaje.id_servicio = '$servicio'";
    }
    if ($transportista != "0") {
    $consulta_transportista = "AND tb_viaje.id_transportista = '$transportista' ";
    }

	
	include '../../conexion/conexion.php';
	if (mysqli_connect_errno()) {
	printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
	exit();
	}
	$sqlviaje = "SELECT
				tb_viaje.id_viaje AS id_viaje,
				tb_viaje.id_global AS parte,
				DATE_FORMAT(tb_viaje.fecha, '%d/%m/%Y') AS fecha,
				CONCAT(tb_transportista.apellido, ', ', tb_transportista.nombre) AS chofer,
				tb_viaje.remito AS remito,
				tb_viaje.remito_tipo AS tipo_remito,
				tb_servicio.nombre AS servicio,
				tb_viaje.carga AS carga,
				tb_viaje.carga_tipo AS tipo_carga,
				tb_viaje.origen AS origen,
				tb_viaje.destino AS destino,
				tb_viaje.km AS km,
				tb_viaje.obs AS obs
				FROM
				tb_viaje
				LEFT JOIN tb_transportista ON tb_transportista.id_transportista = tb_viaje.id_transportista
				LEFT JOIN tb_servicio ON tb_servicio.id_servicio = tb_viaje.id_servicio
				WHERE
				tb_viaje.fecha BETWEEN '$desde' AND '$hasta' $consulta_servicio$consulta_transportista
				ORDER BY
				tb_viaje.fecha DESC";


	$cel=3;//Numero de fila donde empezara a crear  el reporte

	$rsviaje = mysqli_query($conexion, $sqlviaje);
	$cantidad =  mysqli_num_rows($rsviaje);
	while ($datos = mysqli_fetch_array($rsviaje)){
	$parte=utf8_encode($datos['parte']);
	$fecha=utf8_encode($datos['fecha']);
	$chofer=$datos['chofer'];
	$remito=utf8_encode($datos['remito']);
	$tipo_remito=utf8_encode($datos['tipo_remito']);
	$servicio=utf8_encode($datos['servicio']);
	$carga=utf8_encode($datos['carga']);
	$tipo_carga=utf8_encode($datos['tipo_carga']);
	$origen=utf8_encode($datos['origen']);
	$destino=utf8_encode($datos['destino']);
	$km=utf8_encode($datos['km']);
	$obs=utf8_encode($datos['obs']);
	$id_viaje=utf8_encode($datos['id_viaje']);
		
			$a="A".$cel;
			$b="B".$cel;
			$c="C".$cel;
			$d="D".$cel;
			$e="E".$cel;
			$f="F".$cel;
			$g="G".$cel;
			$h="H".$cel;
			$i="I".$cel;
			$j="J".$cel;
			$k="K".$cel;
			$l="L".$cel;
			// Agregar datos
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($a, $parte)
            ->setCellValue($b, $fecha)
            ->setCellValue($c, $chofer)
            ->setCellValue($d, $remito)
			->setCellValue($e, $tipo_remito)
			->setCellValue($f, $servicio)
			->setCellValue($g, $carga)
			->setCellValue($h, $tipo_carga)
			->setCellValue($i, $origen)
			->setCellValue($j, $destino)
			->setCellValue($k, $km)
			->setCellValue($l, $obs);

			$cel+=1;
	}
			
	
/*Fin extracion de datos MYSQL*/
$rango="A2:$l";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN,'color'=>array('argb' => 'FFF')))
);
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte Viajes');


// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);


// Redirigir la salida al navegador web de un cliente ( Excel5 )
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_viajes.xls"');
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
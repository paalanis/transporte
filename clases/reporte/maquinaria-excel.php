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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Reporte - Horas Maquinaria')
            ->setCellValue('A2', 'Fecha')
            ->setCellValue('B2', 'Maquinaria')
            ->setCellValue('C2', 'Marca')
			->setCellValue('D2', 'Hora Anterior')
			->setCellValue('E2', 'Hora Actual')
			->setCellValue('F2', 'Daños')
			->setCellValue('G2', 'Observación')
			->setCellValue('H2', 'Numero');
			
// Fuente de la primera fila en negrita
$boldArray = array('font' => array('bold' => true,),'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));

$objPHPExcel->getActiveSheet()->getStyle('A1:H2')->applyFromArray($boldArray);		

	
			
//Ancho de las columnas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

/*Extraer datos de MYSQL*/
	# conectare la base de datos
    
	    $desde=$_POST['desde'];
        $hasta=$_POST['hasta'];
        $maquinaria=$_POST['maquinaria'];
        
        $consulta_maquinaria = "";

        if ($maquinaria != "0") {
        $consulta_maquinaria = "AND tb_horas_maquinaria.id_maquinaria = '$maquinaria'";
        }

		include '../../conexion/conexion.php';
		if (mysqli_connect_errno()) {
		printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
		exit();
		}

		$sqlmaquinaria = "SELECT
		DATE_FORMAT(tb_horas_maquinaria.fecha, '%d/%m/%Y') AS fecha,
		tb_maquinaria.nombre AS maquinaria,
		tb_marca.nombre AS marca,
		tb_horas_maquinaria.hora_anterior AS anterior,
		tb_horas_maquinaria.hora_actual AS actual,
		tb_horas_maquinaria.danio AS danio,
		tb_horas_maquinaria.obs AS obs,
		tb_horas_maquinaria.id_horas_maquinaria as id_hora
		FROM
		tb_horas_maquinaria
		LEFT JOIN tb_maquinaria ON tb_maquinaria.id_maquinaria = tb_horas_maquinaria.id_maquinaria
		LEFT JOIN tb_marca ON tb_marca.id_marca = tb_maquinaria.id_marca
		WHERE
		tb_horas_maquinaria.fecha BETWEEN '$desde' AND '$hasta' $consulta_maquinaria
		ORDER BY
		tb_horas_maquinaria.fecha DESC
		";
		
		$cel=3;//Numero de fila donde empezara a crear  el reporte

		$rsmaquinaria = mysqli_query($conexion, $sqlmaquinaria);
		while ($datos = mysqli_fetch_array($rsmaquinaria)){
		$fecha=utf8_encode($datos['fecha']);
		$maquinaria=$datos['maquinaria'];
		$marca=utf8_encode($datos['marca']);
		$anterior=utf8_encode($datos['anterior']);
		$actual=utf8_encode($datos['actual']);
		$danio=utf8_encode($datos['danio']);
		$obs=utf8_encode($datos['obs']);
		$id_hora=utf8_encode($datos['id_hora']);

		
			$a="A".$cel;
			$b="B".$cel;
			$c="C".$cel;
			$d="D".$cel;
			$e="E".$cel;
			$f="F".$cel;
			$g="G".$cel;
			$h="H".$cel;
			
			// Agregar datos
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($a, $fecha)
            ->setCellValue($b, $maquinaria)
            ->setCellValue($c, $marca)
            ->setCellValue($d, $anterior)
			->setCellValue($e, $actual)
			->setCellValue($f, $danio)
			->setCellValue($g, $obs)
			->setCellValue($h, $id_hora);

			$cel+=1;
	}
			
	
/*Fin extracion de datos MYSQL*/
$rango="A2:$h";
$styleArray = array('font' => array( 'name' => 'Arial','size' => 10),
'borders'=>array('allborders'=>array('style'=> PHPExcel_Style_Border::BORDER_THIN,'color'=>array('argb' => 'FFF')))
);
$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
// Cambiar el nombre de hoja de cálculo
$objPHPExcel->getActiveSheet()->setTitle('Reporte Horas Maquinaria');


// Establecer índice de hoja activa a la primera hoja , por lo que Excel abre esto como la primera hoja
$objPHPExcel->setActiveSheetIndex(0);


// Redirigir la salida al navegador web de un cliente ( Excel5 )
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_horas_maquinaria.xls"');
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
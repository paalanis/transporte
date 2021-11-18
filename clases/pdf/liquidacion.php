<?php
	include 'plantilla.php';
	include '../../conexion/conexion.php';
	if (mysqli_connect_errno()) {
	printf("La conexión con el servidor de base de datos falló comuniquese con su administrador: %s\n", mysqli_connect_error());
	exit();
	}
	
	date_default_timezone_set("America/Argentina/Mendoza");
	$hoy = date("d-m-Y");

	$dato=$_REQUEST['dato'];	
//viajes ---------------------------------------------------------------------------------------
	$sqlviajes = "SELECT
	DATE_FORMAT(tb_viaje.fecha, '%d/%m/%y') AS fecha,
	tb_origen.nombre as origen,
	tb_destino.nombre as destino,
	tb_viaje.remito as remito,	
	tb_tipo_camion.tipo_camion as tipocamion,
	tb_viaje.patente_chasis as patente_c,
	tb_viaje.patente_equipo as patente_e,
	tb_tipo_carga.tipo_carga as carga,
	tb_viaje.cantidad as cantidad,
	IF(tb_viaje.descarga_manual = '0', '', 'Manual') as descarga,
	tb_viaje.km as km,
	CONCAT('$ ',tb_viaje.precio) as precio
	FROM
	tb_viaje
	LEFT JOIN tb_origen ON tb_viaje.id_origen = tb_origen.id_origen
	LEFT JOIN tb_destino ON tb_viaje.id_destino = tb_destino.id_destino
	LEFT JOIN tb_tipo_camion ON tb_viaje.id_tipo_camion = tb_tipo_camion.id_tipo_camion
	LEFT JOIN tb_tipo_carga ON tb_viaje.id_tipo_carga = tb_tipo_carga.id_tipo_carga
	WHERE
	tb_viaje.id_liquidacion = '$dato'
	ORDER BY
	tb_viaje.fecha DESC
	";

	$rsviajes = mysqli_query($conexion, $sqlviajes);
	$cantidad1 =  mysqli_num_rows($rsviajes);

//viajes ---------------------------------------------------------------------------------------


//liquidacion ---------------------------------------------------------------------------------------
	$sqlliquidacion = "SELECT
	DATE_FORMAT(tb_liquidacion.fecha, '%d/%m/%y') AS fecha,
	DATE_FORMAT(tb_liquidacion.fecha_i, '%d/%m/%y') AS fecha_i,
	DATE_FORMAT(tb_liquidacion.fecha_f, '%d/%m/%y') AS fecha_f,
	CONCAT(tb_transportista.apellido,', ', tb_transportista.nombre) AS chofer,
	CONCAT(tb_transportista.apellido,'-', tb_transportista.nombre) AS archivo,
	tb_transportista.dni AS dni,
	tb_servicio.nombre AS servicio,
	tb_liquidacion.monto_viajes as monto_viaje,
	tb_liquidacion.monto_comision as monto_comision,
	tb_liquidacion.monto_iva as monto_iva,
	tb_liquidacion.monto_anticipos as monto_anticipo,
	tb_liquidacion.monto_retencion as monto_retencion,
	tb_liquidacion.monto_final as monto_final,
	tb_liquidacion.numero as liquidacion
	FROM
	tb_liquidacion
	LEFT JOIN tb_transportista ON tb_transportista.id_transportista = tb_liquidacion.id_transportista
	LEFT JOIN tb_servicio ON tb_servicio.id_servicio = tb_liquidacion.id_servicio
	WHERE
	tb_liquidacion.id_liquidacion = '$dato'";

	$rsliquidacion = mysqli_query($conexion, $sqlliquidacion);
	$datos = mysqli_fetch_array($rsliquidacion);
	$fecha=$datos['fecha'];
	$fecha_i=utf8_encode($datos['fecha_i']);
	$fecha_f=utf8_encode($datos['fecha_f']);
	$chofer=utf8_encode($datos['chofer']);
	$archivo=utf8_encode($datos['archivo']);
	$dni=utf8_encode($datos['dni']);
	$servicio=utf8_encode($datos['servicio']);
	$monto_viaje=utf8_encode($datos['monto_viaje']);
	$monto_comision=utf8_encode($datos['monto_comision']);
	$monto_iva=utf8_encode($datos['monto_iva']);
	$monto_anticipo=utf8_encode($datos['monto_anticipo']);
	$monto_retencion=utf8_encode($datos['monto_retencion']);
	$monto_final=utf8_encode($datos['monto_final']);
	$liquidacion=utf8_encode($datos['liquidacion']);
//liquidacion ---------------------------------------------------------------------------------------.
	
	$pdf = new PDF();
	$title = utf8_decode('Liquidación Transportistas');
	$pdf->SetTitle($title);
	$pdf->AliasNbPages();
	$pdf->AddPage();	

    // Arial 12
    $pdf->SetFont('Arial','',12);	
    // Color de fondo
    $pdf->SetFillColor(231,235,218);
    // Título
    $pdf->Cell(0,10,utf8_decode("NÚMERO DE LIQUIDACIÓN : ".$liquidacion.""),0,1,'L',true);
    // Salto de línea
    $pdf->Ln(4);
    $pdf->Cell(0,10,utf8_decode("FECHA : ".$fecha.""),0,1,'L',true);
    $pdf->Ln(4);
    $pdf->Cell(0,10,utf8_decode("PERIDIO LIQUIDACIÓN : ".$fecha_i." - ".$fecha_f.""),0,1,'L',true);
    $pdf->Ln(4);
    $pdf->Cell(0,10,utf8_decode("CHOFER : ".$chofer.""),0,1,'L',true);
    $pdf->Ln(4);
    $pdf->Cell(0,10,utf8_decode("DNI : ".$dni.""),0,1,'L',true);
    $pdf->Ln(4);
    $pdf->Cell(0,10,utf8_decode("Total viajes : $".$monto_viaje.""),0,1,'L',true);
    $pdf->Ln(4);
    $pdf->Cell(0,10,utf8_decode("- Comisión 10% : ($".$monto_comision.")"),0,1,'L',true);
    $pdf->Ln(4);
    $pdf->Cell(0,10,utf8_decode("Total neto a pagar : $".($monto_viaje-$monto_comision).""),0,1,'L',true);
    $pdf->Ln(4);
    $pdf->Cell(0,10,utf8_decode("+ IVA 21% : $".$monto_iva.""),0,1,'L',true);
    $pdf->Ln(4);
    $pdf->Cell(0,10,utf8_decode("Total facturado : $".($monto_viaje-$monto_comision+$monto_iva).""),0,1,'L',true);
    $pdf->Ln(4);
    $pdf->Cell(0,10,utf8_decode("- Anticipos : ($".$monto_anticipo.")"),0,1,'L',true);
    $pdf->Ln(4);
    $pdf->Cell(0,10,utf8_decode("A pagar : $".($monto_viaje+$monto_iva-$monto_anticipo-$monto_comision).""),0,1,'L',true);
    $pdf->Ln(4);
    $pdf->Cell(0,10,utf8_decode("- Retencion (0.25%) : ($".$monto_retencion.")"),0,1,'L',true);
    $pdf->Ln(4);
    $pdf->Cell(0,10,utf8_decode("Total a pagar : $".$monto_final.""),0,1,'L',true);


 //    $pdf->PrintChapter(''.$liquidacion.'','ANTICIPOS','');

 //    $pdf->SetFillColor(232,232,232);
	// $pdf->SetFont('Arial','',12);
	// $pdf->Cell(47,6,'Fecha',1,0,'C',1);
	// $pdf->Cell(47,6,'Tipo de anticipo',1,0,'C',1);
	// $pdf->Cell(47,6,'Monto',1,0,'C',1);
	// $pdf->Cell(47,6,utf8_decode('Observación'),1,0,'C',1);
	// $pdf->Ln(10);

    
	// //if ($cantidad_anti > 0) { 

 //    while ($datos = mysqli_fetch_array($rsanticipos)){
	// $fecha=utf8_decode($datos['fecha']);
	// $tipo=utf8_decode($datos['tipo']);
	// $monto=$datos['monto'];
	// $obs=utf8_decode($datos['obs']);
	// $pdf->SetFont('Arial','',12);
	// $pdf->Cell(70,6, $fecha.' - '.$tipo.' - $ '.$monto.' - '.$obs,0,0,'L');
	// $pdf->Ln(4);

	// 	}	
	//}else { echo 'Sin Satos';}

	$pdf->PrintChapter(''.$liquidacion.'','VIAJES','');
	
	$pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(14,6,'Fecha',1,0,'C',1);
	$pdf->Cell(16,6,'Remito',1,0,'C',1);
	$pdf->Cell(15,6,'Origen',1,0,'C',1);
	$pdf->Cell(16,6,'Destino',1,0,'C',1);
	$pdf->Cell(10,6,'Tipo',1,0,'C',1);
	$pdf->Cell(20,6,'Patente C',1,0,'C',1);
	$pdf->Cell(20,6,'Patente E',1,0,'C',1);
	$pdf->Cell(15,6,'Carga',1,0,'C',1);
	$pdf->Cell(19,6,'Cantidad',1,0,'C',1);
	$pdf->Cell(19,6,'Dercarga',1,0,'C',1);
	$pdf->Cell(8,6,'Km',1,0,'C',1);
	$pdf->Cell(17,6,'Precio',1,0,'C',1);
	$pdf->Ln(10);

	//if ($cantidad1 > 0) { 
		
	while ($datos = mysqli_fetch_array($rsviajes)){
	$fecha=utf8_decode($datos['fecha']);
	$origen=utf8_decode($datos['origen']);
	$destino=utf8_decode($datos['destino']);
	$remito=utf8_decode($datos['remito']);
	$tipocamion=utf8_decode($datos['tipocamion']);
	$patente_c=utf8_decode($datos['patente_c']);
	$patente_e=utf8_decode($datos['patente_e']);
	$carga=utf8_decode($datos['carga']);
	$cantidad=utf8_decode($datos['cantidad']);
	$descarga=utf8_decode($datos['descarga']);
	$km=utf8_decode($datos['km']);
	$precio=utf8_decode($datos['precio']);
	
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(0,6, $fecha.' - '.$remito.' - '.$origen.' - '.$destino.' - '.$tipocamion.' - '.$patente_c.' - '.$patente_e.' - '.$carga.' - '.$cantidad.' - '.$descarga.' - '.$km.' - '.$precio,0,'J',false);
	$pdf->Ln(4);
		}
	//}else { echo 'Sin Satos';}

	$pdf->Output('D','Liquidacion_'.$liquidacion.'-'.$archivo.'.pdf');
?>
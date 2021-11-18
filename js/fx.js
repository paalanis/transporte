// MENU --------------------------------------------------------------------------------
	$(function() {

		$('.menu').click(function(){

			var titulo = $(this).attr('title')
			var datos = titulo.split('_',2)
			
			var carpeta = datos[0]
			var opcion = datos[1]

			$("#panel_inicio").html('<div class="text-center"><div class="loadingsm"></div></div>');
			$('#panel_inicio').load("clases/"+carpeta+"/"+opcion+".php");

		})
	})
	//FIN MENU	------------------------------------------------------------------------

// NUEVOS --------------------------------------------------------------------------------

	function nuevo(formulario){

		var pars = ''
		var campos = Array()
		var campospasan = Array()

		$("#formulario_nuevo").find(':input').each(function(){
              
            $(this).attr('id')
            var dato = $(this).attr('id').split('_',2) 
          
            if (dato[0] == 'dato') {
               campos.push("dato_"+dato[1])
              campospasan.push("dato_"+dato[1])
            };
              
          });
		
		 for (i = 0; i < campos.length; i++) {
			campo = document.getElementById(campos[i]);

			pars =pars + campospasan[i] + "=" + campo.value + "&";
		 }	
		//alert(pars);
				
				$("#div_mensaje_general").html('<div class="text-center"><div class="loadingsm"></div></div>');
				$('#boton_guardar').attr('disabled', true);

				$.ajax({
						url : "clases/guardar/"+formulario+".php",
						data : pars,
						dataType : "json",
						type : "get",

						success: function(data){
								
							if (data.success == 'true') {

								$('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Ingreso exitoso!</div>');				
								setTimeout("$('#mensaje_general').alert('close')", 1000);
								setTimeout("$('#panel_inicio').load('clases/nuevo/"+formulario+".php')", 1050);
							} else {
								$('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error reintente!</div>');				
								setTimeout("$('#mensaje_general').alert('close')", 2000);
							}
						
						}	

				});

	}

	function liquidacion(formulario){

		var pars = ''
		var campos = Array()
		var campospasan = Array()

		$("#formulario_reporte").find(':input').each(function(){
              
            $(this).attr('id')
            var dato = $(this).attr('id').split('_',2) 
          
            if (dato[0] == 'dato') {
               campos.push("dato_"+dato[1])
              campospasan.push("dato_"+dato[1])
            };
              
          });
						
		 for (i = 0; i < campos.length; i++) {
			campo = document.getElementById(campos[i]);
			
			pars =pars + campospasan[i] + "=" + campo.value + "&";
		 }	
		//alert(pars);
				
				$("#div_reporte").html('<div class="text-center"><div class="loadingsm"></div></div>');
				$('#boton_crear').attr('disabled', true);

				$.ajax({
						url : "clases/guardar/"+formulario+".php",
						data : pars,
						dataType : "json",
						type : "get",

						success: function(data){
								
							if (data.success == 'true') {
								$('#div_reporte').html('<div class="col-lg-10"><div id="mensaje_general" class="alert alert-info alert-dismissible" role="alert">Se ha creado con éxito la liquidación N° <span class="badge">'+data.numero+'</span></div></div><div id="div_boton_imprimir" class="col-lg-2"><button type="button" id="boton_imprimir" class="btn btn-primary btn-lg" onclick="imprimepdf(1,'+data.dato+')">Imprimir PDF <span class="glyphicon glyphicon-print" aria-hidden="true"></span></button></div>');				
								//$('#div_reporte').html('<input type="text" value="liquidación" class="form-control" id="liquidacion" aria-describedby="basic-addon1">')
								//$('#div_reporte').html('<input type="text" value="'+data.dato+'" class="form-control" id="id_liq" aria-describedby="basic-addon1">')
								//setTimeout("$('#mensaje_general').alert('close')", 1000);
								//setTimeout("$('#panel_inicio').load('clases/nuevo/"+formulario+".php')", 1050);
							} else {
								$('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error reintente!</div>');				
								setTimeout("$('#mensaje_general').alert('close')", 2000);
							}
						
						}	

				});

	}

 	//FIN NUEVOS	------------------------------------------------------------------------

// REPORTES --------------------------------------------------------------------------------

	function reporte(formulario){

		var pars = ''
		var campos = Array()
		var campospasan = Array()

		$("#formulario_reporte").find(':input').each(function(){
              
            //alert($(this).attr('id'))
            var dato = $(this).attr('id').split('_',2) 

            if (dato[0] == 'dato') {
               campos.push("dato_"+dato[1])
              campospasan.push("dato_"+dato[1])
            };
              
          });
		
		 for (i = 0; i < campos.length; i++) {
			campo = document.getElementById(campos[i]);

			pars =pars + campospasan[i] + "=" + campo.value + "&";
		 }	
		  //alert(pars);
				
				$("#div_reporte").html('<div class="text-center"><div class="loadingsm"></div></div>');
				$("#div_reporte").load("clases/reporte/"+formulario+".php", pars);

	}

	//FIN REPORTES	------------------------------------------------------------------------

// CONTROLES --------------------------------------------------------------------------------

	function saldo(){
		// if (typeof $('#dato_insumo').val() == "undefined"){
  		//	var insumo = $("#insumo_e").val()
		// }else{

			var insumo = $("#dato_insumo").val()
		// }

		
		if (insumo != "") {
		$("#div_saldo").html('<div class="text-center"><div class="loadingsm"></div></div>');
		$("#div_saldo").load("clases/control/saldo_insumo.php", {insumo: insumo});			
		}else{
			$("#div_saldo").html('');
		}
	}

	//FIN CONTROLES	------------------------------------------------------------------------
	
// MODIFICA --------------------------------------------------------------------------------

	function modifica(formulario){

		var pars = ''
		var campos = Array()
		var campospasan = Array()

		$("#formulario_nuevo").find(':input').each(function(){
              
            $(this).attr('id')
            var dato = $(this).attr('id').split('_',2) 

            if (dato[0] == 'dato') {
               campos.push("dato_"+dato[1])
              campospasan.push("dato_"+dato[1])
            };
              
          });
		
		 for (i = 0; i < campos.length; i++) {
			campo = document.getElementById(campos[i]);

			pars =pars + campospasan[i] + "=" + campo.value + "&";
		 }	
		 //alert(pars);
				
				$("#div_mensaje_general").html('<div class="text-center"><div class="loadingsm"></div></div>');
				$('#boton_guardar').attr('disabled', true);

				$.ajax({
						url : "clases/modifica/"+formulario+".php",
						data : pars,
						dataType : "json",
						type : "get",

						success: function(data){
								
							if (data.success == 'true') {

								$('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-info alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Ingreso exitoso!</div>');				
								setTimeout("$('#mensaje_general').alert('close')", 1000);
								if (formulario == 'viaje'){
								setTimeout("$('#panel_inicio').load('clases/reporte/"+formulario+"-opcion.php')", 1050);	
								}else{
								setTimeout("$('#panel_inicio').load('clases/nuevo/"+formulario+".php')", 1050);
								}
							} else {
								$('#div_mensaje_general').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error reintente!</div>');				
								setTimeout("$('#mensaje_general').alert('close')", 2000);
							}
						
						}	

				});	
	}

 	//FIN MODIFICA	------------------------------------------------------------------------

	function carga_insumo(){

		document.getElementById('boton_insumo').value ='';
		document.getElementById('boton_insumo').disabled =true;

		var insumo = $("#dato_insumo").val()
		var cantidad = $("#dato_insumo-cantidad").val()
		var fecha = $("#dato_fecha").val()
		if (insumo == '' || cantidad == '' || fecha == '') {
	 		if (insumo == '') {
	 			$("#dato_insumo").tooltip({title: "Debe seleccionar", placement: "top"});
                $("#dato_insumo").tooltip('show');
	 		};
	 		if (cantidad == '') {
	 			$("#dato_insumo-cantidad").tooltip({title: "No debe ser cero", placement: "top"});
                $("#dato_insumo-cantidad").tooltip('show');
	 		};
	 		if (fecha == '') {
	 			$("#dato_fecha").tooltip({title: "Debe seleccionar", placement: "top"});
                $("#dato_fecha").tooltip('show');
	 		};
	 		document.getElementById('boton_insumo').disabled =false;
	 	}else{
		var pars = "dato_insumo=" + insumo + "&" + "dato_insumo-cantidad=" + cantidad + "&" + "dato_fecha=" + fecha + "&";
		// alert(pars);
					$("#div_insumos_cargados").html('<div class="text-center"><div class="loadingsm"></div></div>');
					$.ajax({
							url : "clases/guardar/consumo.php",
							data : pars,
							dataType : "json",
							type : "get",

							success: function(data){
									
								if (data.success == 'true') {

									$("#dato_insumo").val('');
									$("#dato_insumo-cantidad").val('');
									document.getElementById('boton_insumo').disabled =false;
									$("#div_insumos_cargados").load("clases/control/insumos_cargados.php");
									$("#div_saldo").html('');

								} else {
									$('#div_insumos_cargados').html('<div id="mensaje_general" class="alert alert-danger alert-dismissible" style="height:47px" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error reintente!</div>');				
									setTimeout("$('#mensaje_general').alert('close')", 2000);
								}
							
							}

					});
					
		}
	}


	function inicio(){

		 window.location.href = "index2.php";
	}


	function imprimepdf(pdf,dato){

		var pdf = pdf.toString();
		var dato = dato.toString();
		//alert(pdf)
		//alert(dato)

		switch(pdf){

			case '1':
			var pdf = 'liquidacion'
			break

		}


		$("#div_boton_imprimir").html('<div class="text-center"><div class="loadingsm"></div></div>');
		location.href = 'http://localhost/gmo/clases/pdf/'+pdf+'.php/?dato='+dato+''
		setTimeout("$('#panel_inicio').load('clases/nuevo/"+pdf+".php')", 2000);
	

	}
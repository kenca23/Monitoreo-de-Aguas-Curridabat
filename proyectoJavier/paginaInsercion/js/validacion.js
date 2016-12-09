	function cambio() {
		var value = $('#indice').val();
		if(value == "holandes"){
			document.getElementById("nada").innerHTML = "Índice Holandés, valores enteros o decimales.";
			//Escondidos
			$('#divNH4_O').hide();
			$('#divCF').hide();
			$('#divPH').hide();
			$('#divFosfato').hide();
			$('#divNitrato').hide();
			$('#divT').hide();
			$('#divTurbidez').hide();
			$('#divSolTot').hide();
			document.getElementById("cf").required = false;
			document.getElementById("ph").required = false;
			document.getElementById("fosfato").required = false;
			document.getElementById("nitrato").required = false;
			document.getElementById("t").required = false;
			document.getElementById("turbidez").required = false;
			document.getElementById("solTot").required = false;
			//Enseñados y opcionales
			$('#divCF_O').show();
			$('#divPH_O').show();
			$('#divFosfato_O').show();
			$('#divNitrato_O').show();
			$('#divT_O').show();
			$('#divTurbidez_O').show();
			$('#divSolTot_O').show();
			//Enseñados y requeridos
			$('#divPO2').show();
			$('#divDBO').show();
			$('#divNH4').show();
			document.getElementById("pO2").required = true;
			document.getElementById("dbo").required = true;
			document.getElementById("nh4").required = true;

		}
		else if(value == "NSF"){
			document.getElementById("nada").innerHTML = "Índice NSF, valores enteros o decimales.";
			//Escondidos
			$('#divCF_O').hide();
			$('#divPH_O').hide();
			$('#divFosfato_O').hide();
			$('#divNitrato_O').hide();
			$('#divT_O').hide();
			$('#divTurbidez_O').hide();
			$('#divSolTot_O').hide();
			$('#divNH4').hide();
			document.getElementById("nh4").required = false;
			//Enseñados y opcionales
			$('#divNH4_O').show();
			//Enseñados y requeridos
			$('#divPO2').show();
			$('#divCF').show();
			$('#divDBO').show();
			$('#divPH').show();
			$('#divFosfato').show();
			$('#divNitrato').show();
			$('#divT').show();
			$('#divTurbidez').show();
			$('#divSolTot').show();
			document.getElementById("pO2").required = true;
			document.getElementById("cf").required = true;
			document.getElementById("dbo").required = true;
			document.getElementById("ph").required = true;
			document.getElementById("fosfato").required = true;
			document.getElementById("nitrato").required = true;
			document.getElementById("t").required = true;
			document.getElementById("turbidez").required = true;
			document.getElementById("solTot").required = true;		
		}else{
			document.getElementById("nada").innerHTML = "Índice WQIB, valores enteros o decimales.";
			//Escondidos
			$('#divPO2').hide();
			$('#divCF').hide();
			$('#divDBO').hide();
			$('#divPH').hide();
			$('#divFosfato').hide();
			$('#divNitrato').hide();
			$('#divT').hide();
			$('#divTurbidez').hide();
			$('#divSolTot').hide();
			$('#divNH4').hide();
			document.getElementById("nh4").required = false;
			document.getElementById("pO2").required = false;
			document.getElementById("cf").required = false;
			document.getElementById("dbo").required = false;
			document.getElementById("ph").required = false;
			document.getElementById("fosfato").required = false;
			document.getElementById("nitrato").required = false;
			document.getElementById("t").required = false;
			document.getElementById("turbidez").required = false;
			document.getElementById("solTot").required = false;
			//Enseñados y opcionales
			$('#divCF_O').show();
			$('#divPH_O').show();
			$('#divFosfato_O').show();
			$('#divNitrato_O').show();
			$('#divT_O').show();
			$('#divTurbidez_O').show();
			$('#divSolTot_O').show();
			$('#divNH4_O').show();
			//Enseñados y requeridos
		}
	}

$(document).ready(
);

function validateForm() {

}
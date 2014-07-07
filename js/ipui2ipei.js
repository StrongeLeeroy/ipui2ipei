function calculate(){
				ipui = document.getElementById("ipui").value
				// ipui = ipui.replace(/\s+/g, '')

				if (ipui.length < 9) {
					alert("ERROR: Debe de haber al menos 9 dígitos hexadecimales.")
					document.getElementById("ipui-group").className = "form-group has-error has-feedback"
					document.getElementById("ipuiVal").className = "glyphicon form-control-feedback glyphicon-remove"
					return false
				}

				ipui = ipui.substr(1)

				ipuiFirst = ipui.substr(0,4)
				vipuiFirstHex = ipuiFirst;

				ipuiFirst = parseInt(ipuiFirst, 16)
				vipuiFirstDec = ipuiFirst
				vipuiFirstBin = vipuiFirstDec.toString(2).toUpperCase();

				ipuiFirst = ipuiFirst.toString()

				while (ipuiFirst.length < 5) {
					ipuiFirst = "0" + ipuiFirst
				};
				vipuiFirstBinComplete = ipuiFirst

				ipuiLast = ipui.substr(4)
				vipuiLastHex = ipuiLast;

				ipuiLast = parseInt(ipuiLast, 16)
				vipuiLastDec = ipuiLast
				vipuiLastBin = vipuiLastDec.toString(2).toUpperCase();

				ipuiLast = ipuiLast.toString()

				while (ipuiLast.length < 7) {
					ipuiLast = "0" + ipuiLast;
				};
				vipuiLastBinComplete = ipuiLast

				ipei = ipuiFirst + ipuiLast

				ipei = ipei + checksum(ipei);

				document.getElementById("ipei").value = ipei

				document.getElementById("ipui-group").className = "form-group has-success has-feedback"
					document.getElementById("ipuiVal").className = "glyphicon form-control-feedback glyphicon-ok"

				if (document.getElementById("verbose").checked) {
					document.getElementById("verboseBox").style.display = "block";
					document.getElementById("verboseContent").innerHTML = "<br><b>EMC (16 bits)</b><br>EMC Hexadecimal: " + vipuiFirstHex + "<br>EMC Binario: " + vipuiFirstBin + "<br>EMC Decimal: " + vipuiFirstDec + "<br>EMC Binario (5 dígitos): " + vipuiFirstBinComplete + "<br><br><b>PSN (20 bits)</b><br>PSN Hexadecimal: " + vipuiLastHex + "<br>PSN Binario: " + vipuiLastBin + "<br>PSN Decimal: " + vipuiLastDec + "<br>PSN Decimal (7 dígitos): " + vipuiLastBinComplete;

					document.getElementById("verboseContentFinal").innerHTML = "<br><b>Resultado final</b><br>IPEI: " + vipuiFirstBinComplete + " " + vipuiLastBinComplete + " " + vcheckDigitFinal;
				};
			};


			/* Cálculo del código de control según el ETSI (EN 300 175-6 - Anexo C).
			   Cada dígito se multiplica por el número de su posición, de izquierda
			   a derecha, empezando desde el 1. */
			function checksum(code) {
				var checkDigit = 0
				var vcheckDigit = ""
				for (var i = 0; i < code.length; i++) {
					checkDigit = checkDigit + (parseInt(code[i]) * (i + 1))
					if (document.getElementById("verbose").checked){
						if (i == 0) {
							vcheckDigit = "(" + code[i] + "*" + (i + 1) + ")"
						} else {
							vcheckDigit = vcheckDigit + " + " + "(" + code[i] + "*" + (i + 1) + ")"
						};
					};
				};
				vcheckDigitNoMod = checkDigit
				checkDigit = checkDigit % 11;
				vcheckDigitFinal = checkDigit;

				if (checkDigit == 10) {
					checkDigit = "*"
					vcheckDigitFinal = "10 (Se traduce a *)"
				};
				
				if (document.getElementById("verbose").checked) {
					document.getElementById("verboseContentChecksum").innerHTML = "<br><b>Checksum (ETSI EN 300 175-6)</b><br>Número sin código de control: " + code + "<br>Suma de dígitos: " + vcheckDigit + " = <b>" + vcheckDigitNoMod + "</b><br>Módulo: " + vcheckDigitNoMod + " mod 11 = " + vcheckDigitFinal;	
				};

				return checkDigit
			};
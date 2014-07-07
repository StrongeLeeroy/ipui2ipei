<!DOCTYPE html>
<html>
	<head>
		<title>IPUI a IPEI</title>
		<meta charset="utf-8">
		<script type="text/javascript" src="script.js"></script>

		<style type="text/css">
			html {font-family: Helvetica, Arial, sans-serif;}
			body {padding: 20px;}
			fieldset {width: 680px;}
			form {padding: 20px;}
			#verboseContent,#verboseContentChecksum,#verboseContentFinal{font-family: monospace;padding-left: 25px;}
			div#standard {font-family: monospace; font-size: 10px; float: right;}
		</style>
	</head>
	<body>
		<fieldset>
			<legend><b>Conversión de IPUI a IPEI</b></legend>
			<form action="index.php?action=convert" method="GET">
				<label for="ipui">IPUI:</label>
				<input type="text" id="ipui" name="ipui" maxlength="10">
				<input type="submit" value="Convertir"><br><br>

				<label for="ipei">IPEI:</label>
				<input type="text" id="ipei" name="ipei" maxlength="13"><br><br>

				<input type="checkbox" id="verbose" name="verbose" value="1">
				<label for="verbose">Verbose</label>
		<?php
			$action = $_GET["action"];
			$verbose = $_GET["verbose"];
			if ($action == "convert") {
				$ipui = $_GET["ipui"];

				if (strlen($ipui < 9)) {
					# ERROR
				}

				$ipui = substr($ipui, 1);

				$ipuiFirst = substr($ipui, 0, 4);
				$vipuiFirstHex = $ipuiFirst;

				$ipuiFirst = hexdec($ipuiFirst);
				$vipuiFirstDec = $ipuiFirst;
				$vipuiFirstBin = decbin($vipuiFirstDec);

				while (strlen($ipuiFirst) < 5) {
					$ipuiFirst = "0" . $ipuiFirst;
				}
				$vipuiFirstBinComplete = $ipuiFirst;


				$ipuiLast = substr($ipui, 4);
				$vipuiLastHex = $ipuiLast;

				$ipuiLast = hexdec($ipuiLast);
				$vipuiLastDec = $ipuiLast;
				$vipuiLastBin = decbin($vipuiLastDec);

				while (strlen($ipuiLast) < 7) {
					$ipuiLast = "0" . $ipuiLast;
				}
				$vipuiLastBinComplete = $ipuiLast;

				$ipei = $ipuiFirst . $ipuiLast;
				$ipei = $ipei + checksum($ipei);
			}

			function checksum(code) {
				$checkDigit = 0;
				$vcheckDigit = "";
				for ($i=0; $i < strlen($code); $i++) { 
					$checkDigit = $checkDigit + ($code[$i] * ($i + 1));
					if ($verbose == 1) {
						if ($i == 0) {
							$vcheckDigit = "(" . $code[$i] . "*" . ($i + 1) . ")";
						} else {
							$vcheckDigit = $vcheckDigit . "+" . "(" . $code[$i] . "*" . ($i + 1) . ")";
						}
					}
				}

				$vcheckDigitNoMod = $checkDigit;
				$checkDigit = $checkDigit % 11;
				$vcheckDigitFinal = $checkDigit;

				if ($checkDigit == 10) {
					$checkDigit = "*"
					$vcheckDigitFinal = "10 (Se traduce a *)"
				};

				return $checkDigit;
			}
		?>
				<div id="verboseContent">
					<?php
						if ($verbose == 1) {
							echo "<br><b>EMC (16 bits)</b><br>EMC Hexadecimal: " . $vipuiFirstHex . "<br>EMC Binario: " . $vipuiFirstBin . "<br>EMC Decimal: " . $vipuiFirstDec . "<br>EMC Binario (5 dígitos): " . $vipuiFirstBinComplete . "<br><br><b>PSN (20 bits)</b><br>PSN Hexadecimal: " . $vipuiLastHex . "<br>PSN Binario: " . $vipuiLastBin . "<br>PSN Decimal: " . $vipuiLastDec . "<br>PSN Decimal (7 dígitos): " . $vipuiLastBinComplete;
						};
					?>
				</div>
				<div id="verboseContentChecksum">
					<?php
						if ($verbose == 1) {
							echo "<br><b>Checksum (ETSI EN 300 175-6)</b><br>Número sin código de control: " . $code . "<br>Suma de dígitos: " . $vcheckDigit . " = <b>" . $vcheckDigitNoMod . "</b><br>Módulo: " . $vcheckDigitNoMod . " mod 11 = " . $vcheckDigitFinal;	
						};
					?>
				</div>
				<div id="verboseContentFinal">
					<?php
						if ($verbose == 1) {
							echo "<br><b>Resultado final</b><br>IPEI: " . $vipuiFirstBinComplete . " " . $vipuiLastBinComplete . " " . $vcheckDigitFinal;
				};
						}
					?>
				</div>
			</form>
			<div id="standard">European Telecommunications Standards Institute (ETSI) - EN 300 175-6 Compliant.</div>
		</fieldset>
	</body>
</html>
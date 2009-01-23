<?php

include_once("../../autentica.inc");
include_once("../../db.inc");

echo "
<head>
<style type='text/css'>

div.titulo {position:absolute;top:5px;left:300px;font-size:200%}

div.textoassunto {position:absolute;top:50px;left:5px}
div.assunto {position:absolute;top:50px;left:70px}

div.textocorpo {position:absolute;top:100px;left:5px}
div.corpo {position:absolute;top:100px;left:70px}

div.baixo {position:absolute;top:300px;left:250px}
</style>
<script language='JavaScript' type='text/javascript'>
function verifica() {
		var assunto = document.getElementById('assunto').value;
		var corpo = document.getElementById('corpo').value;
		if (assunto === '') {
			alert('Digite um assunto');
			document.getElementById('assunto').focus();
			return false;
		}
		
		if (corpo === '') {
			alert('Digite o texto da mensagem');
			document.getElementById('corpo').focus();
			return false;
		}
		return true;
}
</script>

</head>

<body>

<div class=\"titulo\">
E-mail
</div>

<form action='enviar_email-final.php' name='email' id='email' method='POST' onSubmit='return verifica();'>

<div class=\"textoassunto\">
Assunto:
</div>

<div class=\"assunto\">
<input type='text' name='assunto' id='assunto' size='70'><br />
</div>

<div class=\"textocorpo\">
Texto:
</div>

<div class=\"corpo\">
<textarea name='corpo' id='corpo' cols=70 rows=10></textarea>
</div>

<div class=\"baixo\">
";
$sql  = "select id from supervisores where email != ''";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar a tabela supervisores");
while(!$resultado->EOF) {
	$id = $resultado->fields['id'];
	echo $para = $_POST[$id];
	// echo $id . " -> " . $para;
	if (!empty($para)) {
		echo "<input type='hidden' name='$id' value='$para'>";
	}
	$resultado->MoveNext();
}

echo "
<input type='submit' name='submit' value='Envia e-mail para os supervisores selecionados'>
</div>

</form>
</body>
";

?>
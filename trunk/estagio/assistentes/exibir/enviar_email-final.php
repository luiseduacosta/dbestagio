<?php

include_once("../../db.inc");

$assunto = $_POST['assunto'];
$corpo = $_POST['corpo'];

// print_r($_POST[$id]);

$sql  = "select supervisores.id, supervisores.nome, supervisores.email from supervisores ";
$sql .= " where supervisores.email != ''";

// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar a tabela supervisores");
while(!$resultado->EOF) {
		$id = $resultado->fields['id'];
		$nome = $resultado->fields['nome'];
		// $email = $resultado->fields['email'];
		$to = $_POST[$id];
		// echo "Para " . $id . " " . $to . "<br>";
		if (!empty($to)) {
			// $to = "luis@localhost"; // para testes
			// echo $to . "<br>";
			$destino = "$nome <$to> \r\n";
			$cabecalho  = "From: Coordenação de Estágio <estagio@ess.ufrj.br> \r\n";
			$cabecalho .= "Replay-To: estagio@ess.ufrj.br \r\n";
			$cabecalho .= "X-Mailer: PHP/" . phpversion();
			
			// $email = "estagio@ess.ufrj.br";

			// E-mail para o supervisor
			mail ($destino,$assunto,$corpo,$cabecalho);
			// E-mail para a Coordenação
//			mail ("estagio@ess.ufrj.br",$assunto,$corpo,"From:$destino");
			echo "E-mail enviado para $nome" . "<br>";
		}
		$resultado->MoveNext();
	}
exit;

?>
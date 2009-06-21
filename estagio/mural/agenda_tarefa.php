<?php

// include_once("../autoriza.inc");

$id_instituicao = isset($_REQUEST['id_instituicao']) ? $_REQUEST['id_instituicao'] : NULL;

include_once("../db.inc");
include_once("../setup.php");

require(MAILER."class.phpmailer.php");
$mail = new PHPMailer();

$sql_estagio  = "select mural_estagio.id, instituicao, vagas, ";
$sql_estagio .= " dataSelecao, horarioSelecao, dataInscricao, ";
$sql_estagio .= " datafax, email ";
$sql_estagio .= " from mural_estagio ";
$sql_estagio .= " where mural_estagio.periodo = '" . PERIODO_ATUAL . "' ";
$sql_estagio .= " and mural_estagio.id = '$id_instituicao' ";
$sql_estagio .= " order by instituicao, id";

// echo $sql_estagio . "<br />";

$resultado = $db->Execute($sql_estagio);

if($resultado === false) die ("Não foi possível consultar a tabela mural_estagio");

$id_instituicao = $resultado->fields['id'];
$instituicao = $resultado->fields['instituicao'];
$datafax = $resultado->fields['datafax'];
$email = $resultado->fields['email'];
		
// Passo do formato aaaa/mm/dd para dd/mm/aaaa
$data_selecao = date('Ymd',strtotime($resultado->fields['dataSelecao']));

// Passo do formato aaaa/mm/dd para dd/mm/aaaa
$data_encerramento = date("Ymd",strtotime($resultado->fields['dataInscricao']));
// echo "Data encerramento: " . $data_encerramento . "<br>";

$data_hoje = date("Ymd");
$hoje = date('Y-m-d'); // formato mysql
// echo "Data hoje: " . date("Ymd") . "<br>";

// Enviar dois emails: para a instituicao e para estagio
if(empty($email)) $email = "estagio@ess.ufrj.br";
		
// Enviar email somente se ainda nao foi enviado
// echo $datafax . "<br>";
	
if($datafax == 0) {
	if($data_encerramento < $data_hoje) {
		$arquivo = "estagio" . $id_instituicao . ".pdf";
		$camino = TMP.$arquivo;
		// echo "Enviar e-mail com anexo $arquivo<br>";
		$anexo = 1; // flag para o arquivo imprime
		require("imprime.php");
		$mail->From     = "estagio@ess.ufrj.br";
		$mail->FromName = "Coordenação de Estágio e Extensão da ESS/UFRJ";
		$mail->Host     = "localhost";
		$mail->Mailer   = "smtp";
		$mail->CharSet  = "iso8859-1";
		$mail->IsHTML(true);
		$mail->Subject   = "ESS/UFRJ: Inscrições para estágio em $instituicao";
		$mail->Body      = "Anexo lista de alunos inscritos para seleção de estágio. Favor, confirmar recebimento.";
		$mail->AddAddress($email, $instituicao);
		// $mail->AddAddress('uy_luis@hotmail.com', $instituicao);
		$mail->AddBCC('estagio@ess.ufrj.br');
		$mail->ConfirmReadingTo = "estagio@ess.ufrj.br";
		$mail->AddAttachment("../tmp/$arquivo");
		if(!$mail->Send()) {
    			echo "There has been a mail error sending <br>";
		} else {
	    	// echo "Email enviado <br>";
	    		$sql_datafax = "update mural_estagio set datafax = '$hoje' where id=$id_instituicao";
			// echo $sql . "<br>";
	    		$resultado_datafax = $db->Execute($sql_datafax);
			if($resultado_datafax === false) die ("Não foi possível atualizar a tabela mural_estagio");	
		}
		// Clear all addresses and attachments for next loop
		$mail->ClearAddresses();
		$mail->ClearAttachments();
		}
	}

echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=ver_cada.php?id_instituicao=$id_instituicao'>";

exit;

?>
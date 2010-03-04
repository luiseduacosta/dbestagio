<?php

include_once("../db.inc");
include_once("../setup.php");

$registro = isset($_REQUEST['registro']) ? $_REQUEST['registro'] : NULL;
$instituicao = isset($_REQUEST['instituicao']) ? htmlspecialchars($_REQUEST['instituicao']) : NULL;
$id_instituicao = isset($_REQUEST['id_instituicao']) ? $_REQUEST['id_instituicao'] : NULL;

// echo "Registro: " . $registro . "<br>";
// echo "Id instituição : " . $id_instituicao . "<br>";
// echo "Instituicao: " . $instituicao;

if (!ctype_digit($registro)) {
    die("Digite somente números ou tente com outro navegador: <a href=\"http://br.mozdev.org\">Firefox</a>");
}

// Busco o registro entre os alunos estagiarios
$sql_estagiarios  = "select id, nome, telefone, celular, email from alunos ";
$sql_estagiarios .= " where registro='$registro'";
// echo $sql_estagiarios . "<br>";

$resultado_estagiarios = $db->Execute($sql_estagiarios);
if ($resultado_estagiarios === false) die ("Não foi possível consultar a tabela alunos");
$quantidade_estagiarios = $resultado_estagiarios->RecordCount();
// echo "Alunos estagiarios: " . $quantidade_estagiarios . "<br>";

if ($quantidade_estagiarios === 0) {
	$sql = "select id from alunosNovos where registro='$registro'";
	$resultado = $db->Execute($sql);
	$quantidade = $resultado->RecordCount();
	if ($quantidade > 0) {
		// die("Aluno novo já cadastrado como aluno novo");
		echo "<meta http-equiv='refresh' content='1; URL=mural-alunos_modifica.php?registro=$registro&id_aluno=$id_aluno&id_instituicao=$id_instituicao&aluno=0'>";
		exit;
	} else {
		// echo "Inserir alunosNovos<br>";
		// die("Aluno novo ainda nao cadastrado em alunos novos");
		// header("Location:cadastro.php?registro=$registro&id_instituicao=$id_instituicao");
		// die($id_instituicao);
		echo "<meta http-equiv='refresh' content='1; URL=cadastro.php?registro=$registro&id_instituicao=$id_instituicao'>";
		die;
	}
	// Se está cadastrado busco se já está estagiando
} elseif ($quantidade_estagiarios > 0) {
	// die("Aluno já cadastrado como estagiario");
	$periodo_atual = PERIODO_ATUAL;
	$periodo_anterior = explode("-",$periodo_atual);
	// print_r($periodo_anterior);
	$digito_final = $periodo_anterior[1];

	if ($digito_final == 2)
	$periodo_novo = $periodo_anterior[0] .  "-1";

	if ($digito_final == 1)
	$periodo_novo = $periodo_anterior[0] - 1 .  "-2";

	/**/
	// Capturo a instituicao atual e anterior
	$sql_periodos = "select periodo, nivel, id_instituicao, id_supervisor
	, instituicao, supervisores.nome
	from estagiarios
	inner join estagio on estagiarios.id_instituicao = estagio.id
	left join supervisores on estagiarios.id_supervisor = supervisores.id
	where registro = '$registro' and periodo < '" . PERIODO_ATUAL . "' order by periodo desc";
	// echo $sql_periodos . "<br>";
	
	$resultado_periodo = $db->Execute($sql_periodos);
	$quantidade = $resultado_periodo->RecordCount();
	// echo $quantidade . "<br>";
	if ($quantidade === 0) die("Error: Aluno estagiario sem estágio? Informar para <a href='mailto:estagio@ess.ufrj.br?Subject=Error: estagiario (DRE: $registro) sem estagio '>estagio@ess.ufrj.br</a>");
	$i = 1;
	while (!$resultado_periodo->EOF) {
		$periodo = $resultado_periodo->fields['periodo'];
		$nivel = $resultado_periodo->fields['nivel'];
		// echo $periodo . " " . $nivel . " Atual: " . PERIODO_ATUAL . "<br>";

		$id_instituicao_periodo_anterior = $resultado_periodo->fields['id_instituicao'];
		$instituicao_periodo_anterior = $resultado_periodo->fields['instituicao'];

		$id_supervisor_periodo_anterior = $resultado_periodo->fields['id_supervisor'];
		$supervisor_periodo_anterior = $resultado_periodo->fields['nome'];

		$nivel_periodo_anterior = $resultado_periodo->fields['nivel'];
		$periodo_anterior = $resultado_periodo->fields['periodo'];

		$i++;

		// Somente o primeiro periodo anterior ao PERIODO ATUAL
		if ($i > 1) {
			break;
			// die("Registros capturados");
		}
			
		$resultado_periodo->MoveNext();
	}

	// var_dump($nivel_periodo_anterior);
	if ($nivel_periodo_anterior == 1 or $nivel_periodo_anterior == 3) {
		echo $texto = "<h2>Atenção: aluno que cursou estágio I ou III no período anterior</h2><p style='text-align:justify; background-color:#e7e1ae'>Prezada(o) aluna(o):<br><br>Clicando no botão embaixo sua solicitação será processada, ainda que, pelas normas vigentes, alunos que cursaram estágio I ou III no último período devem requerer autorização especial na Coordenação de Estágio e Extensão para serem habilitados a trocar de estágio antes do cumprimento de dois períodos consecutivos numa mesma instituição.<br><br>Em caso de dúvida entre em contacto através do e-mail <a href='mailto:estagio@ess.ufrj.br'>estagio@ess.ufrj.br</a></p>";
		echo "
		<form action='mural-alunos_modifica.php' method='post'>
		<input type='submit' name='submit' value='Confirmar inscrição'>
		<input type='hidden' name='registro' value='$registro'>
		<input type='hidden' name='id_aluno' value='$id_aluno'>
		<input type='hidden' name='aluno' value='1'>
		<input type='hidden' name='id_instituicao' value='$id_instituicao'>
		</form>
			";
		die;
	} else {
		echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=mural-alunos_modifica.php?id_aluno=$id_aluno&registro=$registro&id_instituicao=$id_instituicao&aluno=1'>";
		die;
	}
	exit;
}

?>

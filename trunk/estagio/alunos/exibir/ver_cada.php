<?php

include_once("../../setup.php");
$senha = $_COOKIE['usuario_senha'];
if ($senha) $logado = 1;

$periodo = isset($_REQUEST['periodo']) ? $_REQUEST['periodo'] : NULL;
$periodo_atual = isset($_REQUEST['periodo_atual']) ? $_REQUEST['periodo_atual'] : PERIODO_ATUAL;
/*
 if (empty($periodo_atual)) {
 $periodo_atual = PERIODO_ATUAL;
 }
 */
$botao  = $_POST['botao'];
$indice = $_REQUEST['indice'];
$id_aluno = isset($_REQUEST['id_aluno']) ? $_REQUEST['id_aluno'] : NULL;
$registro = isset($_REQUEST['registro']) ? $_REQUEST['registro'] : NULL;
// echo $registro . "<br>";

// echo $id_aluno . "<br>";

if (empty($id_aluno)) {
	$sql = "select id from alunos where registro = $registro";
	$resultado = $db->Execute($sql);
	$id_aluno = $resultado->fields['id'];
}

$origem = $_SERVER['HTTP_REFERER'];
// echo $_SERVER['PHP_SELF'] . " " . $origem . "<br>";

// Verifico se o usuario esta logado como administrador
$usuario_senha = $_REQUEST['usuario_senha'];
if ($usuario_senha) {
	$logado = 1;
	// echo "Usuario logado " . "<br>";
}

// echo "Registro " . $registro . "<br>";
if ($registro) {
    $sql_estagiario  = "SELECT id, id_aluno, registro ";
    $sql_estagiario .= " from estagiarios ";
    $sql_estagiario .= " where registro = '$registro'";
    // echo $sql_estagiario . "<br>";
    $res_estagiario = $db->Execute($sql_estagiario);
    if ($res_estagiario === false) die ("Não foi possível consultar a tabela estagiarios");
    $quantidade_estagiarios = $res_estagiario->RecordCount();
    // echo $quantidade_estagiarios . "<br>";
    if ($quantidade_estagiarios === 0) {
	echo "Aluno sem estagio. Registro deve ser excluido." . "<br>";
	$sql_aluno = "select id from alunos where registro = '$registro'";
	// echo $sql_aluno . "<br>";
	$res_aluno = $db->Execute($sql_aluno);
	if ($res_aluno === false) die ("Não foi possível consultar a tabela alunos");
	$id_aluno = $res_aluno->fields['id'];
	// echo $id_aluno . "<br>";
	// die();	
	echo "<meta HTTP-EQUIV='refresh' content='2,URL=../cancelar/ver_cancela.php?id_aluno=$id_aluno'>";
	exit;
    }
}

$sql  = "SELECT alunos.id, alunos.registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, ";
$sql .= " cpf, identidade, orgao, nascimento, endereco, cep, municipio, bairro, alunos.observacoes ";
$sql .= " from estagiarios ";
$sql .= " left join alunos on estagiarios.registro = alunos.registro ";
if ($periodo) $sql .= " where periodo='$periodo' ";
$sql .= " group by estagiarios.registro ";
$sql .= " order by nome ";
// echo $sql . "<br>";

// Calculo a quantidade de registros
$resultado_total = $db->Execute($sql);
if ($resultado_total === false) die ("Não foi possível consultar a tabela alunos");
$ultimo = $resultado_total->RecordCount();

// Barra de navegacao superior
switch ($botao) {
	case "primeiro":
		$indice = 0;
		break;
	case "menos_10":
		$indice = $indice -10;
		if($indice < 0)
		$indice = $ultimo - 1; // $ultimo-1;
		break;
	case "retroceder":
		$indice--;
		if($indice <= 0)
		$indice = $ultimo - 1; // $ultimo-1;
		break;
	case "avancar":
		$indice++;
		if($indice == $ultimo)
		$indice = 0;
		break;
	case "mais_10":
		$indice = $indice + 10;
		if($indice >= $ultimo)
		$indice = 0;
		break;
	case "ultimo";
	$indice = $ultimo - 1;
	break;
	default:
		$indice = 0;
		break;
}

if ($debug == 1) echo $_SERVER['HTTP_REFERER'];

// Se foi chamado desde outro lugar atraves de um id_aluno calculo o inicio da contagem
if (!empty($id_aluno)) {
	// echo "Id aluno " . $id_aluno . "<br>";
	$resultado_sql_lugar = $db->Execute($sql);
	if ($resultado_sql_lugar === false) die ("Não foi possível consultar a tabela alunos");
	$j = 0;
	while (!$resultado_sql_lugar->EOF) {
		$lugar_aluno = $resultado_sql_lugar->fields['id'];
		$lugar_registro = $resultado_sql_lugar->fields['registro'];
		// echo $j . " " . $lugar_aluno . " " . $lugar_registro . " " . $id_aluno . "<br>";
		if ($lugar_aluno === $id_aluno || $lugar_registro == $id_aluno) {
			$lugar_na_tabela = $j;
			$indice = $j;
			// echo $j . "<br>";
			break;
		}
		$resultado_sql_lugar->MoveNext();
		$j++;
	}
	// if ($indice) die("Aluno estagiario sem estagio: escolha: a) <a href='../cancelar/cancela.php?id_aluno=$id_aluno'>Excluir</a> o aluno ou; b) <a href='../atualizar/atualiza.php?id_aluno=$id_aluno'>Inserir um est�gio</a>") . "<br>";
}

// echo $sql . "<br>";
// echo $indice . "<br>";

$resultado = $db->SelectLimit($sql,1,$indice);
if ($resultado === false) die ("Nao foi possivel consultar a tabela alunos");
while (!$resultado->EOF) {
	$aluno_id        = $resultado->fields['id'];
	$registro        = $resultado->fields['registro'];
	$nome            = $resultado->fields['nome'];
	$codigo_telefone = $resultado->fields['codigo_telefone'];
	$telefone        = $resultado->fields['telefone'];
	$codigo_celular  = $resultado->fields['codigo_celular'];
	$celular         = $resultado->fields['celular'];
	$email           = strtolower($resultado->fields['email']);
	$cpf             = $resultado->fields['cpf'];
	$identidade      = $resultado->fields['identidade'];
	$orgao           = $resultado->fields['orgao'];
	$nascimento      = $resultado->fields['nascimento'];
	$endereco        = $resultado->fields['endereco'];
	$cep             = $resultado->fields['cep'];
	$bairro          = $resultado->fields['barrio'];
	$municipio       = $resultado->fields['municipio'];
	$observacoes     = $resultado->fields['observacoes'];
	// echo $observacoes . "<br>";
	$resultado->MoveNext();

	// Pego a informacao sobre o ingresso na escola (introducao ao seso)
	$sql_intro = "select min(periodo) as periodo from alunos_ingresso where registro='$registro' group by periodo";
	$res_intro = $db->Execute($sql_intro);
	$periodo_intro = $res_intro->fields['periodo'];

	// Calculo o periodo atual
	if ($periodo_atual and $periodo_intro) {
		$tempo0 = explode("-",$periodo_intro);
		$tempo_inicial = $tempo0[0];
		$periodo_inicial = $tempo0[1];
		$tempo1 = explode("-",$periodo_atual);
		$tempo_final = $tempo1[0];
		$periodo_final = $tempo1[1];
		$tempo_cursado = ($tempo_final - $tempo_inicial);

		// echo $tempo_cursado . "<br>";

		if ($periodo_inicial < $periodo_final) {
			$tempo_cursado = ($tempo_cursado * 2) + 2;
		} elseif ($periodo_inicial > $periodo_final) {
			$tempo_cursado = ($tempo_cursado * 2);
		} elseif ($periodo_inicial === $periodo_final) {
			$tempo_cursado = ($tempo_cursado * 2) + 1;
		}

		// echo "<br>";
		// echo $tempo_cursado . "<br>";
	}

	// Pego a informacao sobre os estagios cursados
	$sql_estagiario = "select id, tc, nivel, turno, periodo, nota, ch, id_instituicao, id_supervisor, id_professor from estagiarios where id_aluno = $aluno_id order by periodo";
        // echo $sql_estagiario . "<br>";
	$resultado_estagiario = $db->Execute($sql_estagiario);
	if ($resultado_estagiario === false) die ("Nao foi possivel consultar a tabela estagiarios");
	$i = 0;
	while (!$resultado_estagiario->EOF) {
		$id_estagiario      = $resultado_estagiario->fields['id'];
		$tc                 = $resultado_estagiario->fields['tc'];
		$nivel              = $resultado_estagiario->fields['nivel'];
		$turno              = $resultado_estagiario->fields['turno'];
		$estagiario_periodo = $resultado_estagiario->fields['periodo'];
		$nota               = $resultado_estagiario->fields['nota'];
		$ch                 = $resultado_estagiario->fields['ch'];
		$id_instituicao     = $resultado_estagiario->fields['id_instituicao'];
		$id_supervisor      = $resultado_estagiario->fields['id_supervisor'];
		$id_professor       = $resultado_estagiario->fields['id_professor'];

		$resultado_estagiario->MoveNext();

		if (empty($id_instituicao)) {
			$id_instituicao = "0";
			$instituicao = "Sem dados";
		} else {
			$sql_estagio = "select id, instituicao from estagio where id = $id_instituicao";
                        // echo $sql_estagio . "<br>";
			$resposta_estagio = $db->Execute($sql_estagio);
			$id          = $resposta_estagio->fields['id'];
			$instituicao = $resposta_estagio->fields['instituicao'];
                        // echo $id . ' ' . $instituicao . "<br>";
		}

		if (empty($id_supervisor)) {
			$id_supervisor = "0";
			$supervisor_nome = "Sem dados";
		} else {
			$sql_supervisor  = "select id, cress, nome, email ";
			$sql_supervisor .= "from supervisores ";
			$sql_supervisor .= "where supervisores.id = $id_supervisor";
			$resultado_supervisor = $db->Execute($sql_supervisor);
			while (!$resultado_supervisor->EOF) {
				$supervisor_nome  = $resultado_supervisor->fields['nome'];
				$supervisor_cress = $resultado_supervisor->fields['cress'];
				$supervisor_email = $resultado_supervisor->fields['email'];

				$resultado_supervisor->MoveNext();
			}
		}

		// Professor
		$sql_professor = "select nome from professores where id = $id_professor";
		$resultado_professor = $db->Execute($sql_professor);
		if ($resultado_professor === false) die ("Nao foi possivel consultar a tabela professores");
		$professor_nome = $resultado_professor->fields['nome'];
		 
		$historico_estagio[$i]['nivel']          = $nivel;
		$historico_estagio[$i]['id_estagiario']  = $id_estagiario;
		$historico_estagio[$i]['tc']             = $tc;
		$historico_estagio[$i]['turno']          = $turno;
		$historico_estagio[$i]['periodo']        = $estagiario_periodo;
		$historico_estagio[$i]['nota']           = $nota;
		$historico_estagio[$i]['ch']             = $ch;
		$historico_estagio[$i]['id_instituicao'] = $id_instituicao;
		$historico_estagio[$i]['instituicao']    = $instituicao;
		$historico_estagio[$i]['id_supervisor']  = $id_supervisor;
		$historico_estagio[$i]['supervisor']     = $supervisor_nome;
		$historico_estagio[$i]['id_professor']   = $id_professor;
		$historico_estagio[$i]['professor']      = $professor_nome;

		$i++;

		// TCC
		$sql_tcc  = "select num_monografia ";
		$sql_tcc .= " from tcc_alunos ";
		$sql_tcc .= "where registro = '$registro'";
		// echo $sql_tcc . "<br>";
		$resultado_tcc = $db->Execute($sql_tcc);
		if ($resultado_tcc === false) die ("Nao foi possivel consultar a tabela tcc_alunos");
		$id_tcc = $resultado_tcc->fields['num_monografia'];

		$n = 1;
		if ($id_tcc) {
			$id_tcc = $resultado_tcc->fields['num_monografia'];

			$sql_monografia  = "select codigo, titulo, catalogo, periodo, nome ";
			$sql_monografia .= "from monografia ";
			$sql_monografia .= "inner join professores on monografia.num_prof = professores.id ";
			$sql_monografia .= "where codigo = '$id_tcc'";

			$resultado_monografia = $db->Execute($sql_monografia);
				
			$tcc['id'] = $resultado_monografia->fields['codigo'];
			$tcc['periodo'] = $resultado_monografia->fields['periodo'];
			$tcc['titulo'] = $resultado_monografia->fields['titulo'];
			$tcc['catalogo'] = $resultado_monografia->fields['catalogo'];
			$tcc['professor'] = $resultado_monografia->fields['nome'];

			$n++;

			// echo $tcc['titulo'] . " ". $tcc['professor'] . "<br>";
		}
	}
}

// Pego a informacao sobre as turma de alunos
$sqlturma = "select id, periodo from estagiarios group by periodo";
// echo $sqlturma . "<br>";
$res_turma = $db->Execute($sqlturma);
if ($res_turma === false) die ("Não foi possível consultar a tabela estagiarios");
while (!$res_turma->EOF) {
	$periodos[] = $res_turma->fields['periodo'];
	$res_turma->MoveNext();
}

$smarty = new Smarty_estagio;
$smarty->assign("logado", $logado);
$smarty->assign("periodo",$periodo);
$smarty->assign("origem",$origem);
$smarty->assign("indice",$indice);
$smarty->assign("num_aluno",$aluno_id);
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("id_supervisor",$id_supervisor);
$smarty->assign("registro",$registro);
$smarty->assign("nome",$nome);
$smarty->assign("codigo_telefone",$codigo_telefone);
$smarty->assign("telefone",$telefone);
$smarty->assign("codigo_celular",$codigo_celular);
$smarty->assign("celular",$celular);
$smarty->assign("email",$email);
$smarty->assign("cpf",$cpf);
$smarty->assign("identidade",$identidade);
$smarty->assign("orgao",$orgao);
$smarty->assign("nascimento",$nascimento);
$smarty->assign("endereco",$endereco);
$smarty->assign("cep",$cep);
$smarty->assign("bairro",$bairro);
$smarty->assign("municipio",$municipio);
$smarty->assign("observacoes",$observacoes);
$smarty->assign("periodo_intro",$periodo_intro);
$smarty->assign("tempo_cursado",$tempo_cursado);
$smarty->assign("historico_estagio",$historico_estagio);
$smarty->assign("periodos",$periodos);
$smarty->assign("tcc",$tcc);
$logado;

$smarty->display("alunos-exibir_ver_cada.tpl");

exit;

?>

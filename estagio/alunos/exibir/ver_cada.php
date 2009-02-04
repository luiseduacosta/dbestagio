<?php

// include_once("../../autentica.inc");

$origem = $_SERVER['HTTP_REFERER'];
// echo $_SERVER['PHP_SELF'] . " " . $origem . "<br>";

include_once("../../db.inc");
include_once("../../setup.php");

$botao  = $_POST['botao'];
$indice = $_REQUEST['indice'];
$id_aluno = $_REQUEST['id_aluno'];

// Verifico se o usuario esta logado como administrador
$usuario_senha = $_REQUEST['usuario_senha'];
if ($usuario_senha) {
    $logado = 1;
    // echo "Usuario logado " . "<br>";
}
// Calculo a quantidade de registros
$sql_total = "select id from alunos";
$resultado_total = $db->Execute($sql_total);
if($resultado_total === false) die ("N�o foi poss�vel consultar a tabela alunos");
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

if($debug == 1)
    echo $_SERVER['HTTP_REFERER'];

// Se foi chamado desde outro lugar atraves de um id_aluno calculo o inicio da contagem
if(!empty($id_aluno)) {
    // echo "Id aluno " . $id_aluno . "<br>";
    $sql_lugar = "select id, registro from alunos order by nome";
    $resultado_sql_lugar = $db->Execute($sql_lugar);
    if($resultado_sql_lugar === false) die ("N�o foi poss�vel consultar a tabela alunos");
    $j = 0;
    while(!$resultado_sql_lugar->EOF) {
    	$lugar_aluno = $resultado_sql_lugar->fields['id'];
    	$lugar_registro = $resultado_sql_lugar->fields['registro'];
        if ($lugar_aluno === $id_aluno || $lugar_registro == $id_aluno) {
            $lugar_na_tabela = $j;
            $indice = $j;
        }
    	$resultado_sql_lugar->MoveNext();
    	$j++;
    }
}

$sql  = "SELECT id, registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, ";
$sql .= " cpf, identidade, orgao, nascimento, endereco, cep, municipio, bairro, observacoes ";
$sql .= " from alunos order by nome";
if($debug == 1)
    echo $sql . "<br>";

$resultado = $db->SelectLimit($sql,1,$indice);
if($resultado === false) die ("Nao foi possivel consultar a tabela alunos");
while(!$resultado->EOF) {
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

    $sql_estagiario = "select tc, nivel, turno, periodo, nota, ch, id_instituicao, id_supervisor, id_professor from estagiarios where id_aluno=$aluno_id order by periodo";
    $resultado_estagiario = $db->Execute($sql_estagiario);
    if($resultado_estagiario === false) die ("Nao foi possivel consultar a tabela estagiarios");
    $i = 0;
    while(!$resultado_estagiario->EOF) {
		$tc              = $resultado_estagiario->fields['tc'];
        $nivel           = $resultado_estagiario->fields['nivel'];
        $turno           = $resultado_estagiario->fields['turno'];
        $periodo         = $resultado_estagiario->fields['periodo'];
		$nota            = $resultado_estagiario->fields['nota'];
		$ch              = $resultado_estagiario->fields['ch'];
        $id_instituicao  = $resultado_estagiario->fields['id_instituicao'];
        $id_supervisor   = $resultado_estagiario->fields['id_supervisor'];
        $id_professor    = $resultado_estagiario->fields['id_professor'];
        
        $resultado_estagiario->MoveNext();

        if(empty($id_instituicao)) {
        	$id_instituicao = "0";
        	$instituicao = "Sem dados";
        } else {
    	    $sql_estagio = "select id, instituicao from estagio where id=$id_instituicao";
    	    $resposta_estagio = $db->Execute($sql_estagio);
    	    while(!$resposta_estagio->EOF) {
    	    	$id          = $resposta_estagio->fields['id'];
    	    	$instituicao = $resposta_estagio->fields['instituicao'];

    	    	$resposta_estagio->MoveNext();
    	    }
        }
    
        if(empty($id_supervisor)) {
            $id_supervisor = "0";
            $supervisor_nome = "Sem dados";
	} else {
    	    $sql_supervisor  = "select id, cress, nome, email ";
    	    $sql_supervisor .= "from supervisores ";
    	    $sql_supervisor .= "where supervisores.id=$id_supervisor";
    	    $resultado_supervisor = $db->Execute($sql_supervisor);
    	    while(!$resultado_supervisor->EOF) {
    	    	$supervisor_nome  = $resultado_supervisor->fields['nome'];
    	    	$supervisor_cress = $resultado_supervisor->fields['cress'];
    	    	$supervisor_email = $resultado_supervisor->fields['email'];

    	    	$resultado_supervisor->MoveNext();
    	    }
	}

    // Professor
    $sql_professor = "select nome from professores where id=$id_professor";
	$resultado_professor = $db->Execute($sql_professor);
   	if($resultado_professor === false) die ("Nao foi possivel consultar a tabela professores");
	    $professor_nome = $resultado_professor->fields['nome'];
		
		$historico_estagio[$i]['nivel']          = $nivel;
		$historico_estagio[$i]['tc']             = $tc;
		$historico_estagio[$i]['turno']          = $turno;
		$historico_estagio[$i]['periodo']        = $periodo;
		$historico_estagio[$i]['nota']           = $nota;
		$historico_estagio[$i]['ch']             = $ch;
		$historico_estagio[$i]['id_instituicao'] = $id_instituicao;
		$historico_estagio[$i]['instituicao']    = $instituicao;
		$historico_estagio[$i]['id_supervisor']  = $id_supervisor;
		$historico_estagio[$i]['supervisor']     = $supervisor_nome;
		$historico_estagio[$i]['id_professor']   = $id_professor;
		$historico_estagio[$i]['professor']      = $professor_nome;	
		$i++;
	    
		}
    }
   	
    // TCC
    $sql_tcc  = "select num_monografia "; 
    $sql_tcc .= " from tcc_alunos ";
    $sql_tcc .= "where registro = '$registro'";
    // echo $sql_tcc . "<br>";    
    $resultado_tcc = $db->Execute($sql_tcc);
    if($resultado_tcc === false) die ("Nao foi possivel consultar a tabela tcc_alunos");
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

$smarty = new Smarty_estagio;
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
$smarty->assign("historico_estagio",$historico_estagio);
$smarty->assign("tcc",$tcc);

$smarty->assign("logado",$logado);

$smarty->display("alunos-exibir_ver_cada.tpl");

exit;

?>
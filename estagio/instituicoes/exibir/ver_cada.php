<?php

include_once("../../autoriza.inc");
// include_once("../../db.inc");
include_once("../../setup.php");

$id_instituicao = isset($_REQUEST['id_instituicao']) ? $_REQUEST['id_instituicao'] : NULL;
$id_supervisor = isset($_REQUEST['id_supervisor']) ? $_REQUEST['id_supervisor'] : NULL;

$indice = $_REQUEST['indice'];
$submit = $_REQUEST['submit'];
$botao  = $_REQUEST['botao'];

/*
echo "id_instituicao: " . $id_instituicao;
echo " Indice: " . $indice;
echo " Submit: " . $submit;
echo " Botao: " . $botao . "<br>";
*/

// Conto a quantidade de registros
$sql = "select id from estagio";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela estagio");
$num_linhas = $resultado->RecordCount();

$ultimo_registro = $num_linhas - 1;

switch($botao)
{
    case "primeiro":
	$indice = 0;
	break;

    case "menos_1";
	$indice--;
	if($indice == 0)
	    $indice = $num_linhas - 1;
	break;

    case "menos_10":
	$indice = $indice - 10;
	if($indice < 0)
	    $indice = $ultimo_registro - abs($indice);
	break;

    case "mais_1":
	$indice++;
	if($indice == $num_linhas)
	    $indice = 0;
	break;

    case "mais_10":
	$indice = $indice + 10;
	if($indice > $ultimo_registro)
	    $indice = $indice - $num_linhas;
	break;

    case "ultimo":
	$indice = $ultimo_registro;
	break;
}

// Rotina para acrescentar um supervisor
if (!empty($id_supervisor)) {
	// echo "Acrescentar supervisor<br>";
	$sql = "insert into inst_super (id_supervisor,id_instituicao) values('$id_supervisor','$id_instituicao')";
	// echo $sql . "<br>";
	$resultado = $db->Execute($sql);
	if($resultado === false) die ("Não foi possível inserir dados na tabela inst_super");	
} else {
	NULL; // echo "Nada: " . $_POST[num_instituicao] . "<br>";
}

// Busco o lugar da instituicao na tabela
if (!empty($id_instituicao)) {
	$sql_instituicao = "select id from estagio order by instituicao";
	$resultado_instituicao = $db->Execute($sql_instituicao);
	if ($resultado_instituicao === false) die ("Não foi possível consultar a tabela estagio");
	$lugar = 0;
	while (!$resultado_instituicao->EOF)	{
		$num_instituicao = $resultado_instituicao->fields['id'];
		if ($num_instituicao === $id_instituicao) {
			$indice = $lugar;
		}
		$lugar++;
		$resultado_instituicao->MoveNext();
	}
}

$sql_estagio  = "select e.id, e.instituicao, e.endereco, e.cep, ";
$sql_estagio .= "e.telefone, e.fax, e.beneficio, e.fim_de_semana, ";
$sql_estagio .= "e.area as id_area, e.convenio, a.area "; 
$sql_estagio .= "from estagio as e ";
$sql_estagio .= "left outer join areas_estagio as a ";
$sql_estagio .= "on e.area=a.id ";
$sql_estagio .= "order by instituicao";
// echo $sql_estagio . '<br';
$res_estagio = $db->SelectLimit($sql_estagio,1,$indice);
// echo $indice . "<br>";
while (!$res_estagio->EOF) {
    $id            = $res_estagio->fields['id'];
    $instituicao   = $res_estagio->fields['instituicao'];
    $endereco      = $res_estagio->fields['endereco'];
    $cep           = $res_estagio->fields['cep'];
    $telefone      = $res_estagio->fields['telefone'];
    $fax           = $res_estagio->fields['fax'];
    $beneficios    = $res_estagio->fields['beneficio'];
    $fim_de_semana = $res_estagio->fields['fim_de_semana'];
    $id_area       = $res_estagio->fields['id_area'];
    $area          = $res_estagio->fields['area'];
    $convenio      = $res_estagio->fields['convenio'];
    
    // Seleciono a turma das instituicoes
    $sql_periodo = "select max(periodo) as periodo from estagiarios where id_instituicao=$id";
	// echo $sql_periodo . "<br>";	
    $resultado = $db->Execute($sql_periodo);
    if ($resultado === false) die ("Não foi possível consultar a tabela estagiarios");
    while (!$resultado->EOF) {
		$periodo = $resultado->fields['periodo'];
		$resultado->MoveNext();
    }

	// Seleciono os supervisores
    $sql  = "select s.id, s.cress, s.nome from supervisores as s, estagio as e, inst_super as j ";
    $sql .= "where s.id=j.id_supervisor and e.id=j.id_instituicao and e.id=$id order by s.nome";
    $resultado = $db->Execute($sql);
    if ($resultado === false) die ("Não foi possível consultar a tabela supervisores");
    $i = 0;
    while (!$resultado->EOF) {
		$inst_supervisores[$i]["id"]    = $resultado->fields["id"];
		$inst_supervisores[$i]["cress"] = $resultado->fields["cress"];
		$inst_supervisores[$i]["nome"]  = $resultado->fields["nome"];
		$i++;
		$resultado->MoveNext();
    }

    // Seleciona os professores 
    $sql_professor  = "select professores.id, professores.nome, max(estagiarios.periodo) as periodo from estagiarios ";
    $sql_professor .= " inner join professores on estagiarios.id_professor = professores.id "; 
    $sql_professor .= " where estagiarios.id_instituicao = $id";
    $sql_professor .= " group by professores.nome ";
    $sql_professor .= " order by nome, periodo ";
    // echo $sql_professor . "<br>";
	$resultado_professor = $db->Execute($sql_professor);
	$i = 0;
	while (!$resultado_professor->EOF) {
		$inst_professores[$i]['id_professor'] = $resultado_professor->fields['id'];
		$inst_professores[$i]['nome'] = $resultado_professor->fields['nome'];
		$inst_professores[$i]['periodo'] = $resultado_professor->fields['periodo'];
		$i++;
		$resultado_professor->MoveNext();
	}
    $res_estagio->MoveNext();
}

// Pego a listagem de todos os supervisores
$sql_supervisores = "select id, nome from supervisores order by nome";
$res_supervisores = $db->Execute($sql_supervisores);
if ($res_supervisores === false) die ("Não foi possível consultar a tabela supervisores");
$i = 0;
while(!$res_supervisores->EOF) {
    $supervisores[$i]['id']   = $res_supervisores->fields['id'];
    $supervisores[$i]['nome'] = $res_supervisores->fields['nome'];
	$i++;
    $res_supervisores->MoveNext();
}

$smarty = new Smarty_estagio;
$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("indice",$indice);
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("id",$id);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("endereco",$endereco);
$smarty->assign("cep",$cep);
$smarty->assign("telefone",$telefone);
$smarty->assign("fax",$fax);
$smarty->assign("beneficios",$beneficios);
$smarty->assign("fim_de_semana",$fim_de_semana);
$smarty->assign("id_area",$id_area);
$smarty->assign("area",$area);
$smarty->assign("convenio",$convenio);
$smarty->assign("turma",$periodo);
$smarty->assign("inst_supervisores",$inst_supervisores);
$smarty->assign("inst_professores",$inst_professores);
$smarty->assign("supervisores",$supervisores);
$smarty->display("instituicao_ver_cada.tpl");

?>
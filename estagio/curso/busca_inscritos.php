<?php
/*
 * Created on 31/05/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

$palavra = isset($_REQUEST['palavra']) ? $_REQUEST['palavra'] : NULL;
$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : nome;

include_once("../setup.php");
include_once("../autentica.inc");

$sql = "select curso_inscricao_supervisor.id, curso_inscricao_supervisor.num_inscricao,curso_inscricao_supervisor.nome, curso_inscricao_supervisor.cress,curso_inscricao_supervisor.email,curso_inscricao_supervisor.codigo_tel,curso_inscricao_supervisor.telefone,curso_inscricao_supervisor.codigo_cel,curso_inscricao_supervisor.celular,curso_inscricao_supervisor.curso_turma, curso_inscricao_instituicao.instituicao, curso_inscricao_instituicao.id as id_instituicao " .
		" from curso_inscricao_supervisor " .
		" join curso_inst_super on curso_inscricao_supervisor.id = curso_inst_super.id_supervisor " .
		" join curso_inscricao_instituicao on curso_inscricao_instituicao.id = curso_inst_super.id_instituicao " .
		" where nome like '%$palavra%' " .
		" order by $ordem";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela curso_inscricao_supervisor");
$quantidade = $resultado->RecordCount();
if ($quantidade === 0) {
    echo "Não há registros com a palavra: $palavra";
    exit;
} else {
    $i = 0;
    while(!$resultado->EOF) {
		$curso_super[$i]['id']             = $resultado->fields['id'];
		$curso_super[$i]['num_inscricao']  = $resultado->fields['num_inscricao'];
		$curso_super[$i]['nome']           = $resultado->fields['nome'];
		$curso_super[$i]['cress']          = $resultado->fields['cress'];
		$curso_super[$i]['email']          = $resultado->fields['email'];
		$curso_super[$i]['codigo_tel']     = $resultado->fields['codigo_tel'];
		$curso_super[$i]['telefone']       = $resultado->fields['telefone'];
		$curso_super[$i]['codigo_cel']     = $resultado->fields['codido_cel'];
		$curso_super[$i]['celular']        = $resultado->fields['celular'];		
		$curso_super[$i]['turma']          = $resultado->fields['curso_turma'];
		$curso_super[$i]['id_instituicao'] = $resultado->fields['id_instituicao'];
		$curso_super[$i]['instituicao']    = $resultado->fields['instituicao'];
		$i++;
		$resultado->MoveNext();
    }
    $smarty = new Smarty_estagio;
    $smarty->assign("matriz",$curso_super);
    $smarty->assign("palavra",$palavra);
	$smarty->assign("autentica",$sistema_autentica);
    $smarty->display("busca_inscritos.tpl");
}

exit;

?>

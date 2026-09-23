<?php

include_once("../autentica.inc");

$muralestagio_id = isset($_REQUEST['muralestagio_id']) ? (int)$_REQUEST['muralestagio_id'] : 0;

$sqlInstituicao = "select instituicao from mural_estagios where id = $muralestagio_id";

$resultadoInstituicao = $db->Execute($sqlInstituicao);
if ($resultadoInstituicao === false)
    die("Não foi possível consultar a tabela mural_estagios");
$instituicao = $resultadoInstituicao ? $resultadoInstituicao->fields['instituicao'] : '';

$sql = "SELECT id, registro, data FROM inscricoes WHERE muralestagio_id='$muralestagio_id' and periodo='" . PERIODO_ATUAL . "'";
$resultado = $db->Execute($sql);
if ($resultado === false)
    die("Não foi possível consultar a tabela inscricoes");

$inscritos = array();
$i = 0;
while ($resultado && !$resultado->EOF) {
    $id = $resultado->fields['id'];
    $registro = $resultado->fields['registro'];
    $data = date("d-m-Y", strtotime($resultado->fields['data']));
    $sqlAlunos = "select id, nome, registro, telefone, celular, email from alunos where registro= '$registro'";
    $resultadoAlunos = $db->Execute($sqlAlunos);
    if ($resultadoAlunos === false)
        die("Não foi possível consultar a tabela alunos");
    while ($resultadoAlunos && !$resultadoAlunos->EOF) {
        $inscritos[$i]['id'] = $id;
        $inscritos[$i]['nome'] = $resultadoAlunos->fields['nome'];
        $inscritos[$i]['registro'] = $resultadoAlunos->fields['registro'];
        $inscritos[$i]['telefone'] = $resultadoAlunos->fields['telefone'];
        $inscritos[$i]['celular'] = $resultadoAlunos->fields['celular'];
        $inscritos[$i]['email'] = $resultadoAlunos->fields['email'];
        $inscritos[$i]['data'] = $data;
        $inscritos[$i]['aluno'] = 1;
        $i++;
        $resultadoAlunos->MoveNext();
    }
    $resultado->MoveNext();
}

if (!empty($inscritos)) {
    sort($inscritos);
}

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica", $sistema_autentica);
$smarty->assign("muralestagio_id", $muralestagio_id);
$smarty->assign("id_instituicao", $muralestagio_id);
$smarty->assign("instituicao", $instituicao);
$smarty->assign("inscritos", $inscritos);
$smarty->display("../../mural/listaInscritos.tpl");

?>

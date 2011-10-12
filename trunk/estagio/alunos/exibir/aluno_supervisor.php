<?php

include_once("../../setup.php");

// Verifico se o usuario esta logado
if (isset($_COOKIE['usuario_senha'])) {
    $usuario = $_COOKIE['usuario_nome'];
    if ($usuario) 
	$logado = 1;
}

$sql  = "select alunos.id, alunos.registro, alunos.nome, estagiarios.turno, estagiarios.nivel, estagiarios.id_supervisor, estagiarios.id_instituicao ";
$sql .= "from alunos, estagiarios where alunos.id = estagiarios.id_aluno order by nome";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela alunos");
$i = 1;
while (!$resultado->EOF) {
    $aluno_super[$i]['id_aluno']       = $resultado->fields['id'];
    $aluno_super[$i]['registro']       = $resultado->fileds['registro'];
    $aluno_super[$i]['aluno']          = $resultado->fields['nome'];
    $aluno_super[$i]['id_supervisor']  = $resultado->fields['id_supervisor'];
    $aluno_super[$i]['id_instituicao'] = $resultado->fields['id_instituicao'];
    
    $id_supervisor = $resultado->fields['id_supervisor'];
    $resultado->MoveNext();

    if (empty($id_supervisor))
        $id_supervisor = "0";

    $sql_supervisores = "select cress, nome from supervisores where id=$id_supervisor";
    $resultado_supervisores = $db->Execute($sql_supervisores);
    if ($resultado_supervisores === false) die ("Não foi possível consultar a tabela supervisores");
    while (!$resultado_supervisores->EOF) {
        $aluno_super[$i]['cress']      = $resultado_supervisores->fields['cress'];
        $aluno_super[$i]['supervisor'] = $resultado_supervisores->fields['nome'];
        $resultado_supervisores->MoveNext();
    }
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("pagina",$PHP_SELF);
$smarty->assign("logado",$logado);
$smarty->assign("alunos_supervisor",$aluno_super);
$smarty->display("alunos-exibir_aluno_supervisor.tpl");

?>

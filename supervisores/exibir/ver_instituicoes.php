<?php

include_once("../../setup.php");

$ordem = $_GET['ordem'];

/*
  $sql  = "select e.id as estagio_id, e.instituicao, s.id as supervisor_id, s.cress, s.nome, ";
  $sql .= "s.email ";
  $sql .= "from supervisores as s, inst_super as i, instituicoes as e ";
  $sql .= "where s.id=i.supervisor_id and i.instituicao_id=e.id";
 */

$sql = "select e.id, e.instituicao ";
$sql .= ", s.id as supervisor_id, s.cress, s.nome, s.email ";
// $sql .= ", c.id as id_curso ";
// $sql .= ", max(t.periodo) as turma ";
$sql .= " from supervisores as s ";
$sql .= " join inst_super as i on s.id = i.supervisor_id ";
$sql .= " join instituicoes as e on e.id = i.instituicao_id ";
// $sql .= " left join estagiarios as t on s.id = t.supervisor_id ";
// $sql .= " group by t.supervisor_id";
// $sql .= " left outer join curso_inscricao_supervisor as c on s.cress = c.cress ";
// $sql .= " group by c.cress";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado == false)
    die("Não foi possível consultar as tabelas");
while (!$resultado->EOF) {
    if (empty($ordem))
        $ordem = "nome";
    else
        $indice = $ordem;

    $instituicao_id = $resultado->fields['id'];
    $supervisor_id = $resultado->fields['supervisor_id'];
    $cress = $resultado->fields['cress'];
    $turma = $resultado->fields['turma'];
    $nome_supervisor = $resultado->fields['nome'];
    $email_supervisor = $resultado->fields['email'];
    $estagio_instituicao = $resultado->fields['instituicao'];

    $matriz[$i][$ordem] = $$indice;
    $matriz[$i]['instituicao_id'] = $instituicao_id;
    $matriz[$i]['supervisor_id'] = $supervisor_id;
    $matriz[$i]['nome'] = $nome_supervisor;
    $matriz[$i]['instituicao'] = $estagio_instituicao;
    $matriz[$i]['email'] = $email_supervisor;

    // Pego a informacao sobre turma de alunos
    $sqlturma = "select id, max(periodo) as turma from estagiarios where supervisor_id = $supervisor_id group by supervisor_id";
    // echo $sqlturma . "<br>";
    $res_turma = $db->Execute($sqlturma);
    if ($res_turma === false)
        die("Não foi possivel consultar a tabela estagiarios");
    $turma = $res_turma->fields['turma'];
    $matriz[$i]['turma'] = $turma;

    $resultado->MoveNext();
    $i++;
}

reset($matriz);
sort($matriz);

/* Debugg
  for($i=0;$i<sizeof($matriz);$i++) {
  print $matriz[$i]['id'] . " ";
  print $matriz[$i]['nome'] . " ";
  print $matriz[$i]['instituicao'] . "<br>";
  }
 */

$smarty = new Smarty_estagio;
$smarty->assign("pagina_atual", $PHP_SELF);
$smarty->assign("supervisores", $matriz);
$smarty->display("supervisores.tpl");

$db->Close();

exit;

?>
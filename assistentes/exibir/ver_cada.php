<?php

include_once("../../autentica.inc");

$indice = $_REQUEST['indice'];
$supervisor_id = $_REQUEST['supervisor_id'];
$periodo = $_REQUEST['periodo'];
// echo "Indice: " . $indice . " Periodo "  . $periodo . "<br />";
// echo "id_supervisor " . $supervisor_id . "<br />";

$sql = "select supervisores.id as num_supervisor, supervisores.cress, supervisores.nome, supervisores.endereco, supervisores.bairro, supervisores.cep, supervisores.municipio, supervisores.codigo_tel, supervisores.telefone, supervisores.codigo_cel, supervisores.celular, supervisores.email, ";
$sql .= " instituicoes.id, instituicoes.instituicao, ";
$sql .= " supervisores.observacoes ";
// $sql .= " max(estagiarios.periodo) ";
$sql .= " from supervisores ";
$sql .= " left join inst_super on supervisores.id = inst_super.supervisor_id ";
$sql .= " left join instituicoes on inst_super.instituicao_id = instituicoes.id ";
$sql .= " left join estagiarios on supervisores.id = estagiarios.supervisor_id ";
if ($periodo) $sql .= " where estagiarios.periodo = '$periodo' ";
$sql .= " group by supervisores.id ";
$sql .= " order by nome, inst_super.supervisor_id ";

// echo $sql . "<br>";

$resultado_total = $db->Execute($sql);
$ultimo = $resultado_total->RecordCount();
// echo $ultimo . "<br>";
// Se estou no final o proximo registro é o primeiro
if ($indice >= $ultimo) {
    $indice = 0;
}

// Se estou no primerio registro o registro anterior é o último
if ($indice < 0) {
    $indice = $ultimo - 1;
}

// Calculo o indice
if (!empty($supervisor_id)) {

    /*
     * Utilizo a consulta anterior
     */
    // echo $sql . "<br />";
    $resultado = $db->Execute($sql);
    $i = 0;
    // echo $i . "<br />";
    while (!$resultado->EOF) {
        $num_supervisor = $resultado->fields['num_supervisor'];
        $nome_supervisor = $resultado->fields['nome'];
        // echo "id_supervisor -> " . $supervisor_id . " num_supervisor -> " . $num_supervisor . " Nome: "  . $nome_supervisor . "<br />";
        if ($num_supervisor == $supervisor_id) {
            $indice = $i;
            // echo "Indice " . $indice . " id_supervisor " . $supervisor_id . "<br />";
            break;
        }
        $i++;
        $resultado->MoveNext();
    }
}

// Rotina para acrescentar uma instituicao
if (!empty($_POST['num_instituicao'])) {
    // echo "Acrescentar instituicao<br>";
    $sql_inserir = "insert into inst_super (supervisor_id,instituicao_id) values('$supervisor_id','$_POST[num_instituicao]')";
    // echo $sql . "<br>";
    $res_inserir = $db->Execute($sql_inserir);
    if ($res_inserir === false)
        die("Não foi possível inserir dados na tabela inst_super");
} else {
    // echo "Nada: " . $_POST[num_instituicao] . "<br>";
}

// echo $indice . " " . $sql . "<br>";
$resultado = $db->SelectLimit($sql, 1, $indice);
if ($resultado === false) die("1 Não foi possível consultar a tabela supervisores");
while (!$resultado->EOF) {
    $supervisor_id = $resultado->fields['num_supervisor'];
    $cress = $resultado->fields['cress'];
    $nome = $resultado->fields['nome'];
    $endereco = $resultado->fields['endereco'];
    $bairro = $resultado->fields['bairro'];
    $cep = $resultado->fields['cep'];
    $municipio = $resultado->fields['municipio'];
    $codigo_tel = $resultado->fields['codigo_tel'];
    $telefone = $resultado->fields['telefone'];
    $codigo_cel = $resultado->fields['codigo_cel'];
    $celular = $resultado->fields['celular'];
    $email = $resultado->fields['email'];
    $instituicao_id = $resultado->fields['id'];
    $observacoes = $resultado->fields['observacoes'];

    // Capturo as instituicoes campo de emprego do supervisor
    $sql_instituicoes = "select instituicoes.id, instituicoes.instituicao from instituicoes ";
    $sql_instituicoes .= " inner join inst_super on instituicoes.id=inst_super.instituicao_id ";
    $sql_instituicoes .= "where inst_super.supervisor_id='$supervisor_id'";
    // echo $sql_instituicoes . "<br>";
    $resultado = $db->Execute($sql_instituicoes);
    if ($resultado === false) die("Não foi possível consultar a tabela instituicoes");
    $i = 0;
    while (!$resultado->EOF) {
        $inst_emprego[$i]['instituicao_id'] = $resultado->fields['id'];
        $inst_emprego[$i]['instituicao'] = $resultado->fields['instituicao'];
        // echo "Instituicao: " . $inst_estagio[$i]['instituicao'] . "<br>";
        $i++;
        $resultado->MoveNext();
    }

    // Alunos supervisionados pelo supervisor
    $sqlalunos = "select alunos.id, alunos.registro, alunos.nome, estagiarios.periodo, estagiarios.instituicao_id as instituicao_id from alunos ";
    $sqlalunos .= " inner join estagiarios on estagiarios.registro = alunos.registro ";
    $sqlalunos .= " where estagiarios.supervisor_id = $supervisor_id";
    $sqlalunos .= " order by estagiarios.periodo, alunos.nome";
    // echo $sqlalunos . "<br>";

    $res_alunos = $db->Execute($sqlalunos);
    if ($res_alunos === false) die("Não foi possível consultar a tabela alunos");
    $i = 0;
    while (!$res_alunos->EOF) {
        $alunos[$i]['aluno_id'] = $res_alunos->fields['id'];
        $alunos[$i]['registro'] = $res_alunos->fields['registro'];
        $alunos[$i]['nome'] = $res_alunos->fields['nome'];
        $alunos[$i]['periodo'] = $res_alunos->fields['periodo'];
        $alunos[$i]['instituicao_id'] = $res_alunos->fields['instituicao_id'];

        $instituicao_id = $res_alunos->fields['instituicao_id'];
        $sql_aluno_instituicao = "select instituicao from instituicoes where id = $instituicao_id";
        // echo $sql_aluno_instituicao . "<br>";
        $res_aluno_instituicao = $db->Execute($sql_aluno_instituicao);

        $alunos[$i]['instituicao'] = $res_aluno_instituicao->fields['instituicao'];
        // echo $res_aluno_instituicao->fields['instituicao'];

        $i++;
        // echo $i;
        $res_alunos->MoveNext();
    }

    $resultado->MoveNext();
}
// die;
// Instituicoes
$sql = "select id, instituicao from instituicoes order by instituicao";
$resultado = $db->Execute($sql);
if ($resultado === false) die("Não foi possível consultar a tabela instituicoes");
$i = 0;
while (!$resultado->EOF) {
    $instituicoes[$i]['instituicao_id'] = $resultado->fields['id'];
    $instituicoes[$i]['instituicao'] = $resultado->fields['instituicao'];
    $resultado->MoveNext();
    $i++;
}

// Pego a informacao sobre as turma de alunos
$sqlturma = "select id, periodo from estagiarios group by periodo";
// echo $sqlturma . "<br>";
$res_turma = $db->Execute($sqlturma);
if ($res_turma === false) die("Não foi possivel consultar a tabela estagiarios");
while (!$res_turma->EOF) {
    $periodos[] = $res_turma->fields['periodo'];
    $res_turma->MoveNext();
}

$smarty = new Smarty_estagio;
$smarty->assign("sistema_autentica", $logado);
$smarty->assign("ultimo", $ultimo - 1);
$smarty->assign("indice", $indice);
$smarty->assign("supervisor_id", $supervisor_id);
$smarty->assign("periodo", $periodo);
$smarty->assign("cress", $cress);
$smarty->assign("nome", $nome);
$smarty->assign("endereco", $endereco);
$smarty->assign("bairro", $bairro);
$smarty->assign("cep", $cep);
$smarty->assign("municipio", $municipio);
$smarty->assign("codigo_tel", $codigo_tel);
$smarty->assign("telefone", $telefone);
$smarty->assign("codigo_cel", $codigo_cel);
$smarty->assign("celular", $celular);
$smarty->assign("email", $email);
$smarty->assign("instituicao_id", $instituicao_id);
$smarty->assign("emprego", $inst_emprego);
$smarty->assign("observacoes", $observacoes);
$smarty->assign("alunos", $alunos);
$smarty->assign("periodos", $periodos);
$smarty->assign("instituicoes", $instituicoes);
$smarty->display("supervisores_ver_cada.tpl");

exit;

?>

<?php

include_once("../autentica.inc");

$aluno_id = isset($_REQUEST['aluno_id']) ? (int) $_REQUEST['aluno_id'] : NULL;
$registro = isset($_REQUEST['registro']) ? (int) $_REQUEST['registro'] : NULL;
$muralestagio_id = isset($_REQUEST['muralestagio_id']) ? (int) $_REQUEST['muralestagio_id'] : NULL;

// Pego o nome da instituicao
$sql = "select instituicao from mural_estagios where id='$muralestagio_id'";
$resultado = $db->Execute($sql);
$instituicao = $resultado->fields['instituicao'];

// Pego os dados do aluno
$sql_alunos = "select id, registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, orgao, nascimento, endereco, cep, municipio, bairro, observacoes from alunos where registro='$registro'";

$resultado_alunos = $db->Execute($sql_alunos);
if ($resultado_alunos === false) die("Não foi possível consultar a tabela alunos");
while (!$resultado_alunos->EOF) {
    $aluno_id = $resultado_alunos->fields['id'];
    $aluno_registro = $resultado_alunos->fields['registro'];
    $aluno_nome = $resultado_alunos->fields['nome'];
    $aluno_codigo_telefone = $resultado_alunos->fields['codigo_telefone'];
    $aluno_telefone = $resultado_alunos->fields['telefone'];
    $aluno_codigo_celular = $resultado_alunos->fields['codigo_celular'];
    $aluno_celular = $resultado_alunos->fields['celular'];
    $aluno_email = strtolower($resultado_alunos->fields['email']);
    $aluno_cpf = $resultado_alunos->fields['cpf'];
    $aluno_identidade = $resultado_alunos->fields['identidade'];
    $aluno_orgao = $resultado_alunos->fields['orgao'];
    $aluno_nascimento = $resultado_alunos->fields['nascimento'];

    // Transformo a data do BD de aaaa-mm-dd para dd-mm-aaaa
    $nascimento = $resultado_alunos->fields['nascimento'];
    // echo $nascimento . "<br>";
    if ($nascimento == 0)
        $aluno_nascimento = "";
    else
        $aluno_nascimento = date("d-m-Y", strtotime($nascimento));
    // echo $aluno_nascimento . "<br>";

    $aluno_endereco = $resultado_alunos->fields['endereco'];
    $aluno_cep = $resultado_alunos->fields['cep'];
    $aluno_municipio = $resultado_alunos->fields['municipio'];
    $aluno_bairro = $resultado_alunos->fields['bairro'];
    $aluno_observacoes = $resultado_alunos->fields['observacoes'];

    $resultado_alunos->MoveNext();
}

// Pego esta informaçao para fazer a tabela dos anteriores estagios
$sql_estagios = "SELECT estagiarios.id, estagiarios.periodo, estagiarios.nivel, " .
            "instituicoes.instituicao, supervisores.nome " .
            "FROM estagiarios " .
            "left join instituicoes " .
            "on instituicoes.id = estagiarios.instituicao_id " .
            "left join supervisores " .
            "on supervisores.id= estagiarios.supervisor_id " .
            "where estagiarios.registro = '$registro' " .
            "order by estagiarios.periodo";

$resultado = $db->Execute($sql_estagios);
if ($resultado === false) die("Nao foi possivel consultar as tabelas estagiarios, instituicoes, supervisores");
    $i = 0;
    while (!$resultado->EOF) {
        $estagiarios[$i]['id'] = $resultado->fields['id'];
        $estagiarios[$i]['periodo'] = $resultado->fields['periodo'];
        $estagiarios[$i]['nivel'] = $resultado->fields['nivel'];
        $estagiarios[$i]['instituicao'] = $resultado->fields['instituicao'];
        $estagiarios[$i]['supervisor'] = $resultado->fields['nome'];

        $resultado->MoveNext();
        $i++;
    }

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica", $sistema_autentica);
// Tabela de estagios anteriores
$smarty->assign("estagiarios", $estagiarios);
// Tabela inserir novo est�gio
$smarty->assign("registro", $registro);
$smarty->assign("aluno_nome", $aluno_nome);
$smarty->assign("codigo_telefone", $aluno_codigo_telefone);
$smarty->assign("telefone", $aluno_telefone);
$smarty->assign("codigo_celular", $aluno_codigo_celular);
$smarty->assign("celular", $aluno_celular);
$smarty->assign("email", $aluno_email);
$smarty->assign("cpf", $aluno_cpf);
$smarty->assign("identidade", $aluno_identidade);
$smarty->assign("orgao", $aluno_orgao);
$smarty->assign("nascimento", $aluno_nascimento);
$smarty->assign("endereco", $aluno_endereco);
$smarty->assign("cep", $aluno_cep);
$smarty->assign("municipio", $aluno_municipio);
$smarty->assign("bairro", $aluno_bairro);
$smarty->assign("observacoes", $aluno_observacoes);

$smarty->assign("instituicao", $instituicao);
$smarty->assign("muralestagio_id", $muralestagio_id);

$smarty->display("../../mural/mural-alunos_modifica.tpl");

exit;

?>

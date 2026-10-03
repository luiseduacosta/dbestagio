<?php

require_once("../../autentica.inc");
require_once("../../libphp/models.php");

// ---------------------------------------------------------------------
// Sel_ext parameterizado do controlador de visualização/edição.
// Mantém o comportamento original (navegação por indice na lista ordenada,
// inserção de registro em branco, edição em dois passos, vínculo de
// supervisor), porém sem SQL injection e sem o ramo morto do "curso".
// ---------------------------------------------------------------------

// Recebe e normaliza os parâmetros.
$instituicao_id = isset($_REQUEST['instituicao_id'])
    ? (int)$_REQUEST['instituicao_id']
    : (isset($_REQUEST['id_instituicao']) ? (int)$_REQUEST['id_instituicao'] : 0);
$supervisor_id  = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;
$modifica       = isset($_REQUEST['modifica']) ? $_REQUEST['modifica'] : false;
$flag           = isset($_REQUEST['flag']) ? (int)$_REQUEST['flag'] : 0;
$inserir        = isset($_REQUEST['inserir']) ? $_REQUEST['inserir'] : null;
$indice         = isset($_REQUEST['indice']) ? (int)$_REQUEST['indice'] : 0;
$botao          = isset($_REQUEST['botao']) ? $_REQUEST['botao'] : null;

$curso = false; // este módulo trabalha somente com a tabela instituicoes

// ---------- Inserir instituição em branco ----------
if ($inserir) {
    $nova = new Instituicao();
    $nova->instituicao = '';
    $nova->cnpj = '';
    $nova->user_id = (int)(isset($usuario_id) ? $usuario_id : 0);
    if ($nova->save()) {
        $instituicao_id = $nova->getKey();
        $flash = "Registro criado. Preencher o formulário com os dados e logo clicar em 'Modificar instituição'.";
    } else {
        error_log("Erro ao inserir instituicao: " . Instituicao::$db->ErrorMsg());
        die("Não foi possível inserir a instituição. Tente novamente.");
    }
}

// ---------- Edição (2 passos: mostra formulário / salva dados) ----------
if ($modifica) {
    $flag++;
    if ($flag == 2) {
        $area_instituicao  = isset($_POST['area']) ? trim($_POST['area']) : null;
        $natureza          = isset($_POST['natureza']) ? trim($_POST['natureza']) : null;
        $nome_instituicao  = isset($_POST['instituicao']) ? trim($_POST['instituicao']) : '';
        $url               = isset($_POST['url']) ? trim($_POST['url']) : null;
        $endereco          = isset($_POST['endereco']) ? trim($_POST['endereco']) : null;
        $bairro            = isset($_POST['bairro']) ? trim($_POST['bairro']) : null;
        $municipio         = isset($_POST['municipio']) ? trim($_POST['municipio']) : null;
        $cep               = isset($_POST['cep']) ? trim($_POST['cep']) : null;
        $telefone          = isset($_POST['telefone']) ? trim($_POST['telefone']) : null;
        $beneficio         = isset($_POST['beneficios']) ? trim($_POST['beneficios']) : null;
        $fim_de_semana     = isset($_POST['fim_de_semana']) ? $_POST['fim_de_semana'] : null;
        $convenio          = isset($_POST['convenio']) ? trim($_POST['convenio']) : null;
        $seguro            = isset($_POST['seguro']) ? $_POST['seguro'] : null;
        $observacoes       = isset($_POST['observacoes']) ? trim($_POST['observacoes']) : null;

        if ($nome_instituicao === '') {
            die("<p>Faltou inserir o nome da instituição. Registro será excluído.
                 <meta http-equiv='refresh' content='2;url=../cancelar/cancela.php?instituicao_id=$instituicao_id'></p>");
        }

        $inst = Instituicao::find($instituicao_id);
        if ($inst === null) {
            die("Instituição não encontrada (id $instituicao_id).");
        }
        $area_id_val = ($area_instituicao !== null && $area_instituicao !== '') ? (int)$area_instituicao : 0;
        $inst->area_id       = $area_id_val > 0 ? $area_id_val : null;
        $inst->natureza      = $natureza;
        $inst->instituicao   = $nome_instituicao;
        $inst->url           = $url;
        $inst->endereco      = $endereco;
        $inst->bairro        = $bairro;
        $inst->municipio     = $municipio;
        $inst->cep           = $cep;
        $inst->telefone      = $telefone;
        $inst->beneficios    = $beneficio;
        $inst->fim_de_semana = $fim_de_semana;
        $inst->convenio      = $convenio;
        $inst->seguro        = $seguro;
        $inst->observacoes   = $observacoes;
        if (!$inst->save()) {
            error_log("Erro ao atualizar instituicao: " . Instituicao::$db->ErrorMsg());
            die("Não foi possível atualizar a tabela instituicoes. Tente novamente.");
        }
        $flag = 0;
        $modifica = false;
    }
}

// ---------- Quantidade total de registros (para a navegação) ----------
$num_linhas = Instituicao::count();

// ---------- Navegação (botao) ----------
$ultimo_registro = $num_linhas - 1;
switch ($botao) {
    case "inserir":
    case "primeiro":
        $indice = 0;
        break;
    case "menos_1":
        $indice = ($indice == 0) ? $ultimo_registro : $indice - 1;
        break;
    case "menos_10":
        $indice = $indice - 10;
        if ($indice < 0) $indice = $ultimo_registro - abs($indice);
        break;
    case "mais_1":
        $indice++;
        if ($indice >= $num_linhas) $indice = 0;
        break;
    case "mais_10":
        $indice = $indice + 10;
        if ($indice > $ultimo_registro) $indice = $indice - $num_linhas;
        break;
    case "ultimo":
        $indice = $ultimo_registro;
        break;
    case "excluir":
        // A exclusão é feita por cancelar/cancela.php (o template envia direto).
        break;
}

// ---------- Vínculo de supervisor ----------
if ($supervisor_id > 0 && $instituicao_id > 0) {
    $instSup = Instituicao::find($instituicao_id);
    if ($instSup) {
        $instSup->vincularSupervisor($supervisor_id);
    }
}

// ---------- Resolve indice -> id (posição na lista ordenada) ----------
if ($instituicao_id <= 0) {
    // Sem id explícito, usa a posição atual (indice).
    $db = ADODB_Model::$db;
    $id_na_pos = $db->GetOne(
        "SELECT id FROM instituicoes ORDER BY instituicao LIMIT 1 OFFSET ?",
        array($indice)
    );
    $instituicao_id = (int)$id_na_pos;
} else {
    // Localiza a posição (indice) da instituição escolhida.
    $db = ADODB_Model::$db;
    $rs = $db->Execute("SELECT id FROM instituicoes ORDER BY instituicao");
    $lugar = 0;
    if ($rs) {
        while (!$rs->EOF) {
            if ((int)$rs->fields['id'] === $instituicao_id) {
                $indice = $lugar;
                break;
            }
            $lugar++;
            $rs->MoveNext();
        }
    }
}

// ---------- Carrega a instituição atual ----------
$inst = $instituicao_id > 0 ? Instituicao::find($instituicao_id) : null;

$dados = array(
    'id'             => $inst ? $inst->getKey() : null,
    'instituicao'    => $inst ? $inst->instituicao : '',
    'url'            => $inst ? $inst->url : '',
    'endereco'       => $inst ? $inst->endereco : '',
    'bairro'         => $inst ? $inst->bairro : '',
    'municipio'      => $inst ? $inst->municipio : '',
    'cep'            => $inst ? $inst->cep : '',
    'telefone'       => $inst ? $inst->telefone : '',
    'beneficios'     => $inst ? $inst->beneficios : '',
    'fim_de_semana'  => $inst ? $inst->fim_de_semana : 0,
    'id_area'        => $inst ? $inst->areaId() : '',
    'area'           => $inst ? $inst->areaNome() : '',
    'natureza'       => $inst ? $inst->natureza : '',
    'convenio'       => $inst ? $inst->convenio : 0,
    'seguro'         => $inst ? $inst->seguro : 0,
    'observacoes'    => $inst ? $inst->observacoes : '',
    'turma'          => '',
    'inst_supervisores' => array(),
    'inst_professores'  => array(),
);

if ($inst) {
    // Última turma de estagiários da instituição.
    if ($inst->countEstagiarios() > 0) {
        $dados['turma'] = $db->GetOne(
            "SELECT MAX(periodo) FROM estagiarios WHERE instituicao_id = ?",
            array($inst->getKey())
        );
    }

    // Supervisores vinculados.
    $dados['inst_supervisores'] = $inst->supervisores();

    // Professores que orientam estagiários nesta instituição (última turma).
    $rs_prof = $db->Execute(
        "SELECT p.id AS id_professor, p.nome, MAX(e.periodo) AS periodo
         FROM estagiarios AS e
         INNER JOIN professores AS p ON e.professor_id = p.id
         WHERE e.instituicao_id = ?
         GROUP BY p.nome
         ORDER BY p.nome",
        array($inst->getKey())
    );
    if ($rs_prof) {
        $i = 0;
        while (!$rs_prof->EOF) {
            $dados['inst_professores'][$i]['id_professor'] = $rs_prof->fields['id_professor'];
            $dados['inst_professores'][$i]['nome']         = $rs_prof->fields['nome'];
            $dados['inst_professores'][$i]['periodo']      = $rs_prof->fields['periodo'];
            $i++;
            $rs_prof->MoveNext();
        }
    }
}

// ---------- Listas auxiliares ----------
$matriz_areas = Instituicao::areasLista();
$supervisores = Instituicao::supervisoresTodos();

$smarty = new Smarty_estagio;

$smarty->assign("titulo", "Ver cada instituição");
$smarty->assign("curso", $curso);
$smarty->assign("modifica", $modifica);
$smarty->assign("sistema_autentica", $sistema_autentica);
$smarty->assign("indice", $indice);
$smarty->assign("instituicao_id", $instituicao_id);
$smarty->assign("id", $dados['id']);
$smarty->assign("instituicao", $dados['instituicao']);
$smarty->assign("url", $dados['url']);
$smarty->assign("id_curso_instituicao", null);
$smarty->assign("endereco", $dados['endereco']);
$smarty->assign("cep", $dados['cep']);
$smarty->assign("bairro", $dados['bairro']);
$smarty->assign("municipio", $dados['municipio']);
$smarty->assign("telefone", $dados['telefone']);
$smarty->assign("beneficios", $dados['beneficios']);
$smarty->assign("fim_de_semana", $dados['fim_de_semana']);
$smarty->assign("id_area", $dados['id_area']);
$smarty->assign("area", $dados['area']);
$smarty->assign("natureza", $dados['natureza']);
$smarty->assign("convenio", $dados['convenio']);
$smarty->assign("seguro", $dados['seguro']);
$smarty->assign("observacoes", $dados['observacoes']);
$smarty->assign("turma", $dados['turma']);
$smarty->assign("inst_supervisores", $dados['inst_supervisores']);
$smarty->assign("inst_professores", $dados['inst_professores']);
$smarty->assign("supervisores", $supervisores);
$smarty->assign("matriz_areas", $matriz_areas);
$smarty->assign("flag", $flag);
$smarty->assign("flash", isset($flash) ? $flash : '');
$smarty->display("instituicao_ver_cada.tpl");

?>
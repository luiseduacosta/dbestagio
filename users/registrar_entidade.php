<?php
/**
 * users/registrar_entidade.php — Passo 2 do cadastro de novo usuário.
 *
 * Fluxo:
 *   GET  → exibe busca por entidade existente (nome + CPF)
 *   POST (acao=buscar)   → lista entidades que casam nome/CPF
 *   POST (acao=vincular) → vincula entidade selecionada ao usuário (bidirecional)
 *   POST (acao=criar)    → cria nova entidade e vincula ao usuário
 *
 * O user_id vem da URL (passo 1 cria o user e redireciona para cá).
 * Valida que o user existe, tem role != admin, e ainda não tem entidade vinculada.
 */
require_once(__DIR__ . "/../setup.php");
require_once(__DIR__ . "/../libphp/models.php");

global $db;

// --- Validar user_id --------------------------------------------------
$user_id = isset($_REQUEST['user_id']) ? (int)$_REQUEST['user_id'] : 0;
if ($user_id <= 0) {
    header("Location: registrar.php");
    exit;
}

$usuario = User::find($user_id);
if (!$usuario) {
    header("Location: registrar.php");
    exit;
}

$usuario_dados = $usuario->toArray();
$role    = $usuario_dados['role'];
$nome    = $usuario_dados['nome'];
$email   = $usuario_dados['email'];

// Role deve ser aluno/professor/supervisor (nunca admin neste fluxo)
if (!in_array($role, array('aluno', 'professor', 'supervisor'), true)) {
    header("Location: registrar.php");
    exit;
}

// Mapeamento role → tabela, modelo, FK no users, FK na entidade
$config_entidade = array(
    'aluno' => array(
        'tabela'   => 'alunos',
        'modelo'   => 'Aluno',
        'fk_user'  => 'aluno_id',
        'fk_ent'   => 'user_id',
        'campo_id' => 'id',
        'label'    => 'aluno',
    ),
    'professor' => array(
        'tabela'   => 'professores',
        'modelo'   => 'Professor',
        'fk_user'  => 'professor_id',
        'fk_ent'   => 'user_id',
        'campo_id' => 'id',
        'label'    => 'professor',
    ),
    'supervisor' => array(
        'tabela'   => 'supervisores',
        'modelo'   => 'Supervisor',
        'fk_user'  => 'supervisor_id',
        'fk_ent'   => 'user_id',
        'campo_id' => 'id',
        'label'    => 'supervisor',
    ),
);

$cfg = $config_entidade[$role];

// Verificar se o usuário já tem entidade vinculada
$ja_vinculado = false;
$coluna_fk = $cfg['fk_user'];
if (!empty($usuario_dados[$coluna_fk]) && (int)$usuario_dados[$coluna_fk] > 0) {
    $ja_vinculado = true;
}

$erro    = '';
$sucesso = '';
$resultados_busca = array();
$acao = isset($_POST['acao']) ? $_POST['acao'] : '';

// --- Processar POST ---------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$ja_vinculado) {

    if ($acao === 'buscar') {
        $busca_nome = trim($_POST['busca_nome'] ?? '');
        $busca_cpf  = trim($_POST['busca_cpf'] ?? '');

        if ($busca_nome === '' && $busca_cpf === '') {
            $erro = "Digite seu nome ou CPF para buscar.";
        } else {
            // Busca por nome (LIKE) e/ou CPF (exato)
            $sql = "SELECT * FROM {$cfg['tabela']} WHERE 1=1";
            $params = array();
            if ($busca_nome !== '') {
                $sql .= " AND nome LIKE ?";
                $params[] = "%" . $busca_nome . "%";
            }
            if ($busca_cpf !== '') {
                $sql .= " AND cpf = ?";
                $params[] = $busca_cpf;
            }
            $sql .= " ORDER BY nome LIMIT 20";

            $rs = $db->Execute($sql, $params);
            if ($rs !== false) {
                while (!$rs->EOF) {
                    $row = $rs->fields;
                    // Verificar se a entidade já tem user_id (já vinculada)
                    $row['ja_vinculada'] = !empty($row['user_id']);
                    $resultados_busca[] = $row;
                    $rs->MoveNext();
                }
            }
        }

    } elseif ($acao === 'vincular') {
        $entidade_id = (int)($_POST['entidade_id'] ?? 0);
        if ($entidade_id <= 0) {
            $erro = "Selecione uma entidade para vincular.";
        } else {
            // Verificar que a entidade existe e não tem user_id
            $sql = "SELECT * FROM {$cfg['tabela']} WHERE {$cfg['campo_id']} = ?";
            $rs = $db->Execute($sql, array($entidade_id));
            if ($rs === false || $rs->RecordCount() === 0) {
                $erro = "Registro não encontrado.";
            } elseif (!empty($rs->fields['user_id'])) {
                $erro = "Este {$cfg['label']} já está vinculado a outro usuário.";
            } else {
                // Vinculação bidirecional
                // 1. Set user_id na entidade
                $sql_upd_ent = "UPDATE {$cfg['tabela']} SET {$cfg['fk_ent']} = ? WHERE {$cfg['campo_id']} = ?";
                $db->Execute($sql_upd_ent, array($user_id, $entidade_id));

                // 2. Set entidade_id no users
                $sql_upd_user = "UPDATE users SET {$cfg['fk_user']} = ? WHERE id = ?";
                $db->Execute($sql_upd_user, array($entidade_id, $user_id));

                $sucesso = "Cadastro concluído! Seu perfil de {$cfg['label']} foi vinculado à sua conta.";
            }
        }

    } elseif ($acao === 'criar') {
        // Criar nova entidade com os dados do formulário
        $ent_nome = trim($_POST['ent_nome'] ?? '');
        $ent_cpf  = trim($_POST['ent_cpf'] ?? '');
        $ent_email = trim($_POST['ent_email'] ?? '');

        if ($ent_nome === '') {
            $erro = "O nome é obrigatório.";
        } else {
            // Campos específicos por role
            if ($role === 'aluno') {
                $ent_registro = (int)($_POST['ent_registro'] ?? 0);
                $ent_telefone = trim($_POST['ent_telefone'] ?? '');

                // alunos.id não é auto_increment
                $novo_id = Aluno::proximoId();
                $sql_ins = "INSERT INTO alunos (id, nome, cpf, registro, email, telefone, user_id) "
                         . "VALUES (?, ?, ?, ?, ?, ?, ?)";
                $db->Execute($sql_ins, array($novo_id, $ent_nome, $ent_cpf, $ent_registro, $ent_email, $ent_telefone, $user_id));
                $entidade_id = $novo_id;

            } elseif ($role === 'professor') {
                $ent_siape  = trim($_POST['ent_siape'] ?? '');
                $sql_ins = "INSERT INTO professores (nome, cpf, siape, email, user_id) "
                         . "VALUES (?, ?, ?, ?, ?)";
                $db->Execute($sql_ins, array($ent_nome, $ent_cpf, $ent_siape, $ent_email, $user_id));
                $entidade_id = (int)$db->Insert_ID();

            } elseif ($role === 'supervisor') {
                $ent_cress = trim($_POST['ent_cress'] ?? '');
                $sql_ins = "INSERT INTO supervisores (nome, cpf, cress, email, user_id) "
                         . "VALUES (?, ?, ?, ?, ?)";
                $db->Execute($sql_ins, array($ent_nome, $ent_cpf, $ent_cress, $ent_email, $user_id));
                $entidade_id = (int)$db->Insert_ID();
            }

            if (!empty($entidade_id) && $entidade_id > 0) {
                // Set entidade_id no users
                $sql_upd_user = "UPDATE users SET {$cfg['fk_user']} = ? WHERE id = ?";
                $db->Execute($sql_upd_user, array($entidade_id, $user_id));

                $sucesso = "Cadastro concluído! Seu perfil de {$cfg['label']} foi criado e vinculado à sua conta.";
            } else {
                $erro = "Erro ao criar o registro. Tente novamente.";
            }
        }
    }
}

// --- Template ---------------------------------------------------------
$smarty = new Smarty_estagio;
$smarty->assign('user_id',     $user_id);
$smarty->assign('usuario_nome', $nome);
$smarty->assign('usuario_email', $email);
$smarty->assign('role',        $role);
$smarty->assign('role_label',  $cfg['label']);
$smarty->assign('erro',        $erro);
$smarty->assign('sucesso',     $sucesso);
$smarty->assign('ja_vinculado', $ja_vinculado);
$smarty->assign('resultados',  $resultados_busca);
$smarty->assign('mostrar_form_criar', ($acao === 'buscar' && empty($resultados_busca) && empty($erro)) || $acao === 'criar_novo');
$smarty->display('users_registrar_entidade.tpl');

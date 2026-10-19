<?php
/**
 * sessao.php — Sessão autenticada do sistema.
 *
 * A identidade do usuário logado vive na SESSÃO do PHP: criada no login
 * (verifica_login.php), relida da tabela users a cada requisição e destruída no
 * logout. Antes, o login apenas gravava um cookie `usuario` com o e-mail e as
 * páginas confiavam nesse valor — qualquer visitante podia se passar por outro
 * usuário (inclusive admin) alterando o cookie no navegador.
 *
 * Uso:
 *   require_once(__DIR__ . "/libphp/sessao.php");
 *   sessao_autenticar($usuario);      // login
 *   $sessao = sessao_usuario();       // identidade da sessão (sem banco)
 *   $usuario = sessao_usuario_atual();// usuário relido do banco (autorização)
 *   sessao_encerrar();                // logout
 *
 * ATENÇÃO: os cookies `usuario` e `usuario_nome` continuam sendo gravados
 * APENAS para os menus em JavaScript (links.php, links.html) exibirem os blocos
 * da administração — são informação de exibição e NUNCA devem ser usados para
 * autorizar nada. Autorização só pela sessão.
 */

if (!function_exists('sessao_iniciar')) {

/**
 * Abre (ou reaproveita) a sessão, com cookie httponly e restrito ao mesmo site.
 */
function sessao_iniciar() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $seguro = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params(array(
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => $seguro,
    ));
    session_start();
}

/**
 * Grava a identidade do usuário autenticado na sessão. Regenera o id da sessão
 * (evita fixação) e apaga os cookies legados de identificação.
 *
 * @param ADODB_Model $usuario Usuário da tabela users já autenticado.
 */
function sessao_autenticar($usuario) {
    sessao_iniciar();
    session_regenerate_id(true);

    $_SESSION['usuario_id']    = (int)$usuario->id;
    $_SESSION['usuario_email'] = (string)$usuario->email;
    $_SESSION['usuario_nome']  = (string)$usuario->email;
    $_SESSION['usuario_role']  = (string)$usuario->categoria;

    // Cookies de exibição dos menus em JavaScript (nunca de autorização).
    $seguro = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    setcookie('usuario', (string)$usuario->email, 0, '/', '', $seguro, false);
    setcookie('usuario_nome', (string)$usuario->email, 0, '/', '', $seguro, false);
    setcookie('email', '', time() - 3600, '/');
}

/**
 * Identidade guardada na sessão (sem consultar o banco) ou null quando não há
 * ninguém autenticado.
 */
function sessao_usuario() {
    sessao_iniciar();
    if (empty($_SESSION['usuario_id'])) {
        return null;
    }
    return array(
        'id'    => (int)$_SESSION['usuario_id'],
        'email' => isset($_SESSION['usuario_email']) ? $_SESSION['usuario_email'] : '',
        'nome'  => isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : '',
        'role'  => isset($_SESSION['usuario_role']) ? $_SESSION['usuario_role'] : '',
    );
}

/**
 * Usuário da sessão relido da tabela users (sem a senha). Como a checagem é
 * feita a cada requisição, um usuário desativado ou removido perde o acesso na
 * hora. Retorna o array do usuário ou null.
 */
function sessao_usuario_atual() {
    $sessao = sessao_usuario();
    if ($sessao === null) {
        return null;
    }
    if (!class_exists('User', false)) {
        require_once __DIR__ . '/models.php';
    }
    $user = User::find($sessao['id']);
    if ($user === null) {
        sessao_encerrar();
        return null;
    }
    // A tabela users tem colunas separadas para role e categoria:
    //   role       -> 'admin' | 'aluno' | 'professor' | 'supervisor' (texto)
    //   categoria  -> '1' | '2' | '3' | '4' (código numérico em string)
    // A checagem de permissão (autentica.inc, MuralestagiosController) compara
    // role === 'admin', então a chave 'role' precisa vir da coluna role, NÃO
    // da categoria (senão admin vira '1' e isAdmin sempre false).
    $data = $user->toArray();
    $role_raw = isset($data['role']) ? (string)$data['role'] : '';
    // Compatibilidade: se por acaso role estiver com código numérico legado,
    // traduz para o texto canônico.
    $role_map = array('1' => 'admin', '2' => 'aluno', '3' => 'professor', '4' => 'supervisor');
    $role = isset($role_map[$role_raw]) ? $role_map[$role_raw] : $role_raw;
    return array(
        'id'              => (int)$data['id'],
        'email'           => $data['email'],
        'nome'            => isset($data['nome']) && $data['nome'] !== '' ? $data['nome'] : $data['email'],
        'role'            => $role,
        'categoria'       => isset($data['categoria']) ? $data['categoria'] : '',
        'aluno_id'        => isset($data['aluno_id']) ? $data['aluno_id'] : (isset($data['estudante_id']) ? $data['estudante_id'] : null),
        'supervisor_id'   => isset($data['supervisor_id']) ? $data['supervisor_id'] : null,
        'professor_id'    => isset($data['professor_id']) ? $data['professor_id'] : (isset($data['docente_id']) ? $data['docente_id'] : null),
    );
}

/**
 * Encerra a sessão e apaga os cookies de identificação (da sessão e os legados).
 */
function sessao_encerrar() {
    sessao_iniciar();
    $_SESSION = array();
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
    setcookie('usuario', '', time() - 3600, '/');
    setcookie('usuario_nome', '', time() - 3600, '/');
    setcookie('email', '', time() - 3600, '/');
}

}

?>

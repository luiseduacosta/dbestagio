<?php
/**
 * Migracao de passwords para bcrypt.
 *
 * Como usar:
 * 1. Edite a linha $senhas abaixo com os dados da password atuais (texto claro)
 *    que voce conhece. O script ira procura pelo email/usuario e faz
 *    correspondente e atualiza o hash para bcrypt.
 * 2. Acesse via navegador: /estagio/administracao/migrar_senhas.php
 * 3. OU (melhor) No primeiro login de cada usuario, o proprio
 *    verifica_login.php JA atualiza automaticamente (converter_hash_para_bcrypt).
 *
 * Este script atualiza TODAS as senhas que voce conhece o valor em texto claro.
 * Para usuarios que voce nao conhece a senha, eles serao migrados automaticamente
 * no proximo login bem-sucedido.
 */

require_once "../setup.php";

echo "<h3>Migração de passwords para bcrypt</h3>";
echo "<pre>";

$tabelas = array(
    'users' => array(
        'email_col'    => 'email',
        'password_col' => 'password',
        'senhas' => array(
            // Preencha aqui: email => senha em texto claro
            // Exemplo:
            // 'admin@exemplo.com' => 'minhasenha123',
        ),
    ),
    'usuarios' => array(
        'email_col'    => 'usuario',
        'password_col' => 'senha',
        'senhas' => array(
            // Preencha aqui: usuario => senha em texto claro
            // Exemplo:
            // 'admin' => 'minhasenha123',
        ),
    ),
);

foreach ($tabelas as $tabela => $cfg) {
    echo "\n=== Tabela: $tabela ===\n";
    foreach ($cfg['senhas'] as $login => $senha_clara) {
        $hash_bcrypt = password_hash($senha_clara, PASSWORD_DEFAULT);

        $sql = "SELECT " . $cfg['email_col'] . " FROM $tabela WHERE " . $cfg['email_col'] . " = ?";
        $r = $db->Execute($sql, array($login));
        if ($r === false) {
            echo "ERRO na consulta: " . $db->ErrorMsg() . "\n";
            continue;
        }
        if ($r->RecordCount() === 0) {
            echo "  SKIP $login: não encontrado\n";
            continue;
        }

        $upd = "UPDATE $tabela SET " . $cfg['password_col'] . " = ? WHERE " . $cfg['email_col'] . " = ?";
        $ok = $db->Execute($upd, array($hash_bcrypt, $login));
        if ($ok === false) {
            echo "  ERRO ao atualizar $login: " . $db->ErrorMsg() . "\n";
        } else {
            echo "  OK $login -> atualizado para bcrypt\n";
        }
    }
}

echo "\n--- Migracao concluida ---\n";
echo "Dica: Os usuarios que nao foram listados acima serao migrados\n";
echo "automaticamente no proximo login bem-sucedido (converter_hash_para_bcrypt).\n";
echo "</pre>";

?>

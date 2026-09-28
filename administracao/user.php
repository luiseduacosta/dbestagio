<?php

require_once __DIR__ . '/../autentica.inc';   // valida e já redireciona se não logado

echo '<link rel="stylesheet" href="user.css">' . "\n";

// A partir daqui o usuário está garantidamente autenticado
if ($sistema_autentica == 1) {
    echo "Logado como: {$usuario_email} ({$usuario_nome_completo})";
    echo "Role: {$usuario_role}";        // admin | supervisor | professor | aluno
    echo "isAdmin: " . ($isAdmin ? 'sim' : 'não');
} else {
  header('Location: /');
  exit;
}

echo "<h3>Usuários do sistema (tabela: users)</h3>";

include_once("../database.inc");
$opts['tb'] = 'users';

$opts['key'] = 'id';
$opts['key_type'] = 'int';
$opts['sort_field'] = array('id');
$opts['inc'] = 15;
$opts['options'] = 'ACPVDF';
$opts['multiple'] = '4';
$opts['navigation'] = 'DB';
$opts['display'] = array(
	'form'  => true,
	'query' => true,
	'sort'  => true,
	'time'  => true,
	'tabs'  => true
);
$opts['js']['prefix']               = 'PME_js_';
$opts['dhtml']['prefix']            = 'PME_dhtml_';
$opts['cgi']['prefix']['operation'] = 'PME_op_';
$opts['cgi']['prefix']['sys']       = 'PME_sys_';
$opts['cgi']['prefix']['data']      = 'PME_data_';
$opts['language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'] . '-UTF8';

$trigger_file = __DIR__ . '/trigger_user_password.php';
$opts['triggers']['insert']['before'] = $trigger_file;
$opts['triggers']['update']['before'] = $trigger_file;

$opts['fdd']['id'] = array(
  'name'     => 'ID',
  'select'   => 'T',
  'options'  => 'AVCPDR',
  'maxlen'   => 11,
  'default'  => '0',
  'sort'     => true
);
$opts['fdd']['email'] = array(
  'name'     => 'E-mail',
  'select'   => 'T',
  'maxlen'   => 50,
  'sort'     => true
);
$opts['fdd']['identificacao'] = array(
  'name'     => 'Identificação',
  'select'   => 'T',
  'maxlen'   => 10,
  'sort'     => true
);
$opts['fdd']['nome'] = array(
  'name'     => 'Nome',
  'select'   => 'T',
  'maxlen'   => 130,
  'sort'     => true
);
$opts['fdd']['role'] = array(
  'name'     => 'Categoria',
  'select'   => 'T',
  'maxlen'   => 20,
  'sort'     => true
);
$opts['fdd']['ativo'] = array(
  'name'     => 'Ativo',
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);

require_once '../libphp/phpMyEdit.class.php';
new phpMyEdit($opts);

?>

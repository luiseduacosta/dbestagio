<?php
require_once(__DIR__ . "/libphp/sessao.php");
sessao_encerrar();
header('Location: index.php');
exit;

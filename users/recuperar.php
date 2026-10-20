<?php
/**
 * Front-controller público para recuperação de senha.
 * Não exige login — exibe orientação para contatar o admin por e-mail.
 */
require_once(__DIR__ . "/../setup.php");
require_once(__DIR__ . "/UsersController.php");

$ctrl = new UsersController();
$ctrl->recuperar();

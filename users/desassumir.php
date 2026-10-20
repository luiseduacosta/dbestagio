<?php
/**
 * users/desassumir.php — Encerra a impersonação e restaura o admin real.
 */
require_once(__DIR__ . "/UsersController.php");

$controller = new UsersController();
$controller->desassumir();

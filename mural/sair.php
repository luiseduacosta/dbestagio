<?php

// Logout: limpa o cookie de autenticacao do sistema e volta ao login principal.
setcookie("usuario", "", time() - 3600, "/");
setcookie("mural_usuario", "", time() - 3600, "/");

header("Location: ../login.php");

?>
<?php

if (!isset($newvals['senha']) || $newvals['senha'] === '') {
    if (($key = array_search('senha', $changed)) !== false) {
        unset($changed[$key]);
    }
    unset($newvals['senha']);
} else {
    $newvals['senha'] = password_hash($newvals['senha'], PASSWORD_DEFAULT);
}

return true;

?>

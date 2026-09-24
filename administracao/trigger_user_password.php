<?php

if (!isset($newvals['password']) || $newvals['password'] === '') {
    if (($key = array_search('password', $changed)) !== false) {
        unset($changed[$key]);
    }
    unset($newvals['password']);
} else {
    $newvals['password'] = password_hash($newvals['password'], PASSWORD_DEFAULT);
}

return true;

?>

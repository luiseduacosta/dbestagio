<?php

function bcrypt_hash_password($plain_password) {
    if (empty($plain_password)) {
        return false;
    }
    return password_hash($plain_password, PASSWORD_DEFAULT);
}

function bcrypt_needs_rehash($hash) {
    if (strlen($hash) < 50 || strpos($hash, '$2y$') !== 0) {
        return true;
    }
    return password_needs_rehash($hash, PASSWORD_DEFAULT);
}

?>

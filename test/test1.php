<?php

$password = "jhostyn123";

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

echo "Contraseña encriptada: " . $hashed_password;

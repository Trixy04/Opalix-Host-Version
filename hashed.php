<?php
$hashedPassword = password_hash('Ab123456!', PASSWORD_DEFAULT);
echo $hashedPassword;
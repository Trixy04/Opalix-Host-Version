<?php
$passwordChiara = 'Ab123456!'; // Cambia con la password che vuoi hashare

$hash = password_hash($passwordChiara, PASSWORD_DEFAULT);

echo "Password originale: $passwordChiara\n";
echo "Hash generato: $hash\n";

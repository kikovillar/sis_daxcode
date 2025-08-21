<?php
// Script de debug para verificar encoding
header('Content-Type: text/html; charset=utf-8');

echo "=== DEBUG ENCODING ===\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Default charset: " . ini_get('default_charset') . "\n";
echo "mbstring extension: " . (extension_loaded('mbstring') ? 'YES' : 'NO') . "\n";
echo "Current locale: " . setlocale(LC_ALL, 0) . "\n";
echo "Internal encoding: " . (function_exists('mb_internal_encoding') ? mb_internal_encoding() : 'N/A') . "\n";

// Teste de caracteres especiais
echo "\n=== TESTE DE CARACTERES ===\n";
echo "Acentos: ação, avaliação, questão\n";
echo "Emojis: 📚 👨‍🎓 👨‍🏫\n";

echo "\n=== TESTE JSON ===\n";
$data = ['message' => 'Sistema de Avaliação', 'status' => 'OK'];
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
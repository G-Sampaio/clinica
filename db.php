<?php
$host = 'localhost';
$dbname = 'clinica';
$username = 'root';
$password = ''; // Senha padrão do XAMPP

$conn = new mysqli($host, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>

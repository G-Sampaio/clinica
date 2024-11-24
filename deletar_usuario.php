<?php
session_start();
include('db.php');

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo'] !== 'admin') {
    header('Location: dashboard.php'); // Redireciona para o dashboard
    exit;
}

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Erro ao excluir usuário: " . $conn->error;
    }
} else {
    die('ID do usuário não informado.');
}
?>

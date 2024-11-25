<?php
session_start();
include('db.php');

// Verifica se o usuário está logado e é aluno
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo'] !== 'aluno') {
    header('Location: dashboard.php');
    exit;
}

// Verifica se o ID do paciente foi passado
if (!isset($_GET['id'])) {
    header('Location: visualizar_pacientes.php');
    exit;
}

$paciente_id = $_GET['id'];

// Deletar paciente
$stmt = $conn->prepare("DELETE FROM pacientes WHERE id = ? AND aluno_id = ?");
$stmt->bind_param("ii", $paciente_id, $_SESSION['user']['id']);

if ($stmt->execute()) {
    echo "Paciente deletado com sucesso!";
} else {
    echo "Erro ao deletar paciente: " . $stmt->error;
}

$stmt->close();
$conn->close();

header('Location: visualizar_pacientes.php');
exit;
?>
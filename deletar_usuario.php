<?php
session_start();
include('db.php');

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo'] !== 'admin') {
    header('Location: dashboard.php'); // Redireciona para o dashboard
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($id) {
    // Inicia uma transação para garantir consistência
    $conn->begin_transaction();

    try {
        // Excluir as consultas associadas ao paciente
        $query = "DELETE FROM consultas WHERE paciente_id IN (SELECT id FROM pacientes WHERE aluno_id = ?)";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Erro na preparação da consulta para excluir as consultas: " . $conn->error);
        }
        if (!$stmt->bind_param("i", $id)) {
            throw new Exception("Erro ao vincular parâmetro para excluir as consultas: " . $stmt->error);
        }
        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a exclusão das consultas: " . $stmt->error);
        }
        $stmt->close();

        // Excluir os pacientes atribuídos ao aluno
        $query = "DELETE FROM pacientes WHERE aluno_id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Erro na preparação da consulta para excluir os pacientes: " . $conn->error);
        }
        if (!$stmt->bind_param("i", $id)) {
            throw new Exception("Erro ao vincular parâmetro para excluir os pacientes: " . $stmt->error);
        }
        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a exclusão dos pacientes: " . $stmt->error);
        }
        $stmt->close();

        // Excluir o usuário (aluno) principal
        $query = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Erro na preparação da consulta para excluir o usuário: " . $conn->error);
        }
        if (!$stmt->bind_param("i", $id)) {
            throw new Exception("Erro ao vincular parâmetro para excluir o usuário: " . $stmt->error);
        }
        if (!$stmt->execute()) {
            throw new Exception("Erro ao excluir o usuário: " . $stmt->error);
        }
        $stmt->close();

        // Confirma a transação
        $conn->commit();
        header('Location: dashboard.php');
        exit;
    } catch (Exception $e) {
        // Reverte a transação em caso de erro
        $conn->rollback();
        die("Erro ao excluir usuário: " . $e->getMessage());
    }
} else {
    die('ID do usuário não informado.');
}

// Fechar a conexão
$conn->close();
?>

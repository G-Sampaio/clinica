<?php
session_start();
require_once('conexao.php');

// Verificar se o usuário é um professor
if ($_SESSION['tipo'] != 'professor') {
    header('Location: dashboard.php');
    exit;
}

// Buscar pacientes dos alunos
$stmt = $con->prepare("SELECT * FROM pacientes WHERE aluno_id IN (SELECT id FROM usuarios WHERE tipo = 'aluno' AND professor_id = ?)");
$stmt->bind_param("i", $_SESSION['id']); // professor_id é o ID do professor logado
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Pacientes</title>
</head>
<body>
    <h1>Pacientes de Seus Alunos</h1>
    <table>
        <tr>
            <th>Nome</th>
            <th>Data de Nascimento</th>
            <th>Endereço</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Histórico Familiar</th>
        </tr>
        <?php while ($paciente = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $paciente['nome']; ?></td>
                <td><?= $paciente['data_nasc']; ?></td>
                <td><?= $paciente['endereco']; ?></td>
                <td><?= $paciente['telefone']; ?></td>
                <td><?= $paciente['email']; ?></td>
                <td><?= $paciente['hist_familiar']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

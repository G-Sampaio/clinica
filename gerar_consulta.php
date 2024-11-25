<?php 
session_start();
include('db.php');

// Verifica se o usuário está logado e é aluno
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo'] !== 'aluno') {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obter os dados do formulário
    $paciente_id = $_POST['paciente_id'];
    $aluno_id = $_SESSION['user']['id']; // ID do aluno preenchido automaticamente
    $data_consulta = $_POST['data_consulta'];
    $hora_consulta = $_POST['hora_consulta'];
    $observacoes = $_POST['observacoes'];

    // Buscar nome do paciente
    $stmt = $conn->prepare("SELECT nome FROM pacientes WHERE id = ?");
    $stmt->bind_param("i", $paciente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $paciente = $result->fetch_assoc();
    $paciente_nome = $paciente['nome'];

    // Buscar nome do aluno
    $stmt = $conn->prepare("SELECT nome FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $aluno_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $aluno = $result->fetch_assoc();
    $aluno_nome = $aluno['nome'];

    // Inserir a consulta no banco de dados
    $stmt = $conn->prepare("INSERT INTO consultas (paciente_id, aluno_id, data_consulta, hora_consulta, observacoes) 
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $paciente_id, $aluno_id, $data_consulta, $hora_consulta, $observacoes);
    $stmt->execute();

    echo "Consulta criada com sucesso! Paciente: $paciente_nome, Aluno: $aluno_nome.";

    // Fechar a declaração
    $stmt->close();
}

// Fechar a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Consulta</title>
</head>
<body>
    <h1>Criar Nova Consulta</h1>
    <form action="salvar_consulta.php" method="POST">
        <label for="paciente_id">Selecione o Paciente:</label>
        <select id="paciente_id" name="paciente_id" required>
            <?php
            $result = $conn->query("SELECT id, nome FROM pacientes");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
            }
            ?>
        </select><br><br>

        <label for="data_consulta">Data da Consulta:</label>
        <input type="date" id="data_consulta" name="data_consulta" required><br><br>

        <label for="hora_consulta">Hora da Consulta:</label>
        <input type="time" id="hora_consulta" name="hora_consulta" required><br><br>

        <label for="observacoes">Observações:</label><br>
        <textarea id="observacoes" name="observacoes" rows="4" cols="50"></textarea><br><br>

        <button type="submit">Criar Consulta</button>
    </form>
</body>
</html>
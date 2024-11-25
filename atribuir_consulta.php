
 <?php
session_start();
include('db.php');

// Verifica se o usuário está logado e se é um aluno
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo'] !== 'aluno') {
    header('Location: dashboard.php');
    exit;
}

// Verifica se o ID do paciente foi fornecido
if (isset($_GET['id'])) {
    $paciente_id = $_GET['id'];

    // Verifica se o paciente pertence ao aluno logado
    $stmt = $conn->prepare("SELECT * FROM pacientes WHERE id = ? AND aluno_id = ?");
    $stmt->bind_param("ii", $paciente_id, $_SESSION['user']['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $paciente = $result->fetch_assoc();

    if (!$paciente) {
        echo "Paciente não encontrado ou você não tem permissão para atribuir consulta.";
        exit;
    }

    // Verifica se o formulário foi submetido
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data_consulta = $_POST['data_consulta'];
        $hora_consulta = $_POST['hora_consulta'];
        $observacoes = $_POST['observacoes'];

        // Inserir a consulta na tabela consultas
        $stmt = $conn->prepare("INSERT INTO consultas (paciente_id, aluno_id, data_consulta, hora_consulta, observacoes) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $paciente_id, $_SESSION['user']['id'], $data_consulta, $hora_consulta, $observacoes);

        if ($stmt->execute()) {
            echo "Consulta atribuída com sucesso!";
        } else {
            echo "Erro ao atribuir consulta: " . $stmt->error;
        }
    }
} else {
    echo "ID do paciente não fornecido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atribuir Consulta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        form {
            width: 50%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        label {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }

        input[type="date"], input[type="time"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Atribuir Consulta ao Paciente</h1>
    <form action="atribuir_consulta.php?id=<?= $paciente['id']; ?>" method="POST">
        <label for="data_consulta">Data da Consulta:</label>
        <input type="date" id="data_consulta" name="data_consulta" required><br>

        <label for="hora_consulta">Hora da Consulta:</label>
        <input type="time" id="hora_consulta" name="hora_consulta" required><br>

        <label for="observacoes">Observações:</label>
        <textarea id="observacoes" name="observacoes" rows="5" placeholder="Insira aqui as observações da consulta..." required></textarea><br>

        <button type="submit">Atribuir Consulta</button>
    </form>
    <br>
    <button onclick="window.location.href='visualizar_pacientes.php'">Voltar</button>
</body>
</html>

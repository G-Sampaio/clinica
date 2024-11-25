<?php
session_start();
include('db.php');

// Verifica se o usuário está logado e é aluno
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo'] !== 'aluno') {
    header('Location: dashboard.php');
    exit;
}

if (isset($_GET['id'])) {
    $paciente_id = $_GET['id'];

    // Obter dados do paciente para edição
    $stmt = $conn->prepare("SELECT * FROM pacientes WHERE id = ? AND aluno_id = ?");
    $stmt->bind_param("ii", $paciente_id, $_SESSION['user']['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $paciente = $result->fetch_assoc();

    if (!$paciente) {
        echo "Paciente não encontrado ou não autorizado a editar.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $data_nasc = $_POST['data_nasc'];
        $genero = $_POST['genero'];
        $endereco = $_POST['endereco'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $contato_emergencia = $_POST['contato_emergencia'];
        $escolaridade = $_POST['escolaridade'];
        $ocupacao = $_POST['ocupacao'];

        // Atualizar dados do paciente
        $stmt = $conn->prepare("UPDATE pacientes SET nome = ?, data_nasc = ?, genero = ?, endereco = ?, telefone = ?, email = ?, contato_emergencia= ?, escolaridade = ?, ocupacao = ? WHERE id = ?");
        $stmt->bind_param("sssssssssi", $nome, $data_nasc, $genero, $endereco, $telefone, $email, $contato_emergencia, $escolaridade, $ocupacao, $paciente_id);

        
        if ($stmt->execute()) {
            echo "Paciente atualizado com sucesso!";
            header('Location: visualizar_pacientes.php');
        } else {
            echo "Erro ao atualizar paciente: " . $stmt->error;
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
    <title>Editar Paciente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 900px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-size: 1rem;
            color: #333;
        }
        input, select, textarea {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }
        button[type="submit"] {
            padding: 12px;
            font-size: 1rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            font-size: 1rem;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            width: 100%;
        }
        .back-btn:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Editar Paciente</h1>
        <form action="editar_paciente.php?id=<?= $paciente['id']; ?>" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= $paciente['nome']; ?>" required><br>

            <label for="data_nasc">Data de Nascimento:</label>
            <input type="date" id="data_nasc" name="data_nasc" value="<?= $paciente['data_nasc']; ?>" required><br>

            <label for="genero">Gênero:</label>
            <select id="genero" name="genero" required>
                <option value="masculino" <?= $paciente['genero'] == 'masculino' ? 'selected' : ''; ?>>Masculino</option>
                <option value="feminino" <?= $paciente['genero'] == 'feminino' ? 'selected' : ''; ?>>Feminino</option>
                <option value="outro" <?= $paciente['genero'] == 'outro' ? 'selected' : ''; ?>>Outro</option>
            </select><br>

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" value="<?= $paciente['endereco']; ?>" required><br>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?= $paciente['telefone']; ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= $paciente['email']; ?>" required><br>

            <label for="contato_emergencia">Contato de Emergência:</label>
            <input type="text" id="contato_emergencia" name="contato_emergencia" value="<?= $paciente['contato_emergencia']; ?>" required><br>

            <label for="escolaridade">Escolaridade:</label>
            <textarea id="escolaridade" name="escolaridade" required><?= $paciente['escolaridade']; ?></textarea><br>

            <label for="ocupacao">Ocupação:</label>
            <textarea id="ocupacao" name="ocupacao" required><?= $paciente['ocupacao']; ?></textarea><br>

            <button type="submit">Atualizar Paciente</button>
        </form>
        <br>
        <a href="dashboard.php" class="back-btn">Voltar ao Dashboard</a>
    </div>

</body>
</html>

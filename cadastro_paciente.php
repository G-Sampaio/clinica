<?php
session_start();
include('db.php');

// Verifica se o usuário está logado e é aluno
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo'] !== 'aluno') {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $data_nasc = $_POST['data_nasc'];
    $genero = $_POST['genero'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $dataInicio = $_POST['dataInicio'];
    $contato_emergencia = $_POST['contato_emergencia'];
    $escolaridade = $_POST['escolaridade'];
    $ocupacao = $_POST['ocupacao'];
    $necessidadeEspecial = $_POST['necessidadeEspecial'];
    $histFamiliar = $_POST['histFamiliar'];
    $histSocial = $_POST['histSocial'];
    $finais = $_POST['finais'];

    // Inserir paciente no banco de dados
    $stmt = $conn->prepare("INSERT INTO pacientes (
        nome, data_nasc, genero, endereco, telefone, email, cidade, estado, dataInicio, contato_emergencia, escolaridade,
        ocupacao, necessidadeEspecial, histFamiliar, histSocial, finais, aluno_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssssssssssssi",
        $nome, $data_nasc, $genero, $endereco, $telefone, $email, $cidade, $estado, $dataInicio, $contato_emergencia,
        $escolaridade, $ocupacao, $necessidadeEspecial, $histFamiliar, $histSocial, $finais, $_SESSION['user']['id']
    );

    if ($stmt->execute()) {
        echo "Paciente cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar paciente: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Paciente</title>
    <style>
        /* Definindo a fonte padrão */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        /* Estilo do título */
        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        /* Estilo do formulário */
        form {
            width: 60%;
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

        input[type="text"], input[type="email"], input[type="date"], select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        textarea {
            resize: vertical;
            height: 100px;
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

        /* Estilo para o botão voltar */
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
    <h1>Cadastrar Paciente</h1>
    <form action="cadastro_paciente.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br>
        
        <label for="data_nasc">Data de Nascimento:</label>
        <input type="date" id="data_nasc" name="data_nasc" required><br>
        
        <label for="genero">Gênero:</label>
        <select id="genero" name="genero" required>
            <option value="masculino">Masculino</option>
            <option value="feminino">Feminino</option>
            <option value="outro">Outro</option>
        </select><br>

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required><br>
        
        <label for="cidade">Cidade:</label>
        <input type="text" id="cidade" name="cidade" required><br>

        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" required><br>

        <label for="dataInicio">Data de Início:</label>
        <input type="date" id="dataInicio" name="dataInicio" required><br>
        
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="contato_emergencia">Contato de Emergência:</label>
        <input type="text" id="contato_emergencia" name="contato_emergencia" required><br>
        
        <label for="escolaridade">Escolaridade:</label>
        <input type="text" id="escolaridade" name="escolaridade" required><br>
        
        <label for="ocupacao">Ocupação:</label>
        <input type="text" id="ocupacao" name="ocupacao" required><br>
        
        <label for="necessidadeEspecial">Necessidade Especial:</label>
        <textarea id="necessidadeEspecial" name="necessidadeEspecial"></textarea><br>

        <label for="histFamiliar">Histórico Familiar:</label>
        <textarea id="histFamiliar" name="histFamiliar"></textarea><br>

        <label for="histSocial">Histórico Social:</label>
        <textarea id="histSocial" name="histSocial"></textarea><br>

        <label for="finais">Finais:</label>
        <textarea id="finais" name="finais"></textarea><br>

        <button type="submit">Cadastrar Paciente</button>
    </form>
    <button onclick="window.location.href='dashboard.php'">Voltar ao Dashboard</button>
</body>
</html>

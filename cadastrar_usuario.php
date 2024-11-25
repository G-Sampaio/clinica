<?php
session_start();
include('db.php');

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo'] !== 'admin') {
    header('Location: dashboard.php'); // Redireciona para o dashboard
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = md5($_POST['senha']); // Criptografa a senha com MD5
    $tipo = $_POST['tipo'];

    // Insere o usuário no banco
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha, $tipo);

    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h1 {
            text-align: center;
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, select {
            width: 100%;
            padding: auto;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            font-size: 1em;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .back-link {
            text-align: center;
            margin-top: 10px;
        }
        .back-link a {
            text-decoration: none;
            color: #555;
            font-size: 0.9em;
        }
        .back-link a:hover {
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastrar Usuário</h1>
        <form action="cadastrar_usuario.php" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <label for="tipo">Tipo de Usuário:</label>
            <select id="tipo" name="tipo" required>
                <option value="admin">Admin</option>
                <option value="professor">Professor</option>
                <option value="aluno">Aluno</option>
            </select>

            <input type="submit" value="Cadastrar">
        </form>
        <div class="back-link">
            <a href="dashboard.php">Voltar para o Dashboard</a>
        </div>
    </div>
</body>
</html>

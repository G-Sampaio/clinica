<?php
session_start();
include('db.php');

// Verifica se o usuário está logado e é admin
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo'] !== 'admin') {
    header('Location: dashboard.php'); // Redireciona para o dashboard
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die('ID do usuário não informado.');
}

// Busca os dados do usuário
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];

    // Atualiza os dados
    $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ?, tipo = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nome, $email, $tipo, $id);
    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Erro ao atualizar usuário: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
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
            margin-top: 50px;
        }

        /* Estilo do formulário */
        form {
            width: 400px;
            margin: 40px auto;
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

        input[type="text"], input[type="email"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        select {
            height: 40px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Editar Usuário</h1>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="<?= $user['nome']; ?>" required><br>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= $user['email']; ?>" required><br>
        
        <label for="tipo">Tipo de Usuário:</label>
        <select name="tipo" id="tipo">
            <option value="admin" <?= $user['tipo'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="professor" <?= $user['tipo'] == 'professor' ? 'selected' : ''; ?>>Professor</option>
            <option value="aluno" <?= $user['tipo'] == 'aluno' ? 'selected' : ''; ?>>Aluno</option>
        </select><br>
        
        <input type="submit" value="Atualizar">
    </form>
</body>
</html>


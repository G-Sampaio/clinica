<?php
session_start();
include('db.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Recupera o nome do usuário da sessão, se disponível
$nome_usuario = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário';

// Exibe os usuários cadastrados
$stmt = $conn->prepare("SELECT id, nome, email, tipo FROM usuarios");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 1.5em;
            position: relative;
        }
        .header .logout {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
        }
        .logout a {
            color: white;
            text-decoration: none;
            font-size: 0.9em;
            padding: 8px 12px;
            background-color: #f44336;
            border-radius: 4px;
        }
        .logout a:hover {
            background-color: #da190b;
        }
        .container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .btn {
            padding: 5px 10px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-edit {
            background-color: #2196F3;
        }
        .btn-delete {
            background-color: #f44336;
        }
        .btn-edit:hover {
            background-color: #0b7dda;
        }
        .btn-delete:hover {
            background-color: #da190b;
        }
        .btn-cadastrar, .btn-visualizar, .btn-cadastrar-usuario {
            background-color: #4CAF50;
            margin: 10px 0;
        }
        .btn-cadastrar:hover, .btn-visualizar:hover, .btn-cadastrar-usuario:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="header">
        Bem-vindo, <?= htmlspecialchars($nome_usuario); ?>
        <div class="logout">
            <a href="logout.php">Sair</a>
        </div>
    </div>
    <div class="container">
        <h2>Usuários Cadastrados</h2>
        <a href="cadastrar_usuario.php" class="btn btn-cadastrar-usuario">Cadastrar Usuário</a>
        <a href="cadastro_paciente.php" class="btn btn-cadastrar">Cadastrar Paciente</a>
        <a href="visualizar_pacientes.php" class="btn btn-visualizar">Visualizar Pacientes</a>
        <a href="visualizar_pacientes_professor.php" class="btn btn-visualizar">Visualizar Pacientes por Aluno</a>
        <a href="consulta.php" class="btn btn-visualizar">Pesquisar Paciente</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['nome']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= ucfirst($row['tipo']); ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?= $row['id']; ?>" class="btn btn-edit">Editar</a>
                        <a href="deletar_usuario.php?id=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Deletar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

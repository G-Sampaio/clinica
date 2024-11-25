<?php
session_start();
include('db.php');

// Verifica se o usuário está logado e se é um aluno
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo'] !== 'aluno') {
    header('Location: dashboard.php');
    exit;
}

// Buscar pacientes do aluno logado
$stmt = $conn->prepare("SELECT * FROM pacientes WHERE aluno_id = ?");
$stmt->bind_param("i", $_SESSION['user']['id']);  // 'aluno_id' deve corresponder ao ID do aluno que está logado
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Pacientes</title>
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

        /* Estilizando a tabela */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Estilo para os links de ação */
        a {
            text-decoration: none;
            color: #007BFF;
            margin-right: 15px;
        }

        a:hover {
            color: #0056b3;
        }

        /* Estilo para o botão */
        button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Seus Pacientes</h1>
    <table>
        <tr>
            <th>Nome</th>
            <th>Data de Nascimento</th>
            <th>Endereço</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
        <?php while ($paciente = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $paciente['nome']; ?></td>
                <td><?= $paciente['data_nasc']; ?></td>
                <td><?= $paciente['endereco']; ?></td>
                <td><?= $paciente['telefone']; ?></td>
                <td><?= $paciente['email']; ?></td>
                <td>
                    <a href="editar_paciente.php?id=<?= $paciente['id']; ?>">Editar</a>
                    <a href="deletar_paciente.php?id=<?= $paciente['id']; ?>" onclick="return confirm('Tem certeza que deseja deletar este paciente?');">Deletar</a>
                    <a href="atribuir_consulta.php?id=<?= $paciente['id']; ?>">Atribuir Consulta</a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <button onclick="window.location.href='dashboard.php'">Voltar ao Dashboard</button>
</body>
</html>






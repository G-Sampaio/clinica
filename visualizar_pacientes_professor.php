<?php
session_start();
include('db.php');

// Verifica se o usuário está logado e é aluno
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo'] !== 'professor') {
    header('Location: dashboard.php');
    exit;
}

$sql_alunos = "SELECT id, nome FROM usuarios WHERE tipo = 'aluno'";
$result_alunos = $conn->query($sql_alunos);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos e Pacientes</title>
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
            margin: 20px 0;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            color: #555;
        }

        .message {
            text-align: center;
            color: #666;
            font-size: 16px;
            margin: 20px;
        }
    </style>
</head>
<body>

<h1>Alunos e Seus Pacientes</h1>

<?php if ($result_alunos->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Aluno</th>
                <th>Paciente(s)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($aluno = $result_alunos->fetch_assoc()): ?>
                <?php
                $aluno_id = $aluno['id'];
                $aluno_nome = $aluno['nome'];

                // Consultar pacientes associados ao aluno
                $sql_pacientes = "SELECT p.nome AS paciente_nome 
                                  FROM pacientes p
                                  INNER JOIN consultas c ON c.paciente_id = p.id
                                  WHERE c.aluno_id = ?";
                $stmt = $conn->prepare($sql_pacientes);
                $stmt->bind_param("i", $aluno_id);
                $stmt->execute();
                $result_pacientes = $stmt->get_result();

                // Montar lista de pacientes
                $pacientes = [];
                while ($paciente = $result_pacientes->fetch_assoc()) {
                    $pacientes[] = $paciente['paciente_nome'];
                }

                $stmt->close();
                ?>
                <tr>
                    <td><?= htmlspecialchars($aluno_nome); ?></td>
                    <td><?= $pacientes ? implode(", ", $pacientes) : "Nenhum paciente atribuído"; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="message">Não há alunos cadastrados.</p>
<?php endif; ?>

<?php $conn->close(); ?>

</body>
</html>

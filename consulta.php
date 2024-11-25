<?php
session_start();
include('db.php');

// Verifica se o usuário está logado e é aluno
if (!isset($_SESSION['user']) || $_SESSION['user']['tipo'] !== 'aluno') {
    header('Location: dashboard.php');
    exit;
}

$nomePaciente = $_GET['nome_paciente'] ?? '';
$informacoes = [];

if (!empty($nomePaciente)) {
    // Consulta ao banco de dados
    $query = "
        SELECT 
            p.nome, p.email, p.telefone, p.genero, p.data_nasc, p.cidade, p.estado, p.endereco,
            p.dataInicio, p.contato_emergencia, p.escolaridade, p.ocupacao, p.necessidadeEspecial, 
            p.histFamiliar, p.histSocial, p.finais,
            c.data_consulta, c.hora_consulta, c.observacoes
        FROM 
            pacientes p
        LEFT JOIN 
            consultas c ON p.id = c.paciente_id
        WHERE 
            p.nome LIKE ? AND p.aluno_id = ?
    ";
    $stmt = $conn->prepare($query);

    // Verifica se a preparação da consulta foi bem-sucedida
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $nomeBusca = "%" . $nomePaciente . "%";
    $alunoId = $_SESSION['user']['id'];

    // Faz o bind dos parâmetros
    if (!$stmt->bind_param("si", $nomeBusca, $alunoId)) {
        die("Erro ao vincular parâmetros: " . $stmt->error);
    }

    // Executa a consulta
    if (!$stmt->execute()) {
        die("Erro ao executar a consulta: " . $stmt->error);
    }

    // Obtém os resultados
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $informacoes[] = $row;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Paciente</title>
    <style>
        /* Estilo básico da página */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        .informacoes {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .informacoes p {
            margin: 5px 0;
        }

        /* Estilo para o botão de imprimir */
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            margin: 20px auto;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Estilo do formulário de pesquisa */
        .formulario-pesquisa {
            text-align: center;
            margin-bottom: 20px;
        }

        .formulario-pesquisa input {
            padding: 10px;
            font-size: 16px;
            width: 50%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .formulario-pesquisa button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .formulario-pesquisa button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Consulta de Paciente</h1>

    <!-- Formulário de Pesquisa -->
    <div class="formulario-pesquisa">
        <form method="get" action="">
            <input type="text" name="nome_paciente" placeholder="Digite o nome do paciente" value="<?= htmlspecialchars($nomePaciente); ?>" required>
            <button type="submit">Pesquisar</button>
        </form>
    </div>

    <?php if (!empty($informacoes)) : ?>
        <div class="informacoes">
            <h2>Dados Pessoais</h2>
            <?php foreach ($informacoes as $info) : ?>
                <p><strong>Nome:</strong> <?= htmlspecialchars($info['nome']); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($info['email']); ?></p>
                <p><strong>Telefone:</strong> <?= htmlspecialchars($info['telefone']); ?></p>
                <p><strong>Gênero:</strong> <?= htmlspecialchars($info['genero']); ?></p>
                <p><strong>Data de Nascimento:</strong> <?= htmlspecialchars($info['data_nasc']); ?></p>
                <p><strong>Cidade:</strong> <?= htmlspecialchars($info['cidade']); ?></p>
                <p><strong>Estado:</strong> <?= htmlspecialchars($info['estado']); ?></p>
                <p><strong>Endereço:</strong> <?= htmlspecialchars($info['endereco']); ?></p>
                <p><strong>Data de Início:</strong> <?= htmlspecialchars($info['dataInicio']); ?></p>
                <p><strong>Contato de Emergência:</strong> <?= htmlspecialchars($info['contato_emergencia']); ?></p>
                <p><strong>Escolaridade:</strong> <?= htmlspecialchars($info['escolaridade']); ?></p>
                <p><strong>Ocupação:</strong> <?= htmlspecialchars($info['ocupacao']); ?></p>
                <p><strong>Necessidade Especial:</strong> <?= htmlspecialchars($info['necessidadeEspecial']); ?></p>
                <p><strong>Histórico Familiar:</strong> <?= htmlspecialchars($info['histFamiliar']); ?></p>
                <p><strong>Histórico Social:</strong> <?= htmlspecialchars($info['histSocial']); ?></p>
                <p><strong>Finais:</strong> <?= htmlspecialchars($info['finais']); ?></p>

                <h3>Consultas Atribuídas</h3>
                <p><strong>Data da Consulta:</strong> <?= htmlspecialchars($info['data_consulta']); ?></p>
                <p><strong>Hora da Consulta:</strong> <?= htmlspecialchars($info['hora_consulta']); ?></p>
                <p><strong>Observações:</strong> <?= htmlspecialchars($info['observacoes']); ?></p>
                <hr>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p>Não foram encontrados pacientes com esse nome.</p>
    <?php endif; ?>

    <button onclick="window.print()">Imprimir Página</button>
</body>
</html>

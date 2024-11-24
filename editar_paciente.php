<?php
session_start();
require_once('conexao.php');

// Verificar se o usuário é um aluno
if ($_SESSION['tipo'] != 'aluno') {
    header('Location: dashboard.php');
    exit;
}

if (isset($_GET['id'])) {
    $paciente_id = $_GET['id'];

    // Obter dados do paciente para edição
    $stmt = $con->prepare("SELECT * FROM pacientes WHERE id = ? AND aluno_id = ?");
    $stmt->bind_param("ii", $paciente_id, $_SESSION['id']);
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
        $contato_emerg = $_POST['contato_emerg'];
        $hist_familiar = $_POST['hist_familiar'];
        $hist_social = $_POST['hist_social'];

        // Atualizar dados do paciente
        $stmt = $con->prepare("UPDATE pacientes SET nome = ?, data_nasc = ?, genero = ?, endereco = ?, telefone = ?, email = ?, contato_emerg = ?, hist_familiar = ?, hist_social = ? WHERE id = ?");
        $stmt->bind_param("sssssssssi", $nome, $data_nasc, $genero, $endereco, $telefone, $email, $contato_emerg, $hist_familiar, $hist_social, $paciente_id);
        
        if ($stmt->execute()) {
            echo "Paciente atualizado com sucesso!";
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
</head>
<body>
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

        <label for="contato_emerg">Contato de Emergência:</label>
        <input type="text" id="contato_emerg" name="contato_emerg" value="<?= $paciente['contato_emerg']; ?>" required><br>

        <label for="hist_familiar">Histórico Familiar:</label>
        <textarea id="hist_familiar" name="hist_familiar" required><?= $paciente['hist_familiar']; ?></textarea><br>

        <label for="hist_social">Histórico Social:</label>
        <textarea id="hist_social" name="hist_social" required><?= $paciente['hist_social']; ?></textarea><br>

        <button type="submit">Atualizar Paciente</button>
    </form>
</body>
</html>

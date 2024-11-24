<?php
session_start();
require_once('db.php');

// Verificar se o usuário é um aluno
if ($_SESSION['tipo'] != 'admin') {
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
    $contato_emerg = $_POST['contato_emerg'];
    $hist_familiar = $_POST['hist_familiar'];
    $hist_social = $_POST['hist_social'];
    
    // Inserir paciente no banco de dados
    $stmt = $con->prepare("INSERT INTO pacientes (nome, data_nasc, genero, endereco, telefone, email, contato_emerg, hist_familiar, hist_social, aluno_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssi", $nome, $data_nasc, $genero, $endereco, $telefone, $email, $contato_emerg, $hist_familiar, $hist_social, $_SESSION['id']);
    
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
        
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="contato_emerg">Contato de Emergência:</label>
        <input type="text" id="contato_emerg" name="contato_emerg" required><br>
        
        <label for="hist_familiar">Histórico Familiar:</label>
        <textarea id="hist_familiar" name="hist_familiar" required></textarea><br>
        
        <label for="hist_social">Histórico Social:</label>
        <textarea id="hist_social" name="hist_social" required></textarea><br>
        
        <button type="submit">Cadastrar Paciente</button>
    </form>
</body>
</html>

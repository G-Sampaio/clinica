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
<html>
<head>
    <title>Editar Usuário</title>
</head>
<body>
    <h1>Editar Usuário</h1>
    <form method="POST">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?= $user['nome']; ?>" required><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?= $user['email']; ?>" required><br>
        <label>Tipo de Usuário:</label>
        <select name="tipo">
            <option value="admin" <?= $user['tipo'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="professor" <?= $user['tipo'] == 'professor' ? 'selected' : ''; ?>>Professor</option>
            <option value="aluno" <?= $user['tipo'] == 'aluno' ? 'selected' : ''; ?>>Aluno</option>
        </select><br>
        <input type="submit" value="Atualizar">
    </form>
</body>
</html>

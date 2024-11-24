<?php
include('db.php');

// Função de login
function login($email, $senha) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($senha, $user['senha'])) {
            return $user;
        }
    }
    return false;
}

// Função para verificar o tipo de usuário
function verificarTipoUsuario($user) {
    return $user['tipo'];
}

// Função para criar paciente
function criarPaciente($dados) {
    global $conn;
    $stmt = $conn->prepare("
        INSERT INTO pacientes (nome, data_nasc, genero, endereco, telefone, email, contato_emergencia, escolaridade, ocupacao, estagiario_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssssssi",
        $dados['nome'],
        $dados['data_nasc'],
        $dados['genero'],
        $dados['endereco'],
        $dados['telefone'],
        $dados['email'],
        $dados['contato_emergencia'],
        $dados['escolaridade'],
        $dados['ocupacao'],
        $dados['estagiario_id']
    );
    return $stmt->execute();
}

// Função para atualizar paciente
function atualizarPaciente($dados) {
    global $conn;
    $stmt = $conn->prepare("
        UPDATE pacientes 
        SET nome = ?, data_nasc = ?, genero = ?, endereco = ?, telefone = ?, email = ?, contato_emergencia = ?, escolaridade = ?, ocupacao = ? 
        WHERE id = ?");
    $stmt->bind_param(
        "sssssssssi",
        $dados['nome'],
        $dados['data_nasc'],
        $dados['genero'],
        $dados['endereco'],
        $dados['telefone'],
        $dados['email'],
        $dados['contato_emergencia'],
        $dados['escolaridade'],
        $dados['ocupacao'],
        $dados['id']
    );
    return $stmt->execute();
}

// Função para buscar pacientes
function buscarPacientes($aluno_id = null) {
    global $conn;
    if ($aluno_id) {
        $stmt = $conn->prepare("SELECT * FROM pacientes WHERE estagiario_id = ?");
        $stmt->bind_param("i", $aluno_id);
    } else {
        $stmt = $conn->prepare("SELECT * FROM pacientes");
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

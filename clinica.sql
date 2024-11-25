-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/11/2024 às 02:25
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `clinica`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `consultas`
--

CREATE TABLE `consultas` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) DEFAULT NULL,
  `aluno_id` int(11) DEFAULT NULL,
  `data_consulta` date DEFAULT NULL,
  `hora_consulta` time DEFAULT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `consultas`
--

INSERT INTO `consultas` (`id`, `paciente_id`, `aluno_id`, `data_consulta`, `hora_consulta`, `observacoes`) VALUES
(1, 1, 12, '0000-00-00', '00:00:00', 'teste'),
(2, 14, 12, '2024-11-11', '21:49:00', NULL),
(3, 13, 12, '2024-11-05', '21:49:00', NULL),
(4, 13, 12, '2024-11-01', '21:51:00', NULL),
(5, 7, 8, '2024-10-30', '21:59:00', NULL),
(6, 10, 8, '2024-11-07', '23:58:00', NULL),
(7, 12, 9, '2024-11-07', '22:15:00', NULL),
(8, 12, 9, '2024-11-12', '23:21:00', 'asdawdasdawdasdawd'),
(9, 16, 9, '2024-11-27', '12:23:00', 'asdawsssxx'),
(10, 16, 9, '2024-11-23', '22:23:00', 'asxcaec');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `genero` enum('Masculino','Feminino','Outro') DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contato_emergencia` varchar(100) DEFAULT NULL,
  `escolaridade` varchar(50) DEFAULT NULL,
  `ocupacao` varchar(50) DEFAULT NULL,
  `aluno_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pacientes`
--

INSERT INTO `pacientes` (`id`, `nome`, `data_nasc`, `genero`, `endereco`, `telefone`, `email`, `contato_emergencia`, `escolaridade`, `ocupacao`, `aluno_id`) VALUES
(1, 'contato_emergencia	', '2024-11-07', 'Masculino', 'contato_emergencia	', 'contato_emergen', 'contato_emergencia@2', 'contato_emergencia	', 'contato_emergencia	', 'contato_emergencia	', NULL),
(2, 'Jonas', '2024-11-18', 'Masculino', 'Jonas', 'Jonas', 'Jonas@fasd', 'Jonas', 'Jonas', 'Jonas', NULL),
(3, 'Gui', '2024-11-16', 'Masculino', 'Gui', 'Gui', 'Gui@Gui', 'Gui', 'Gui', 'Gui', 1),
(7, 'Guilherme Sampaioac', '2024-11-16', 'Masculino', 'Guilherme Sampaioac', '123123123123', 'g@gmail.com', 'Guilherme Sampaioac', 'Guilherme Sampaioac', 'Guilherme Sampaioac', 8),
(10, 'Guiçlhe', '2024-11-22', 'Masculino', 'Guiçlhe', 'Guiçlhe', 'd@a', 'teste', 'teste', 'teste', 8),
(11, 'Teste', '2024-11-25', 'Masculino', 'Teste', 'Teste', 'a@a', 'Teste', 'Teste', 'Teste', 10),
(12, 'Nome testea', '2024-11-24', 'Masculino', 'Nome testea', 'Nome testea', 'e@1', 'Nome testea', 'Nome testea', 'Nome testea', 9),
(13, 'teste', '2024-11-07', 'Masculino', 'teste', 'teste', 'teste!@as', 'teste', 'teste', 'teste', 12),
(14, 'teste 23', '2024-11-07', 'Masculino', 'teste', 'teste', 'teste@asd', 'teste', 'testesst', 'testesst', 12),
(15, 'teste', '2024-10-29', 'Masculino', 'asd', 'asd', 'a@as', 'dasda', 'asdasd', 'asdasd', 8),
(16, 'Beatriz', '2024-11-14', 'Feminino', 'asdas ', '123123', 'f@f', 'a123123', 'asdawdasd', 'asdawdasdw', 9);

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas`
--

CREATE TABLE `turmas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `tipo` enum('admin','professor','aluno') NOT NULL,
  `turma_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `turma_id`) VALUES
(1, 'Admin Geral', 'admin@clinica.com', 'senha123', 'admin', NULL),
(7, 'Gui', 'g@gmail.com', '202cb962ac59075b964b07152d234b70', 'admin', NULL),
(8, 'Wan', 'w@gmail.com', '202cb962ac59075b964b07152d234b70', 'aluno', NULL),
(9, 'Daniel', 'd@d', '202cb962ac59075b964b07152d234b70', 'aluno', NULL),
(10, 'Aluno', 'a@a', '202cb962ac59075b964b07152d234b70', 'aluno', NULL),
(11, 'professor', 'p@p', '202cb962ac59075b964b07152d234b70', 'professor', NULL),
(12, 'danilo', 'd@g', '202cb962ac59075b964b07152d234b70', 'aluno', NULL),
(13, 'Filipak', 'f@fa', '202cb962ac59075b964b07152d234b70', 'professor', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `aluno_id` (`aluno_id`);

--
-- Índices de tabela `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estagiario_id` (`aluno_id`);

--
-- Índices de tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `turma_id` (`turma_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `consultas`
--
ALTER TABLE `consultas`
  ADD CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `consultas_ibfk_2` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `pacientes_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

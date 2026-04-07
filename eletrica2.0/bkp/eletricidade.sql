-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07/04/2026 às 03:10
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
-- Banco de dados: `eletricidade`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `consumo`
--

CREATE TABLE `consumo` (
  `id` int(11) NOT NULL,
  `potencia` varchar(50) DEFAULT NULL,
  `tempo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ohm`
--

CREATE TABLE `ohm` (
  `id` int(11) NOT NULL,
  `tipo` enum('V =','R =','C =','A =') NOT NULL,
  `tensao` varchar(50) DEFAULT NULL,
  `resistencia` varchar(50) DEFAULT NULL,
  `corrente` varchar(50) DEFAULT NULL,
  `questao` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ohm`
--

INSERT INTO `ohm` (`id`, `tipo`, `tensao`, `resistencia`, `corrente`, `questao`) VALUES
(1, 'A =', '220', '10', '22', 'Questão 3'),
(2, 'V =', '15', '3', '5', 'Questão 99');

-- --------------------------------------------------------

--
-- Estrutura para tabela `potencia`
--

CREATE TABLE `potencia` (
  `id` int(11) NOT NULL,
  `tipo` enum('W =','V =','A =') NOT NULL,
  `questao` varchar(80) NOT NULL,
  `potencia` varchar(50) DEFAULT NULL,
  `tensao` varchar(50) DEFAULT NULL,
  `corrente` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `resistores`
--

CREATE TABLE `resistores` (
  `id_resistores` int(11) NOT NULL,
  `tipo_resistores` enum('serie','paralelo','misto') DEFAULT NULL,
  `valores_resistores` text DEFAULT NULL,
  `resultado_resistores` float DEFAULT NULL,
  `resolvido_resistores` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `resistores`
--

INSERT INTO `resistores` (`id_resistores`, `tipo_resistores`, `valores_resistores`, `resultado_resistores`, `resolvido_resistores`) VALUES
(1, 'serie', '[5,5]', 10, 1),
(2, 'paralelo', '[3,3]', 1.5, 0),
(3, 'misto', '{\"serie\":[1,1],\"paralelo\":[1,1]}', 2.5, 0),
(4, 'misto', '{\"serie\":[2,2],\"paralelo\":[2,2]}', 5, 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `consumo`
--
ALTER TABLE `consumo`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `ohm`
--
ALTER TABLE `ohm`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `potencia`
--
ALTER TABLE `potencia`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `resistores`
--
ALTER TABLE `resistores`
  ADD PRIMARY KEY (`id_resistores`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `consumo`
--
ALTER TABLE `consumo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ohm`
--
ALTER TABLE `ohm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `potencia`
--
ALTER TABLE `potencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `resistores`
--
ALTER TABLE `resistores`
  MODIFY `id_resistores` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 31-Mar-2026 às 21:49
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.0.25

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
-- Estrutura da tabela `consumo`
--

CREATE TABLE `consumo` (
  `id` int(11) NOT NULL,
  `potencia` varchar(50) DEFAULT NULL,
  `tempo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ohm`
--

CREATE TABLE `ohm` (
  `id` int(11) NOT NULL,
  `tipo` enum('T =','R =','C =') NOT NULL,
  `tensao` varchar(50) DEFAULT NULL,
  `resistencia` varchar(50) DEFAULT NULL,
  `corrente` varchar(50) DEFAULT NULL,
  `questao` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `ohm`
--

INSERT INTO `ohm` (`id`, `tipo`, `tensao`, `resistencia`, `corrente`, `questao`) VALUES
(2, 'T =', '11', '660', '60', ''),
(3, 'R =', '60', '0.075', '800', ''),
(4, 'C =', '900', '99', '9.0909090909091', ''),
(5, 'T =', '10000', '1000', '10', ''),
(6, 'C =', '36', '5', '7.2', ''),
(7, 'C =', '36', '5', '7.2', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `potencia`
--

CREATE TABLE `potencia` (
  `id` int(11) NOT NULL,
  `tipo` enum('P =','I =','V =') NOT NULL,
  `questao` varchar(80) NOT NULL,
  `potencia` varchar(50) DEFAULT NULL,
  `tensao` varchar(50) DEFAULT NULL,
  `corrente` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `potencia`
--

INSERT INTO `potencia` (`id`, `tipo`, `questao`, `potencia`, `tensao`, `corrente`) VALUES
(1, 'P =', '', '100', '1000', '10'),
(2, 'P =', '', '100000', '10000', '10'),
(3, 'P =', '', '50', '6000', '120'),
(5, 'P =', '', '7000', '100', '70'),
(6, 'P =', '5', '3300', '330', '10'),
(7, 'P =', '1', '330', '3', '110');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `consumo`
--
ALTER TABLE `consumo`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `ohm`
--
ALTER TABLE `ohm`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `potencia`
--
ALTER TABLE `potencia`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `potencia`
--
ALTER TABLE `potencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

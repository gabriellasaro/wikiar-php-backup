-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Tempo de geração: 11/07/2017 às 21:21
-- Versão do servidor: 5.7.18-0ubuntu0.16.04.1
-- Versão do PHP: 7.0.18-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `wa_manager`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `wa_attempts`
--

CREATE TABLE `wa_attempts` (
  `id` bigint(20) NOT NULL,
  `code_user` varchar(120) COLLATE utf8_unicode_520_ci NOT NULL,
  `day` date NOT NULL,
  `sucess` bigint(20) NOT NULL DEFAULT '0',
  `failed` bigint(20) NOT NULL DEFAULT '0',
  `IP` varchar(120) COLLATE utf8_unicode_520_ci DEFAULT NULL,
  `register` datetime NOT NULL,
  `last_change` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Fazendo dump de dados para tabela `wa_attempts`
--


--
-- Estrutura para tabela `wa_email`
--

CREATE TABLE `wa_email` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code_user` bigint(20) NOT NULL,
  `user_email` varchar(68) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `token` varchar(102) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email_status` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0',
  `email_register` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `wa_sessions`
--

CREATE TABLE `wa_sessions` (
  `id` bigint(24) UNSIGNED NOT NULL,
  `code_user` bigint(20) NOT NULL,
  `closing_date` date NOT NULL,
  `token` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `session_status` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `platform` varchar(40) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `browser` varchar(40) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `session_IP` varchar(120) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `register` datetime NOT NULL,
  `logout_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Fazendo dump de dados para tabela `wa_sessions`
--


--
-- Estrutura para tabela `wa_user`
--

CREATE TABLE `wa_user` (
  `code_user` bigint(20) NOT NULL,
  `username` varchar(120) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_nicename` varchar(120) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_email` varchar(80) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_password` varchar(310) COLLATE utf8_unicode_520_ci NOT NULL,
  `user_birthday` date NOT NULL,
  `user_language` varchar(10) COLLATE utf8_unicode_520_ci NOT NULL DEFAULT 'pt-br',
  `user_status` char(1) COLLATE utf8_unicode_520_ci NOT NULL DEFAULT '0',
  `user_register` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Fazendo dump de dados para tabela `wa_user`
--


--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `wa_attempts`
--
ALTER TABLE `wa_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `wa_email`
--
ALTER TABLE `wa_email`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `wa_sessions`
--
ALTER TABLE `wa_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `wa_user`
--
ALTER TABLE `wa_user`
  ADD PRIMARY KEY (`code_user`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `wa_attempts`
--
ALTER TABLE `wa_attempts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de tabela `wa_email`
--
ALTER TABLE `wa_email`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `wa_sessions`
--
ALTER TABLE `wa_sessions`
  MODIFY `id` bigint(24) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Tempo de geração: 11/07/2017 às 21:22
-- Versão do servidor: 5.7.18-0ubuntu0.16.04.1
-- Versão do PHP: 7.0.18-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `wikiar`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `activity_notification`
--

CREATE TABLE `activity_notification` (
  `code_user` bigint(20) NOT NULL,
  `message` varchar(150) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `url_notification` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `url_img` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `date_notification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `post_key`
--

CREATE TABLE `post_key` (
  `code_post` bigint(20) NOT NULL,
  `key_status` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `register` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Fazendo dump de dados para tabela `post_key`
--



-- --------------------------------------------------------

--
-- Estrutura para tabela `wa_archived_posts`
--

CREATE TABLE `wa_archived_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code_post` bigint(20) NOT NULL,
  `post_title` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_subtitle` varchar(300) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_img` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_version` varchar(8) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `post_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Fazendo dump de dados para tabela `wa_archived_posts`
--


--
-- Estrutura para tabela `wa_indexer`
--

CREATE TABLE `wa_indexer` (
  `id` bigint(20) NOT NULL,
  `code_post` bigint(20) NOT NULL,
  `code_page` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `indexer_status` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `register` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `wa_linker`
--

CREATE TABLE `wa_linker` (
  `code_post` bigint(20) NOT NULL,
  `code_user` bigint(20) NOT NULL,
  `public_version` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `last_edition_version` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `status_statistics` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0',
  `post_classification` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0',
  `status_comment` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0',
  `post_language` varchar(10) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'pt-br',
  `post_license` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'CC-BY-SA',
  `post_status` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL,
  `post_last_change` datetime DEFAULT NULL,
  `post_date_publish` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Fazendo dump de dados para tabela `wa_linker`
--


--
-- Estrutura para tabela `wa_page`
--

CREATE TABLE `wa_page` (
  `code_page` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `page_name` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `page_img` text COLLATE utf8mb4_unicode_520_ci,
  `page_capa` text COLLATE utf8mb4_unicode_520_ci,
  `page_description` int(11) DEFAULT NULL,
  `page_address` varchar(80) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `page_followers` bigint(20) NOT NULL,
  `page_status` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0',
  `page_language` varchar(8) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `wa_posts`
--

CREATE TABLE `wa_posts` (
  `code_post` bigint(20) NOT NULL,
  `post_title` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_subtitle` varchar(300) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_img` text COLLATE utf8mb4_unicode_520_ci,
  `post_version` varchar(8) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `post_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Fazendo dump de dados para tabela `wa_posts`
--


--
-- Estrutura para tabela `wa_post_recommend`
--

CREATE TABLE `wa_post_recommend` (
  `id` bigint(20) NOT NULL,
  `CODE_link` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `CODE_post` bigint(20) NOT NULL,
  `recommend` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `register` datetime NOT NULL,
  `last_activity` datetime DEFAULT NULL,
  `number_changes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Fazendo dump de dados para tabela `wa_post_recommend`
--



--
-- Estrutura para tabela `wa_post_statistics`
--

CREATE TABLE `wa_post_statistics` (
  `code_post` bigint(20) NOT NULL,
  `accesses` bigint(20) NOT NULL DEFAULT '0',
  `access_male` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `access_female` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `recommend` bigint(20) NOT NULL DEFAULT '0',
  `time_spent` varchar(40) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0',
  `error` int(12) NOT NULL DEFAULT '0',
  `status` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Fazendo dump de dados para tabela `wa_post_statistics`
--


-- --------------------------------------------------------

--
-- Estrutura para tabela `wa_profile`
--

CREATE TABLE `wa_profile` (
  `code_user` bigint(20) NOT NULL,
  `username` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `user_nicename` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `user_img` text COLLATE utf8mb4_unicode_520_ci,
  `user_capa` varchar(400) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `user_description` varchar(300) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `user_birthday` date DEFAULT NULL,
  `user_address` varchar(80) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `user_followers` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `user_status` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0',
  `verified_user` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0',
  `user_language` varchar(8) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `user_sex` enum('M','F') COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'M',
  `user_register` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Fazendo dump de dados para tabela `wa_profile`
--



-- --------------------------------------------------------

--
-- Estrutura para tabela `wa_socialnetwork`
--

CREATE TABLE `wa_socialnetwork` (
  `code_user` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `ID_youtube` varchar(120) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `ID_gplus` varchar(120) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `ID_facebook` varchar(120) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `ID_twitter` varchar(120) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `ID_donate` text COLLATE utf8mb4_unicode_520_ci,
  `sn_status` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0',
  `register` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `wa_tags`
--

CREATE TABLE `wa_tags` (
  `id` bigint(20) NOT NULL,
  `code_post` bigint(20) NOT NULL,
  `code_tag` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `tag_name` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `tag_status` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `register` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Fazendo dump de dados para tabela `wa_tags`
--


--
-- Estrutura para tabela `wa_tt`
--

CREATE TABLE `wa_tt` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code_tag` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `tag_name` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `used` bigint(20) UNSIGNED NOT NULL DEFAULT '1',
  `tag_month` varchar(10) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `register` datetime DEFAULT NULL,
  `date_last_changes` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Fazendo dump de dados para tabela `wa_tt`
--


--
-- Estrutura para tabela `wa_users_followers`
--

CREATE TABLE `wa_users_followers` (
  `id` bigint(24) UNSIGNED NOT NULL,
  `code_user` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `user_followed` varchar(120) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` char(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `register` datetime NOT NULL,
  `last_change` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `activity_notification`
--
ALTER TABLE `activity_notification`
  ADD PRIMARY KEY (`code_user`);

--
-- Índices de tabela `post_key`
--
ALTER TABLE `post_key`
  ADD PRIMARY KEY (`code_post`);

--
-- Índices de tabela `wa_archived_posts`
--
ALTER TABLE `wa_archived_posts`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `wa_indexer`
--
ALTER TABLE `wa_indexer`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `wa_linker`
--
ALTER TABLE `wa_linker`
  ADD PRIMARY KEY (`code_post`);

--
-- Índices de tabela `wa_page`
--
ALTER TABLE `wa_page`
  ADD PRIMARY KEY (`code_page`);

--
-- Índices de tabela `wa_posts`
--
ALTER TABLE `wa_posts`
  ADD PRIMARY KEY (`code_post`);

--
-- Índices de tabela `wa_post_recommend`
--
ALTER TABLE `wa_post_recommend`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `wa_post_statistics`
--
ALTER TABLE `wa_post_statistics`
  ADD PRIMARY KEY (`code_post`);

--
-- Índices de tabela `wa_profile`
--
ALTER TABLE `wa_profile`
  ADD PRIMARY KEY (`code_user`);

--
-- Índices de tabela `wa_socialnetwork`
--
ALTER TABLE `wa_socialnetwork`
  ADD PRIMARY KEY (`code_user`);

--
-- Índices de tabela `wa_tags`
--
ALTER TABLE `wa_tags`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `wa_tt`
--
ALTER TABLE `wa_tt`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `wa_users_followers`
--
ALTER TABLE `wa_users_followers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `wa_archived_posts`
--
ALTER TABLE `wa_archived_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de tabela `wa_indexer`
--
ALTER TABLE `wa_indexer`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `wa_post_recommend`
--
ALTER TABLE `wa_post_recommend`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de tabela `wa_tags`
--
ALTER TABLE `wa_tags`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de tabela `wa_tt`
--
ALTER TABLE `wa_tt`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de tabela `wa_users_followers`
--
ALTER TABLE `wa_users_followers`
  MODIFY `id` bigint(24) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

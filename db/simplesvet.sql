-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 06-Nov-2017 às 23:56
-- Versão do servidor: 10.1.19-MariaDB
-- PHP Version: 5.6.28

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simplesvet`
--

DELIMITER $$
--
-- Procedures
--
CREATE PROCEDURE `sp_animalvacina_aplica` (IN `p_anv_int_codigo` INT(11), IN `p_ani_int_codigo` INT(11), IN `p_usu_int_codigo` INT(11), IN `p_aplica` CHAR(1), INOUT `p_status` BOOLEAN, INOUT `p_msg` TEXT)  SQL SECURITY INVOKER
    COMMENT 'Procedure de Update'
BEGIN

  DECLARE v_existe boolean;
  DECLARE v_ani_cha_vivo char(1);

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Erro ao executar o procedimento.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;


    -- VALIDACOES
   SELECT IF(count(1) = 0, FALSE, TRUE)
  INTO v_existe
  FROM animal_vacina
  WHERE anv_int_codigo = p_anv_int_codigo;
  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Registro não encontrado.<br />');
  END IF;

  -- VALIDAÇÕES
  SELECT a.ani_cha_vivo
  INTO v_ani_cha_vivo
  FROM animal a
  WHERE a.ani_int_codigo = p_ani_int_codigo;
  IF v_ani_cha_vivo = 'N' THEN
    SET p_msg = CONCAT(p_msg, 'Não pode ser programada uma vacina para um animal morto. <br />');
   END IF;

  IF p_aplica NOT IN ('S', 'N') THEN
    SET p_msg = CONCAT(p_msg, 'Tipo de Aplicação Inválido. <br />');
  END IF;

  IF p_msg = '' THEN

    START TRANSACTION;

    UPDATE animal_vacina SET
      anv_dti_aplicacao = IF(p_aplica = 'S', CURRENT_TIMESTAMP(), NULL),
      usu_int_codigo = IF(p_aplica = 'S',  p_usu_int_codigo, NULL)
    WHERE anv_int_codigo = p_anv_int_codigo;

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'O registro foi alterado com sucesso.';

  END IF;

END$$

CREATE PROCEDURE `sp_animalvacina_del` (IN `p_anv_int_codigo` INT(11), INOUT `p_status` BOOLEAN, INOUT `p_msg` TEXT)  SQL SECURITY INVOKER
    COMMENT 'Procedure de Delete'
BEGIN

  DECLARE v_existe BOOLEAN;
  DECLARE v_row_count int DEFAULT 0;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Erro ao executar o procedimento.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;

  -- VALIDAÇÕES
  SELECT IF(count(1) = 0, FALSE, TRUE)
  INTO v_existe
  FROM animal_vacina
  WHERE anv_int_codigo = p_anv_int_codigo;
  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Registro não encontrado.<br />');
  END IF;

  IF p_msg = '' THEN

    START TRANSACTION;

    DELETE FROM animal_vacina
    WHERE anv_int_codigo = p_anv_int_codigo;

    SELECT ROW_COUNT() INTO v_row_count;

    COMMIT;

    IF (v_row_count > 0) THEN
      SET p_status = TRUE;
      SET p_msg = 'O Registro foi excluído com sucesso';
    END IF;

  END IF;

END$$

CREATE PROCEDURE `sp_animal_del` (IN `p_ani_int_codigo` INT(11), INOUT `p_status` BOOLEAN, INOUT `p_msg` TEXT)  SQL SECURITY INVOKER
    COMMENT 'Procedure de Delete'
BEGIN

  DECLARE v_existe BOOLEAN;
  DECLARE v_row_count int DEFAULT 0;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Erro ao executar o procedimento.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;

  -- VALIDAÇÕES
  SELECT IF(count(1) = 0, FALSE, TRUE)
  INTO v_existe
  FROM animal
  WHERE ani_int_codigo = p_ani_int_codigo;
  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Registro não encontrado.<br />');
  END IF;

  IF p_msg = '' THEN

    START TRANSACTION;

    DELETE FROM animal
    WHERE ani_int_codigo = p_ani_int_codigo;

    SELECT ROW_COUNT() INTO v_row_count;

    COMMIT;

    IF (v_row_count > 0) THEN
      SET p_status = TRUE;
      SET p_msg = 'O Registro foi excluído com sucesso';
    END IF;

  END IF;

END$$

CREATE PROCEDURE `sp_animal_ins` (IN `p_ani_var_nome` VARCHAR(50), IN `p_ani_dec_peso` DECIMAL(8,3), IN `p_ani_var_raca` VARCHAR(50), IN `p_ani_cha_vivo` CHAR(1), INOUT `p_status` BOOLEAN, INOUT `p_msg` TEXT, INOUT `p_insert_id` INT(11))  SQL SECURITY INVOKER
    COMMENT 'Procedure de Insert'
BEGIN

  DECLARE v_existe boolean;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Erro ao executar o procedimento.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;

  -- VALIDAÇÕES
  IF p_ani_var_nome IS NULL THEN
    SET p_msg = concat(p_msg, 'Nome não informado.<br />');
  END IF;
  IF p_ani_cha_vivo IS NULL THEN
    SET p_msg = concat(p_msg, 'Status não informado.<br />');
  ELSE
    IF p_ani_cha_vivo NOT IN ('S','N') THEN
      SET p_msg = concat(p_msg, 'Status inválido.<br />');
    END IF;
  END IF;

  IF p_msg = '' THEN

    START TRANSACTION;

    INSERT INTO animal (ani_var_nome, ani_dec_peso, ani_var_raca, ani_cha_vivo)
    VALUES (p_ani_var_nome, p_ani_dec_peso, p_ani_var_raca, p_ani_cha_vivo);

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'Um novo registro foi inserido com sucesso.';
    SET p_insert_id = LAST_INSERT_ID();

  END IF;

END$$

CREATE PROCEDURE `sp_animal_upd` (IN `p_ani_int_codigo` INT, IN `p_ani_var_nome` VARCHAR(50), IN `p_ani_dec_peso` DECIMAL(8,3), IN `p_rac_int_codigo` INT, IN `p_prp_int_codigo` INT, IN `p_ani_cha_vivo` CHAR(1), INOUT `p_status` BOOLEAN, INOUT `p_msg` TEXT, INOUT `p_insert_id` INT(11))  SQL SECURITY INVOKER
    COMMENT 'Procedure de Update'
BEGIN

  DECLARE v_existe boolean;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Erro ao executar o procedimento.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;

  -- VALIDAÇÕES
  IF p_ani_int_codigo IS NULL THEN
    SET p_msg = concat(p_msg, 'Codigo não informado.<br />');
  END IF;

  IF p_msg = '' THEN

    START TRANSACTION;

    UPDATE animal SET ani_var_nome=p_ani_var_nome,
                      ani_dec_peso=p_ani_dec_peso,
                      rac_int_codigo=p_rac_int_codigo,
                      prp_int_codigo=p_prp_int_codigo,
                      ani_cha_vivo=p_ani_cha_vivo
	WHERE ani_int_codigo = p_ani_int_codigo;

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'Um registro foi atualizado com sucesso.';
    SET p_insert_id = LAST_INSERT_ID();

  END IF;

END$$

CREATE PROCEDURE `sp_usuario_del` (IN `p_usu_int_codigo` INT(11), INOUT `p_status` BOOLEAN, INOUT `p_msg` TEXT)  SQL SECURITY INVOKER
    COMMENT 'Procedure de Delete'
BEGIN

  DECLARE v_existe BOOLEAN;
  DECLARE v_row_count int DEFAULT 0;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Erro ao executar o procedimento.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;

  -- VALIDAÇÕES
  SELECT IF(count(1) = 0, FALSE, TRUE)
  INTO v_existe
  FROM usuario
  WHERE usu_int_codigo = p_usu_int_codigo;
  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Registro não encontrado.<br />');
  END IF;

  IF p_msg = '' THEN

    START TRANSACTION;

    DELETE FROM usuario
    WHERE usu_int_codigo = p_usu_int_codigo;

    SELECT ROW_COUNT() INTO v_row_count;

    COMMIT;

    IF (v_row_count > 0) THEN
      SET p_status = TRUE;
      SET p_msg = 'O Registro foi excluído com sucesso';
    END IF;

  END IF;

END$$

CREATE PROCEDURE `sp_usuario_ins` (IN `p_usu_var_nome` VARCHAR(50), IN `p_usu_var_email` VARCHAR(100), IN `p_usu_cha_status` CHAR(1), INOUT `p_status` BOOLEAN, INOUT `p_msg` TEXT, INOUT `p_insert_id` INT(11))  SQL SECURITY INVOKER
    COMMENT 'Procedure de Insert'
BEGIN

  DECLARE v_existe boolean;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Erro ao executar o procedimento.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;

  -- VALIDAÇÕES
  IF p_usu_var_nome IS NULL THEN
    SET p_msg = concat(p_msg, 'Nome não informado.<br />');
  END IF;
  IF p_usu_var_email IS NULL THEN
    SET p_msg = concat(p_msg, 'Email não informado.<br />');
  ELSE
    IF p_usu_var_email NOT REGEXP '^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$' THEN
      SET p_msg = concat(p_msg, 'Email em formato inválido.<br />');
    END IF;
  END IF;
  IF p_usu_cha_status IS NULL THEN
    SET p_msg = concat(p_msg, 'Status não informado.<br />');
  ELSE
    IF p_usu_cha_status NOT IN ('A','I') THEN
      SET p_msg = concat(p_msg, 'Status inválido.<br />');
    END IF;
  END IF;

  SELECT IF(COUNT(1) > 0, TRUE, FALSE)
  INTO v_existe
  FROM usuario
  WHERE usu_var_email = p_usu_var_email;
  IF v_existe THEN
    SET p_msg = concat(p_msg, 'Já existe usuário com este email.<br />');
  END IF;

  IF p_msg = '' THEN

    START TRANSACTION;

    INSERT INTO usuario (usu_var_nome, usu_var_email, usu_cha_status)
    VALUES (p_usu_var_nome, p_usu_var_email, p_usu_cha_status);

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'Um novo registro foi inserido com sucesso.';
    SET p_insert_id = LAST_INSERT_ID();

  END IF;

END$$

CREATE PROCEDURE `sp_usuario_upd` (IN `p_usu_int_codigo` INT(11), IN `p_usu_var_nome` VARCHAR(50), IN `p_usu_var_email` VARCHAR(100), IN `p_usu_cha_status` CHAR(1), INOUT `p_status` BOOLEAN, INOUT `p_msg` TEXT)  SQL SECURITY INVOKER
    COMMENT 'Procedure de Update'
BEGIN

  DECLARE v_existe BOOLEAN;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SET p_status = FALSE;
    SET p_msg = 'Erro ao executar o procedimento.';
  END;

  SET p_msg = '';
  SET p_status = FALSE;

  -- VALIDAÇÕES
  SELECT IF(count(1) = 0, FALSE, TRUE)
  INTO v_existe
  FROM usuario
  WHERE usu_int_codigo = p_usu_int_codigo;
  IF NOT v_existe THEN
    SET p_msg = concat(p_msg, 'Registro não encontrado.<br />');
  END IF;

  -- VALIDAÇÕES
  IF p_usu_int_codigo IS NULL THEN
    SET p_msg = concat(p_msg, 'Código não informado.<br />');
  END IF;
  IF p_usu_var_nome IS NULL THEN
    SET p_msg = concat(p_msg, 'Nome não informado.<br />');
  END IF;
  IF p_usu_var_email IS NULL THEN
    SET p_msg = concat(p_msg, 'Email não informado.<br />');
  ELSE
    IF p_usu_var_email NOT REGEXP '^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$' THEN
      SET p_msg = concat(p_msg, 'Email em formato inválido.<br />');
    END IF;
  END IF;
  IF p_usu_cha_status IS NULL THEN
    SET p_msg = concat(p_msg, 'Status não informado.<br />');
  ELSE
    IF p_usu_cha_status NOT IN ('A','I') THEN
      SET p_msg = concat(p_msg, 'Status inválido.<br />');
    END IF;
  END IF;

  SELECT IF(COUNT(1) > 0, TRUE, FALSE)
  INTO v_existe
  FROM usuario
  WHERE usu_var_email = p_usu_var_email
        AND usu_int_codigo <> p_usu_int_codigo;
  IF v_existe THEN
    SET p_msg = concat(p_msg, 'Já existe usuário com este email.<br />');
  END IF;

  IF p_msg = '' THEN

    START TRANSACTION;

    UPDATE usuario
    SET usu_var_nome = p_usu_var_nome,
        usu_var_email = p_usu_var_email,
        usu_cha_status = p_usu_cha_status
    WHERE usu_int_codigo = p_usu_int_codigo;

    COMMIT;

    SET p_status = TRUE;
    SET p_msg = 'O registro foi alterado com sucesso';

  END IF;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `animal`
--

CREATE TABLE `animal` (
  `ani_int_codigo` int(11) UNSIGNED NOT NULL COMMENT 'Código',
  `ani_var_nome` varchar(50) NOT NULL COMMENT 'Nome',
  `ani_cha_vivo` char(1) NOT NULL DEFAULT 'S' COMMENT 'Vivo|S:Sim;N:Não',
  `ani_dec_peso` decimal(8,3) DEFAULT NULL COMMENT 'Peso',
  `rac_int_codigo` int(11) NOT NULL COMMENT 'Raça',
  `prp_int_codigo` int(11) NOT NULL,
  `ani_dti_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Inclusão'
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8 COMMENT='Animal';

-- --------------------------------------------------------

--
-- Estrutura da tabela `animal_vacina`
--

CREATE TABLE `animal_vacina` (
  `anv_int_codigo` int(11) UNSIGNED NOT NULL COMMENT 'Codigo',
  `ani_int_codigo` int(11) UNSIGNED NOT NULL COMMENT 'Animal',
  `vac_int_codigo` int(11) UNSIGNED NOT NULL COMMENT 'Vacina',
  `anv_dat_programacao` date NOT NULL COMMENT 'Data Programacao',
  `anv_dti_aplicacao` datetime DEFAULT NULL COMMENT 'Data Aplicacao',
  `usu_int_codigo` int(11) UNSIGNED DEFAULT NULL COMMENT 'Usuario que aplicou',
  `anv_dti_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Inclusao'
) ENGINE=InnoDB AVG_ROW_LENGTH=16384 DEFAULT CHARSET=utf8 COMMENT='AnimalVacina||Agenda de Vacinação';

-- --------------------------------------------------------

--
-- Estrutura da tabela `proprietario`
--

CREATE TABLE `proprietario` (
  `prp_int_codigo` int(11) NOT NULL,
  `prp_var_nome` varchar(50) NOT NULL,
  `prp_var_email` varchar(100) NOT NULL,
  `prp_var_tel` varchar(15) NOT NULL,
  `prp_dti_inclusao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `proprietario`
--

INSERT INTO `proprietario` (`prp_int_codigo`, `prp_var_nome`, `prp_var_email`, `prp_var_tel`, `prp_dti_inclusao`) VALUES
(0, 'Felipe', 'calskn@alskdn.com', '71991640905', '2017-11-06 23:02:25');

--
-- Acionadores `proprietario`
--
DELIMITER $$
CREATE TRIGGER `proprietario_BEFORE_INSERT` BEFORE INSERT ON `proprietario` FOR EACH ROW BEGIN
set new.prp_dti_inclusao = now();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `raca`
--

CREATE TABLE `raca` (
  `rac_int_codigo` int(11) NOT NULL,
  `rac_var_nome` varchar(45) NOT NULL,
  `rac_dti_inclusao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `raca`
--

INSERT INTO `raca` (`rac_int_codigo`, `rac_var_nome`, `rac_dti_inclusao`) VALUES
(1, 'Siamês', '2017-11-06 20:41:15'),
(2, 'Vira-lata', '2017-11-06 20:41:15');

--
-- Acionadores `raca`
--
DELIMITER $$
CREATE TRIGGER `raca_BEFORE_INSERT` BEFORE INSERT ON `raca` FOR EACH ROW BEGIN
set new.rac_dti_inclusao = now();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `usu_int_codigo` int(11) UNSIGNED NOT NULL COMMENT 'Código',
  `usu_var_nome` varchar(50) NOT NULL COMMENT 'Nome',
  `usu_var_email` varchar(100) NOT NULL COMMENT 'Email',
  `usu_cha_status` char(1) NOT NULL DEFAULT 'A' COMMENT 'Status|A:Ativo;I:Inativo',
  `usu_dti_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Inclusão'
) ENGINE=InnoDB AVG_ROW_LENGTH=16384 DEFAULT CHARSET=utf8 COMMENT='Usuario';

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`usu_int_codigo`, `usu_var_nome`, `usu_var_email`, `usu_cha_status`, `usu_dti_inclusao`) VALUES
(1, 'Joe', 'joe@doe.com', 'A', '2016-03-25 16:23:14');

-- --------------------------------------------------------

--
-- Estrutura da tabela `vacina`
--

CREATE TABLE `vacina` (
  `vac_int_codigo` int(11) UNSIGNED NOT NULL COMMENT 'Código',
  `vac_var_nome` varchar(50) NOT NULL COMMENT 'Nome',
  `vac_dti_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Inclusão'
) ENGINE=InnoDB AVG_ROW_LENGTH=5461 DEFAULT CHARSET=utf8 COMMENT='Vacina';

--
-- Extraindo dados da tabela `vacina`
--

INSERT INTO `vacina` (`vac_int_codigo`, `vac_var_nome`, `vac_dti_inclusao`) VALUES
(1, 'Vanguard', '2016-03-25 18:03:35'),
(2, 'Anti-rábica', '2016-03-25 18:03:44'),
(3, 'Leshimune', '2016-03-25 18:04:15');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_animal`
--
CREATE TABLE `vw_animal` (
`ani_int_codigo` int(11) unsigned
,`ani_var_nome` varchar(50)
,`ani_dec_peso` decimal(8,3)
,`rac_var_nome` varchar(45)
,`prp_var_nome` varchar(50)
,`ani_cha_vivo` char(1)
,`ani_var_vivo` varchar(3)
,`ani_dti_inclusao` timestamp
,`ani_dtf_inclusao` varchar(24)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_usuario`
--
CREATE TABLE `vw_usuario` (
`usu_int_codigo` int(11) unsigned
,`usu_var_nome` varchar(50)
,`usu_var_email` varchar(100)
,`usu_cha_status` char(1)
,`usu_var_status` varchar(7)
,`usu_dti_inclusao` timestamp
,`usu_dtf_inclusao` varchar(24)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_vacina`
--
CREATE TABLE `vw_vacina` (
`vac_int_codigo` int(11) unsigned
,`vac_var_nome` varchar(50)
,`vac_dti_inclusao` timestamp
,`vac_dtf_inclusao` varchar(24)
);

-- --------------------------------------------------------

--
-- Structure for view `vw_animal`
--
DROP TABLE IF EXISTS `vw_animal`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_animal`  AS  select `animal`.`ani_int_codigo` AS `ani_int_codigo`,`animal`.`ani_var_nome` AS `ani_var_nome`,`animal`.`ani_dec_peso` AS `ani_dec_peso`,`raca`.`rac_var_nome` AS `rac_var_nome`,`proprietario`.`prp_var_nome` AS `prp_var_nome`,`animal`.`ani_cha_vivo` AS `ani_cha_vivo`,(case `animal`.`ani_cha_vivo` when 'S' then 'Sim' when 'N' then 'Não' end) AS `ani_var_vivo`,`animal`.`ani_dti_inclusao` AS `ani_dti_inclusao`,date_format(`animal`.`ani_dti_inclusao`,'%d/%m/%Y %H:%i:%s') AS `ani_dtf_inclusao` from ((`animal` left join `raca` on((`animal`.`rac_int_codigo` = `raca`.`rac_int_codigo`))) left join `proprietario` on((`animal`.`prp_int_codigo` = `proprietario`.`prp_int_codigo`))) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_usuario`
--
DROP TABLE IF EXISTS `vw_usuario`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW `vw_usuario`  AS  select `usuario`.`usu_int_codigo` AS `usu_int_codigo`,`usuario`.`usu_var_nome` AS `usu_var_nome`,`usuario`.`usu_var_email` AS `usu_var_email`,`usuario`.`usu_cha_status` AS `usu_cha_status`,(case `usuario`.`usu_cha_status` when 'A' then 'Ativo' when 'I' then 'Inativo' end) AS `usu_var_status`,`usuario`.`usu_dti_inclusao` AS `usu_dti_inclusao`,date_format(`usuario`.`usu_dti_inclusao`,'%d/%m/%Y %H:%i:%s') AS `usu_dtf_inclusao` from `usuario` ;

-- --------------------------------------------------------

--
-- Structure for view `vw_vacina`
--
DROP TABLE IF EXISTS `vw_vacina`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW `vw_vacina`  AS  select `vacina`.`vac_int_codigo` AS `vac_int_codigo`,`vacina`.`vac_var_nome` AS `vac_var_nome`,`vacina`.`vac_dti_inclusao` AS `vac_dti_inclusao`,date_format(`vacina`.`vac_dti_inclusao`,'%d/%m/%Y %H:%i:%s') AS `vac_dtf_inclusao` from `vacina` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`ani_int_codigo`);

--
-- Indexes for table `animal_vacina`
--
ALTER TABLE `animal_vacina`
  ADD PRIMARY KEY (`anv_int_codigo`),
  ADD KEY `FK_animal_vacina_animal_ani_int_codigo` (`ani_int_codigo`),
  ADD KEY `FK_animal_vacina_usuario_usu_int_codigo` (`usu_int_codigo`),
  ADD KEY `FK_animal_vacina_vacina_vac_int_codigo` (`vac_int_codigo`);

--
-- Indexes for table `proprietario`
--
ALTER TABLE `proprietario`
  ADD PRIMARY KEY (`prp_int_codigo`);

--
-- Indexes for table `raca`
--
ALTER TABLE `raca`
  ADD PRIMARY KEY (`rac_int_codigo`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usu_int_codigo`),
  ADD UNIQUE KEY `UK_usuario_usu_var_email` (`usu_var_email`),
  ADD KEY `IDX_usuario_usu_dti_inclusao` (`usu_dti_inclusao`),
  ADD KEY `IDX_usuario_usu_var_nome` (`usu_var_nome`);

--
-- Indexes for table `vacina`
--
ALTER TABLE `vacina`
  ADD PRIMARY KEY (`vac_int_codigo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `animal`
--
ALTER TABLE `animal`
  MODIFY `ani_int_codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Código';
--
-- AUTO_INCREMENT for table `animal_vacina`
--
ALTER TABLE `animal_vacina`
  MODIFY `anv_int_codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Codigo';
--
-- AUTO_INCREMENT for table `raca`
--
ALTER TABLE `raca`
  MODIFY `rac_int_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usu_int_codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Código', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `vacina`
--
ALTER TABLE `vacina`
  MODIFY `vac_int_codigo` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Código', AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `animal_vacina`
--
ALTER TABLE `animal_vacina`
  ADD CONSTRAINT `FK_animal_vacina_animal_ani_int_codigo` FOREIGN KEY (`ani_int_codigo`) REFERENCES `animal` (`ani_int_codigo`),
  ADD CONSTRAINT `FK_animal_vacina_usuario_usu_int_codigo` FOREIGN KEY (`usu_int_codigo`) REFERENCES `usuario` (`usu_int_codigo`),
  ADD CONSTRAINT `FK_animal_vacina_vacina_vac_int_codigo` FOREIGN KEY (`vac_int_codigo`) REFERENCES `vacina` (`vac_int_codigo`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

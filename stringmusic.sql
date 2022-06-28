-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28-Jun-2022 às 20:24
-- Versão do servidor: 10.4.21-MariaDB
-- versão do PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `stringmusic`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getCharts` (IN `codigo` INT, OUT `mes` INT, OUT `inte` INT, OUT `serv` INT, OUT `inst` INT, IN `qt_mes` INT)  begin
    	SELECT month(co.dt_compra), count(i.cd_interpretacao) into mes, inte
        	from tb_interpretacao as i
                join tb_usuario as u
                on u.cd_usuario = i.cd_usuario
                    join tb_carrinho as c
                    on i.cd_interpretacao = c.cd_interpretacao
                        join tb_compra as co
                        on c.cd_carrinho = co.cd_carrinho
                            where u.cd_usuario = codigo and month(co.dt_compra) = qt_mes; 
		SELECT count(s.cd_servico) into serv
        	from tb_servico as s
                join tb_usuario as u
                on u.cd_usuario = s.cd_usuario
                    join tb_carrinho as c
                    on s.cd_servico = c.cd_servico
                        join tb_compra as co
                        on c.cd_carrinho = co.cd_carrinho
                            where u.cd_usuario = codigo and month(co.dt_compra) = qt_mes;
		SELECT count(ins.cd_instrumento) into inst
        	from tb_instrumento as ins
                join tb_usuario as u
                on u.cd_usuario = ins.cd_usuario
                    join tb_carrinho as c
                    on ins.cd_instrumento = c.cd_instrumento
                        join tb_compra as co
                        on c.cd_carrinho = co.cd_carrinho
                            where u.cd_usuario = codigo and month(co.dt_compra) = qt_mes;
    end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getVendas` (IN `cod` INT, OUT `venda` INT)  begin
    		SELECT count(i.cd_interpretacao) + (SELECT count(i.cd_instrumento)
                                        	from tb_instrumento as i 
                                        		join tb_usuario as u 
                                        			on u.cd_usuario = i.cd_usuario 
                                        				join tb_carrinho as c 
                                        					on i.cd_instrumento = c.cd_instrumento 
                                        						join tb_compra as co 
                                        							on c.cd_carrinho = co.cd_carrinho 
                                        								where u.cd_usuario = cod and co.dt_entrega is NULL) + 
                                       (SELECT count(i.cd_servico) 
                                        	from tb_servico as i 
                                        		join tb_usuario as u 
                                        			on u.cd_usuario = i.cd_usuario 
                                        				join tb_carrinho as c 
                                        					on i.cd_servico = c.cd_servico 
                                        						join tb_compra as co 
                                        							on c.cd_carrinho = co.cd_carrinho 
                                        								where u.cd_usuario = cod and co.dt_entrega is NULL) into venda 
			from tb_interpretacao as i 
            	join tb_usuario as u 
                	on u.cd_usuario = i.cd_usuario 
                    	join tb_carrinho as c 
                        	on i.cd_interpretacao = c.cd_interpretacao 
                            	join tb_compra as co 
                                	on c.cd_carrinho = co.cd_carrinho 
                                    	where u.cd_usuario = cod and co.dt_entrega is NULL;
    	end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_arquivo`
--

CREATE TABLE `tb_arquivo` (
  `cd_arquivo` int(11) NOT NULL,
  `nm_arquivo` varchar(80) NOT NULL,
  `nm_path` varchar(100) NOT NULL,
  `dt_arquivo` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_arquivo`
--

INSERT INTO `tb_arquivo` (`cd_arquivo`, `nm_arquivo`, `nm_path`, `dt_arquivo`) VALUES
(1, 'yanntiersen.pdf', 'arq_pdf/628a8963593bd.pdf', '2022-05-22 16:05:07');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_carrinho`
--

CREATE TABLE `tb_carrinho` (
  `cd_carrinho` int(11) NOT NULL,
  `qt_carrinho` int(11) NOT NULL,
  `nm_tipo` tinyint(1) NOT NULL,
  `nm_inativo` tinyint(1) DEFAULT 0,
  `cd_instrumento` int(11) DEFAULT NULL,
  `cd_interpretacao` int(11) DEFAULT NULL,
  `cd_servico` int(11) DEFAULT NULL,
  `cd_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_carrinho`
--

INSERT INTO `tb_carrinho` (`cd_carrinho`, `qt_carrinho`, `nm_tipo`, `nm_inativo`, `cd_instrumento`, `cd_interpretacao`, `cd_servico`, `cd_usuario`) VALUES
(1, 1, 1, 1, NULL, 1, NULL, 2),
(2, 1, 1, 1, NULL, 2, NULL, 2),
(3, 2, 1, 1, NULL, 3, NULL, 2),
(4, 1, 2, 1, NULL, NULL, 1, 2),
(5, 1, 2, 1, NULL, NULL, 1, 2),
(6, 1, 1, 1, NULL, 1, NULL, 2),
(7, 2, 1, 1, NULL, 1, NULL, 2),
(8, 1, 1, 1, NULL, 2, NULL, 2),
(9, 1, 1, 1, NULL, 3, NULL, 2),
(10, 1, 3, 1, 1, NULL, NULL, 2),
(11, 1, 2, 1, NULL, NULL, 1, 2),
(12, 1, 2, 1, NULL, NULL, 1, 2),
(13, 1, 1, 1, NULL, 2, NULL, 1),
(14, 1, 2, 1, NULL, NULL, 2, 1),
(15, 1, 2, 1, NULL, NULL, 1, 3),
(16, 1, 2, 1, NULL, NULL, 2, 3),
(17, 1, 3, 1, 4, NULL, NULL, 4),
(18, 1, 3, 1, 2, NULL, NULL, 4),
(19, 1, 2, 1, NULL, NULL, 2, 4),
(20, 1, 1, 1, NULL, 2, NULL, 3),
(21, 1, 3, 1, 3, NULL, NULL, 9),
(22, 1, 3, 1, 4, NULL, NULL, 9),
(23, 1, 1, 1, NULL, 5, NULL, 9),
(24, 1, 2, 1, NULL, NULL, 2, 9),
(25, 1, 1, 1, NULL, 5, NULL, 9),
(26, 1, 1, 1, NULL, 1, NULL, 3),
(27, 1, 3, 1, 1, NULL, NULL, 4),
(28, 1, 1, 1, NULL, 4, NULL, 2),
(29, 1, 2, 1, NULL, NULL, 6, 3),
(30, 1, 3, 1, 6, NULL, NULL, 3),
(31, 1, 1, 1, NULL, 6, NULL, 3),
(32, 1, 2, 1, NULL, NULL, 5, 3),
(33, 1, 2, 1, NULL, NULL, 4, 3),
(34, 1, 3, 1, 8, NULL, NULL, 3),
(35, 1, 2, 1, NULL, NULL, 3, 3),
(36, 1, 3, 1, 5, NULL, NULL, 3),
(37, 1, 3, 1, 7, NULL, NULL, 3),
(38, 1, 1, 1, NULL, 2, NULL, 3),
(39, 1, 3, 1, 3, NULL, NULL, 3),
(40, 2, 3, 1, 3, NULL, NULL, 3),
(41, 1, 2, 1, NULL, NULL, 3, 3),
(42, 1, 1, 1, NULL, 3, NULL, 3),
(43, 1, 1, 1, NULL, 4, NULL, 3),
(44, 1, 3, 1, 6, NULL, NULL, 3),
(45, 1, 2, 1, NULL, NULL, 2, 3),
(46, 1, 3, 1, 2, NULL, NULL, 3),
(47, 1, 1, 1, NULL, 6, NULL, 3),
(48, 1, 3, 1, 6, NULL, NULL, 3),
(49, 1, 2, 1, NULL, NULL, 3, 3),
(50, 1, 2, 1, NULL, NULL, 1, 3),
(51, 1, 3, 1, 8, NULL, NULL, 3),
(52, 1, 1, 1, NULL, 1, NULL, 3),
(53, 1, 1, 1, NULL, 2, NULL, 3),
(54, 1, 2, 1, NULL, NULL, 3, 3),
(55, 1, 3, 1, 3, NULL, NULL, 3),
(56, 1, 1, 1, NULL, 2, NULL, 3),
(57, 1, 2, 1, NULL, NULL, 4, 3),
(58, 1, 1, 1, NULL, 2, NULL, 3),
(59, 1, 2, 1, NULL, NULL, 2, 3),
(60, 1, 3, 1, 4, NULL, NULL, 3),
(61, 1, 1, 1, NULL, 1, NULL, 3),
(62, 1, 2, 1, NULL, NULL, 6, 3),
(63, 1, 1, 1, NULL, 6, NULL, 3),
(64, 1, 3, 1, 2, NULL, NULL, 3),
(65, 1, 2, 1, NULL, NULL, 1, 3),
(66, 1, 3, 1, 1, NULL, NULL, 3),
(67, 1, 1, 1, NULL, 8, NULL, 3),
(68, 1, 2, 1, NULL, NULL, 6, 5),
(69, 1, 1, 1, NULL, 8, NULL, 5),
(70, 4, 1, 1, NULL, 3, NULL, 3),
(71, 3, 1, 1, NULL, 3, NULL, 3),
(72, 1, 1, 1, NULL, 3, NULL, 3),
(73, 1, 1, 1, NULL, 3, NULL, 3),
(74, 1, 1, 1, NULL, 1, NULL, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_compra`
--

CREATE TABLE `tb_compra` (
  `cd_compra` int(11) NOT NULL,
  `dt_compra` datetime NOT NULL,
  `vl_compra` varchar(12) NOT NULL,
  `nm_pagamento` char(1) NOT NULL,
  `nm_pagante` varchar(50) NOT NULL,
  `nm_email` varchar(100) NOT NULL,
  `nm_token` varchar(50) DEFAULT NULL,
  `dt_entrega` date DEFAULT NULL,
  `cd_usuario` int(11) DEFAULT NULL,
  `cd_carrinho` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_compra`
--

INSERT INTO `tb_compra` (`cd_compra`, `dt_compra`, `vl_compra`, `nm_pagamento`, `nm_pagante`, `nm_email`, `nm_token`, `dt_entrega`, `cd_usuario`, `cd_carrinho`) VALUES
(1, '2022-04-03 00:00:00', '620', 'c', 'APRO', 'test_user_19653727@testuser.com', '8.0725414096775E+244', NULL, 2, 1),
(2, '2022-04-03 00:00:00', '620', 'c', 'APRO', 'test_user_19653727@testuser.com', '8.0725414096775E+244', NULL, 2, 2),
(3, '2022-04-03 00:00:00', '620', 'c', 'APRO', 'test_user_19653727@testuser.com', '8.0725414096775E+244', NULL, 2, 3),
(4, '2022-04-03 00:00:00', '620', 'c', 'APRO', 'test_user_19653727@testuser.com', '8.0725414096775E+244', NULL, 2, 4),
(5, '2022-04-04 00:00:00', '200', 'c', 'APRO', 'test_user_19653727@testuser.com', '2.150603630692E+250', NULL, 2, 5),
(6, '2022-04-04 00:00:00', '10', 'c', 'APRO', 'test_user_19653727@testuser.com', '5.5652256792503E+241', NULL, 2, 6),
(7, '2022-04-04 00:00:00', '250', 'c', 'APRO', 'test_user_19653727@testuser.com', '5.5686529912854E+243', NULL, 2, 7),
(8, '2022-04-04 00:00:00', '250', 'c', 'APRO', 'test_user_19653727@testuser.com', '5.5686529912854E+243', NULL, 2, 8),
(9, '2022-04-04 00:00:00', '250', 'c', 'APRO', 'test_user_19653727@testuser.com', '5.5686529912854E+243', NULL, 2, 9),
(10, '2022-04-05 00:00:00', '200', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.5671569900578E+242', NULL, 2, 12),
(11, '2022-04-12 00:00:00', '300', 'c', 'APRO', 'test_user_19653727@testuser.com', '8.7254687440276E+245', NULL, 3, 15),
(12, '2022-04-12 00:00:00', '300', 'c', 'APRO', 'test_user_19653727@testuser.com', '8.7254687440276E+245', NULL, 3, 16),
(13, '2022-04-12 00:00:00', '1500', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.3698273950621E+248', NULL, 4, 17),
(14, '2022-04-12 00:00:00', '1500', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.3698273950621E+248', NULL, 4, 18),
(15, '2022-04-12 00:00:00', '1500', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.3698273950621E+248', NULL, 4, 19),
(16, '2022-04-12 00:00:00', '50', 'c', 'APRO', 'test_user_19653727@testuser.com', '5.3811388790204E+248', NULL, 3, 20),
(17, '2022-04-12 00:00:00', '50', 'c', 'APRO', 'test_user_19653727@testuser.com', '9.2431369281416E+233', NULL, 9, 21),
(18, '2022-04-12 00:00:00', '900', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.6165300276846E+243', NULL, 9, 22),
(19, '2022-04-12 00:00:00', '220', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.0866246817911E+240', NULL, 9, 23),
(20, '2022-04-12 00:00:00', '220', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.0866246817911E+240', NULL, 9, 24),
(21, '2022-04-12 00:00:00', '10', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.3698113436124E+248', NULL, 3, 26),
(22, '2022-04-15 00:00:00', '100', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.5298127043468E+242', NULL, 2, 28),
(23, '2022-04-18 00:00:00', '120', 'c', 'APRO', 'test_user_19653727@testuser.com', '6.7029249340849E+241', NULL, 3, 29),
(24, '2022-04-18 00:00:00', '900', 'c', 'APRO', 'test_user_19653727@testuser.com', '8.7249144001003E+245', NULL, 3, 30),
(25, '2022-04-18 00:00:00', '800', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.1416394936804E+235', NULL, 3, 31),
(26, '2022-04-22 19:23:06', '200', 'c', 'APRO', 'test_user_19653727@testuser.com', '2.3207758667578E+239', NULL, 3, 32),
(27, '2022-04-22 19:52:12', '100', 'c', 'APRO', 'test_user_19653727@testuser.com', '5.3724084771138E+248', NULL, 3, 33),
(28, '2022-04-22 20:43:01', '500', 'c', 'APRO', 'test_user_19653727@testuser.com', '8.169277566361E+238', NULL, 3, 34),
(29, '2022-04-22 20:47:55', '120', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.5340282494652E+242', NULL, 3, 35),
(30, '2022-04-22 21:00:10', '205', 'c', 'APRO', 'test_user_19653727@testuser.com', '4.1080337365542E+233', NULL, 3, 37),
(31, '2022-04-22 21:03:16', '50', 'c', 'APRO', 'test_user_19653727@testuser.com', '5.3732710225431E+248', NULL, 3, 38),
(32, '2022-04-22 21:06:33', '50', 'c', 'APRO', 'test_user_19653727@testuser.com', '5.3724470385926E+248', NULL, 3, 39),
(33, '2022-04-22 21:18:00', '100', 'c', 'APRO', 'test_user_19653727@testuser.com', '4.4069704903966E+246', NULL, 3, 40),
(34, '2022-04-22 21:25:37', '120', 'c', 'APRO', 'test_user_19653727@testuser.com', '3.9758774449414E+234', NULL, 3, 41),
(35, '2022-04-22 21:27:58', '180', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.1862498453413E+247', NULL, 3, 42),
(36, '2022-04-22 21:30:29', '100', 'c', 'APRO', 'test_user_19653727@testuser.com', '5.5645257630833E+241', NULL, 3, 43),
(37, '2022-04-22 21:42:00', '900', 'c', 'APRO', 'test_user_19653727@testuser.com', '2.0544875864028E+239', NULL, 3, 44),
(38, '2022-04-22 21:43:00', '100', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.4996259754765E+237', NULL, 3, 45),
(39, '2022-04-24 22:45:26', '500', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.1862527156362E+247', '2022-05-12', 3, 46),
(40, '2022-04-24 22:48:47', '800', 'c', 'APRO', 'test_user_19653727@testuser.com', '6.046062584636E+242', NULL, 3, 47),
(41, '2022-04-24 23:20:27', '900', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.3698127598132E+248', NULL, 3, 48),
(42, '2022-04-24 23:21:46', '120', 'c', 'APRO', 'test_user_19653727@testuser.com', '3.5344792627584E+246', NULL, 3, 49),
(43, '2022-04-24 23:30:41', '200', 'c', 'APRO', 'test_user_19653727@testuser.com', '5.3724084771134E+248', '2022-05-20', 3, 50),
(44, '2022-04-24 23:43:20', '500', 'c', 'APRO', 'test_user_19653727@testuser.com', '3.9398773297851E+240', NULL, 3, 51),
(45, '2022-04-25 18:26:54', '10', 'c', 'APRO', 'test_user_19653727@testuser.com', '7.1742188535311E+243', NULL, 3, 52),
(46, '2022-04-25 19:26:16', '50', 'c', 'APRO', 'test_user_19653727@testuser.com', '3.5350282222588E+246', '2022-05-20', 3, 53),
(47, '2022-04-26 20:01:33', '120', 'c', 'APRO', 'test_user_19653727@testuser.com', '8.0850328432281E+244', NULL, 3, 54),
(48, '2022-05-03 19:26:40', '10', 'c', 'APRO', 'test_user_19653727@testuser.com', '5.3747039452023E+248', '2022-05-12', 3, 61),
(49, '2022-05-10 19:14:10', '120', 'c', 'APRO', 'test_user_19653727@testuser.com', '8.0708859627529E+244', NULL, 3, 62),
(50, '2022-05-10 19:57:24', '800', 'c', 'APRO', 'test_user_19653727@testuser.com', '8.7410906470923E+245', NULL, 3, 63),
(51, '2022-05-17 15:02:14', '500', 'c', 'APRO', 'test_user_19653727@testuser.com', '2.8593439195602E+241', NULL, 3, 64),
(52, '2022-05-17 15:27:12', '200', 'c', 'APRO', 'test_user_19653727@testuser.com', '1.1812616045012E+241', NULL, 3, 65),
(53, '2022-05-17 15:29:07', '18000', 'c', 'APRO', 'test_user_19653727@testuser.com', '5.4896099995345E+242', NULL, 3, 66),
(54, '2022-05-22 18:38:05', '10', 'c', 'APRO', 'test_user_19653727@testuser.com', '6.3623266571319E+241', NULL, 3, 67),
(55, '2022-05-23 19:22:29', '130', 'c', 'APRO', 'test_user_19653727@testuser.com', '3.9117196299673E+243', NULL, 5, 68),
(56, '2022-05-23 19:22:29', '130', 'c', 'APRO', 'test_user_19653727@testuser.com', '3.9117196299673E+243', NULL, 5, 69),
(57, '2022-06-07 20:25:50', '720', 'c', 'APRO', 'test_user_19653727@testuser.com', '2.6903901969569E+238', NULL, 3, 70),
(58, '2022-06-07 20:28:55', '540', 'c', 'APRO', 'test_user_19653727@testuser.com', '4.7021993340071E+233', NULL, 3, 71),
(59, '2022-06-07 20:29:51', '180', 'c', 'APRO', 'test_user_19653727@testuser.com', '2.5928240492531E+227', NULL, 3, 72),
(60, '2022-06-14 19:17:42', '180', 'c', 'APRO', 'test_user_19653727@testuser.com', '2.8146479933719E+241', NULL, 3, 73),
(61, '2022-06-14 20:18:34', '10', 'c', 'APRO', 'test_user_19653727@testuser.com', '3.8564893063717E+243', NULL, 3, 74);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_endereco`
--

CREATE TABLE `tb_endereco` (
  `cd_endereco` int(11) NOT NULL,
  `sg_uf` char(2) NOT NULL,
  `nm_cidade` varchar(50) NOT NULL,
  `nm_bairro` varchar(50) NOT NULL,
  `nm_rua` varchar(60) NOT NULL,
  `nm_cep` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_endereco`
--

INSERT INTO `tb_endereco` (`cd_endereco`, `sg_uf`, `nm_cidade`, `nm_bairro`, `nm_rua`, `nm_cep`) VALUES
(1, 'SP', 'São Vicente', 'Parque São Vicente', 'Rua Armando Vitório Bei', '11365030'),
(2, 'SP', 'São Vicente', 'Jardim Guassu', 'Rua Santo Antônio', '11370540'),
(3, 'SP', 'Santos', 'Gonzaga', 'Avenida Presidente Wilson', '11065200'),
(4, 'SP', 'São Vicente', 'Vila Nossa Senhora de Fátima', 'Rua Antero de Moura', '11355220'),
(5, 'SP', 'São Vicente', 'Samarita', 'Avenida Teresina', '11345020'),
(6, 'DF', 'Brasília', 'Recanto das Emas', 'Quadra 603 Conjunto 11, ', '72640311'),
(7, 'SP', 'São Vicente', 'Catiapoa', 'Rua Mecanizada Nove Mil Novecentos e Noventa e Quatro', '11390570'),
(8, 'SP', 'Santos', 'Gonzaga', 'Avenida Presidente Wilson', '11065200'),
(9, 'SP', 'Santos', 'Gonzaga', 'Avenida Presidente Wilson', '11065200'),
(10, 'RO', 'Porto Velho', 'Novo Horizonte', 'Rua João Paulo I', '76810-15'),
(11, 'PB', 'João Pessoa', 'Gramame', 'Rua Monsenhor Eusébio Oliveira', '58068-24');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_feedback`
--

CREATE TABLE `tb_feedback` (
  `cd_feedback` int(11) NOT NULL,
  `qt_feedback` int(11) NOT NULL,
  `dt_feedback` datetime NOT NULL,
  `dt_modificado` datetime DEFAULT NULL,
  `nm_feedback` varchar(150) NOT NULL,
  `cd_usuario` int(11) DEFAULT NULL,
  `cd_instrumento` int(11) DEFAULT NULL,
  `cd_servico` int(11) DEFAULT NULL,
  `cd_interpretacao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_feedback`
--

INSERT INTO `tb_feedback` (`cd_feedback`, `qt_feedback`, `dt_feedback`, `dt_modificado`, `nm_feedback`, `cd_usuario`, `cd_instrumento`, `cd_servico`, `cd_interpretacao`) VALUES
(1, 5, '2022-04-10 14:57:15', NULL, 'Gostei muito do produto, música realmente boa!', 2, NULL, NULL, 1),
(2, 4, '2022-04-12 10:45:39', NULL, 'Produto deixou a desejar, porém ainda é muito bom!\r\n', 2, NULL, NULL, 2),
(3, 1, '2022-04-12 11:37:10', NULL, 'Produto veio com defeito, estou marcando o reembolso.', 4, 4, NULL, NULL),
(4, 3, '2022-04-12 12:14:50', NULL, 'Esperava mais do músico, não prestou um serviço tão bom.', 3, NULL, 1, NULL),
(5, 5, '2022-04-12 12:16:28', NULL, 'Gostei muito do produto!!', 3, NULL, NULL, 2),
(6, 3, '2022-04-12 14:19:25', NULL, 'Produto bom, pena que as maracas vieram amassadas.', 9, 3, NULL, NULL),
(7, 4, '2022-04-12 14:22:30', NULL, 'Produto veio em ótimo estado. Mas o bocal veio amassado.', 9, 4, NULL, NULL),
(8, 4, '2022-04-12 14:29:26', NULL, 'Músico muito bom!!', 9, NULL, 2, NULL),
(9, 5, '2022-04-12 14:31:03', NULL, 'Album muito bom!! Chegou o produto via email rapidinho.', 9, NULL, NULL, 5),
(10, 1, '2022-04-12 19:42:45', NULL, 'Não gostei muito não.', 3, NULL, NULL, 1),
(11, 3, '2022-05-08 15:13:51', NULL, 'Produto bom, porém demorou pra chegar!', 3, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_fundo`
--

CREATE TABLE `tb_fundo` (
  `cd_fundo` int(11) NOT NULL,
  `nm_fundo` varchar(100) NOT NULL,
  `cd_imagem` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_fundo`
--

INSERT INTO `tb_fundo` (`cd_fundo`, `nm_fundo`, `cd_imagem`) VALUES
(1, 'Singed_0.jpg', 16),
(2, 'captura de tela.png', 18);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_imagem`
--

CREATE TABLE `tb_imagem` (
  `cd_imagem` int(11) NOT NULL,
  `nm_imagem` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL,
  `dt_imagem` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_imagem`
--

INSERT INTO `tb_imagem` (`cd_imagem`, `nm_imagem`, `path`, `dt_imagem`) VALUES
(1, 'Powerwolf.jpg', 'img/6248b57e0c68b.jpg', '2022-04-02 17:43:42'),
(2, 'Beatles.png', 'img/6248b66658a2d.png', '2022-04-02 17:47:34'),
(3, 'Taeyeon.jpg', 'img/6248b8150f7a6.jpg', '2022-04-02 17:54:45'),
(4, 'Guitarra.jpg', 'img/6248b8598d77a.jpg', '2022-04-02 17:55:53'),
(5, 'Taeyeonperfil.jpg', 'img/6248b98d0e3f2.jpg', '2022-04-02 18:01:01'),
(6, 'WhatsApp Image 2021-02-19 at 15.49.45.jpeg', 'img/624a219201a02.jpeg', '2022-04-03 19:37:06'),
(7, 'índice.jpg', 'img/624b7876b0e25.jpg', '2022-04-04 20:00:06'),
(8, 'violino.jpg', 'img/624c8896da879.jpg', '2022-04-05 15:21:10'),
(9, 'pandeiro.jpg', 'img/624c896c4f91e.jpg', '2022-04-05 15:24:44'),
(10, 'flauta.jpg', 'img/624c8aec9b8bd.jpg', '2022-04-05 15:31:08'),
(11, 'chocoalho.png', 'img/624c8b58b3229.png', '2022-04-05 15:32:56'),
(12, 'tchaikosvky.jpg', 'img/624c8d107d835.jpg', '2022-04-05 15:40:16'),
(13, '155.jpg', 'img/624c8dc2cea4b.jpg', '2022-04-05 15:43:14'),
(14, 'orquestra.jpg', 'img/624c8f0883b3f.jpg', '2022-04-05 15:48:40'),
(15, 'Singed_0.jpg', 'img/624c902d63749.jpg', '2022-04-05 15:53:33'),
(16, 'Singed_0.jpg', 'img/624c90404047c.jpg', '2022-04-05 15:53:52'),
(17, 'Cozinha-bege-com-geladeira-colorida-verde-Projeto-de-Lojas-KD-1.jpg', 'img/624cc48eb23ff.jpg', '2022-04-05 19:37:02'),
(18, 'captura de tela.png', 'img/624cc4a0cb81c.png', '2022-04-05 19:37:20'),
(19, 'user.jpeg', 'imgs/user.jpeg', '2022-04-12 11:52:12'),
(20, 'teclado.jpg', 'img/6255b4307c768.jpg', '2022-04-12 14:17:36'),
(21, 'led_zeppelim.jpg', 'img/6255b616e1a70.jpg', '2022-04-12 14:25:42'),
(22, 'produzir_musica.jpg', 'img/6255b6b8a43e6.jpg', '2022-04-12 14:28:24'),
(23, 'editopodcast.jpg', 'img/6255b9c4dfbec.jpg', '2022-04-12 14:41:24'),
(24, 'sousuavoz.jpg', 'img/6255b9ef169c4.jpg', '2022-04-12 14:42:07'),
(25, 'guitarrada.png', 'img/6255bb3151767.png', '2022-04-12 14:47:29'),
(26, 'bateria.jpg', 'img/6255bbcf7d337.jpg', '2022-04-12 14:50:07'),
(27, 'editoaudio.jpg', 'img/6255bd1528908.jpg', '2022-04-12 14:55:33'),
(28, 'tamborim.jpg', 'img/6255c0d3da011.jpg', '2022-04-12 15:11:31'),
(29, 'partituras.png', 'img/6255ffaa4a91c.png', '2022-04-12 19:39:38'),
(30, 'tamborim.jpg', 'img/625600924fecd.jpg', '2022-04-12 19:43:30'),
(31, 'capa_mozart.jpg', 'img/627aed7416037.jpg', '2022-05-10 19:55:48'),
(32, 'comptine-d-un-autre-ete.jpg', 'img/628a88fbda907.jpg', '2022-05-22 16:03:23'),
(33, 'comptine-d-un-autre-ete.jpg', 'img/628a8963181c8.jpg', '2022-05-22 16:05:07');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_instrumento`
--

CREATE TABLE `tb_instrumento` (
  `cd_instrumento` int(11) NOT NULL,
  `nm_instrumento` varchar(80) NOT NULL,
  `ds_instrumento` varchar(200) NOT NULL,
  `dt_instrumento` datetime NOT NULL,
  `vl_instrumento` varchar(12) DEFAULT NULL,
  `nm_inativo` tinyint(1) DEFAULT NULL,
  `cd_usuario` int(11) DEFAULT NULL,
  `cd_imagem` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_instrumento`
--

INSERT INTO `tb_instrumento` (`cd_instrumento`, `nm_instrumento`, `ds_instrumento`, `dt_instrumento`, `vl_instrumento`, `nm_inativo`, `cd_usuario`, `cd_imagem`) VALUES
(1, 'Piano de Cauda Branco', 'Piano branco de Cauda em ótimo estado!!', '2022-04-04 20:00:06', '18,000.00', 0, 1, 7),
(2, 'Violino Stradivarius', 'Violino Stradivarius em ótimo estado', '2022-04-05 15:21:11', '500.00', 0, 1, 8),
(3, 'Pandeiro Profissional', 'Pandeiro profissional da marca Izzo com 4 maracas', '2022-04-05 15:24:44', '50.00', 0, 1, 9),
(4, 'Flauta Transversal', 'Flauta Transversal Yamaha usada', '2022-04-05 15:31:08', '900.00', 0, 3, 10),
(5, 'Maraca Nordestina', 'Maraca nordestina vermelha', '2022-04-05 15:32:56', '80.00', 0, 3, 11),
(6, 'Teclado Yamaha', 'Teclado yamaha 57 teclas', '2022-04-12 14:17:36', '900.00', 0, 9, 20),
(7, 'Vendo guitarra profissional', 'Guitarra profissional preta acompanha palhetas', '2022-04-12 14:47:29', '205.00', 0, 5, 25),
(8, 'Bateria vermelha', 'Vendo bateria com 3 pratos, percussão e 4 tambores. Acompanha baqueta', '2022-04-12 14:50:07', '500.00', 0, 1, 26),
(9, 'Tamborim Contemporânea', 'Kit Tamborim da Contemporânea', '2022-04-12 15:11:32', '90.00', 0, 7, 28),
(10, 'Tamborim Contemporânea', 'Kit Tamborim da Contemporânea', '2022-04-12 19:43:30', '120.00', 0, 3, 30);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_interpretacao`
--

CREATE TABLE `tb_interpretacao` (
  `cd_interpretacao` int(11) NOT NULL,
  `nm_interpretacao` varchar(50) NOT NULL,
  `ds_interpretacao` varchar(200) NOT NULL,
  `dt_interpretacao` datetime NOT NULL,
  `vl_interpretacao` varchar(12) NOT NULL,
  `nm_inativo` tinyint(1) DEFAULT NULL,
  `nm_genero` varchar(50) NOT NULL,
  `sg_tipo` char(1) NOT NULL,
  `cd_usuario` int(11) DEFAULT NULL,
  `cd_imagem` int(11) DEFAULT NULL,
  `cd_arquivo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_interpretacao`
--

INSERT INTO `tb_interpretacao` (`cd_interpretacao`, `nm_interpretacao`, `ds_interpretacao`, `dt_interpretacao`, `vl_interpretacao`, `nm_inativo`, `nm_genero`, `sg_tipo`, `cd_usuario`, `cd_imagem`, `cd_arquivo`) VALUES
(1, 'Álbum PowerWolf', 'Album Blessed and Possessed', '2022-04-02 17:43:42', '10.00', 0, 'rock', 'f', 1, 1, NULL),
(2, 'Beatles', 'Beatles', '2022-04-02 17:47:34', '50.00', 0, 'rock', 'f', 1, 2, NULL),
(3, 'INVU', 'Album da Taeyeon', '2022-04-02 17:54:45', '180.00', 0, 'pop', 'f', 1, 3, NULL),
(4, 'Concerto N° 1', 'Concerto N°1 de Tchaiskovsky para piano', '2022-04-05 15:40:17', '100.00', 0, 'clas', 'f', 4, 12, NULL),
(5, 'Álbum .155', 'Álbum .155 do CostaGold', '2022-04-05 15:43:15', '120.00', 0, 'rap', 'f', 4, 13, NULL),
(6, 'Album Led Zeppelin', 'Album Led Zeppelin original', '2022-04-12 14:25:43', '800.00', 0, 'pop', 'f', 9, 21, NULL),
(7, 'Album', 'album', '2022-05-10 19:55:48', '100.00', 0, 'clas', 'f', 3, 31, NULL),
(8, 'Comptine dun autre été', 'PDF da composição de Yann Tiersen, Comptine dun autre été', '2022-05-22 16:05:07', '10.00', 0, 'erud', 'v', 3, 33, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_servico`
--

CREATE TABLE `tb_servico` (
  `cd_servico` int(11) NOT NULL,
  `nm_servico` varchar(50) NOT NULL,
  `ds_servico` varchar(200) NOT NULL,
  `dt_servico` datetime NOT NULL,
  `vl_servico` varchar(12) NOT NULL,
  `nm_genero` varchar(50) NOT NULL,
  `nm_inativo` tinyint(1) DEFAULT NULL,
  `cd_usuario` int(11) DEFAULT NULL,
  `cd_imagem` int(11) DEFAULT NULL,
  `cd_arquivo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_servico`
--

INSERT INTO `tb_servico` (`cd_servico`, `nm_servico`, `ds_servico`, `dt_servico`, `vl_servico`, `nm_genero`, `nm_inativo`, `cd_usuario`, `cd_imagem`, `cd_arquivo`) VALUES
(1, 'Toco guitarra', 'Toco guitarra em festas muito bem', '2022-04-02 17:55:53', '200.00', 'rap', 0, 1, 4, NULL),
(2, 'Toco violino para casamento', 'Toco violino em casamentos orquestrados', '2022-04-05 15:48:40', '100.00', 'clas', 0, 5, 14, NULL),
(3, 'Irei produzir música para vocês', 'Produzo música eletrônica há 10 anos.....', '2022-04-12 14:28:24', '120.00', 'elect', 0, 9, 22, NULL),
(4, 'Edito o áudio do seu podcast', 'Irei EDITAR o ÁUDIO do podcast que você me mandar', '2022-04-12 14:41:25', '100.00', 'erud', 0, 10, 23, NULL),
(5, 'Serei a voz que você precisar', 'Irei narrar, dublar ou até mesmo cantar o que você quiser', '2022-04-12 14:42:07', '200.00', 'pop', 0, 10, 24, NULL),
(6, 'Irei editar/mixar/masterizar sua música', 'Sou editor de música profissional', '2022-04-12 14:55:33', '120.00', 'elect', 0, 1, 27, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `cd_usuario` int(11) NOT NULL,
  `nm_usuario` varchar(150) NOT NULL,
  `nm_senha` varchar(32) NOT NULL,
  `nm_email` varchar(100) NOT NULL,
  `dt_nascimento` date NOT NULL,
  `nm_cpf` varchar(11) NOT NULL,
  `sg_genero` char(1) NOT NULL,
  `sg_especialidade` char(1) NOT NULL,
  `nm_inativo` int(11) NOT NULL DEFAULT 0,
  `dt_tempo` datetime NOT NULL,
  `nm_apelido` varchar(40) DEFAULT NULL,
  `ds_usuario` varchar(300) DEFAULT NULL,
  `cd_imagem` int(11) DEFAULT NULL,
  `cd_fundo` int(11) DEFAULT NULL,
  `cd_endereco` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`cd_usuario`, `nm_usuario`, `nm_senha`, `nm_email`, `dt_nascimento`, `nm_cpf`, `sg_genero`, `sg_especialidade`, `nm_inativo`, `dt_tempo`, `nm_apelido`, `ds_usuario`, `cd_imagem`, `cd_fundo`, `cd_endereco`) VALUES
(1, 'Filipe Bovolin Reis', '3.4818561661973E+126', 'filipe.bovolin@gmail.com', '2004-07-11', '36101263886', 'm', 'm', 0, '2022-04-02 17:42:14', NULL, NULL, 6, NULL, 1),
(2, 'Sarah Cristina Salles de Oliveira', '3.4818561661973E+126', 'sarinhabr@gmail.com', '2004-09-28', '52815915847', 'f', 'v', 0, '2022-04-02 17:57:25', NULL, NULL, 5, NULL, 2),
(3, 'Giovanna Cardim', '3.4818561661973E+126', 'giovanna@gmail.com', '2003-02-25', '47685880857', 'f', 'v', 0, '2022-04-05 15:28:49', NULL, NULL, 29, NULL, 3),
(4, 'Luana Ochoa Rossini', '3.4818561661973E+126', 'luanarossi@gmail.com', '2002-09-25', '42686712856', 'f', 'c', 0, '2022-04-05 15:35:26', NULL, NULL, 19, NULL, 4),
(5, 'Kelwyn Leite', '3.4818561661973E+126', 'kelwyn@gmail.com', '2000-10-09', '41153764814', 'm', 'm', 0, '2022-04-05 15:47:40', NULL, NULL, 15, 1, 5),
(6, 'Yasmin Stella Costa', '2.2678145381498E+146', 'yasmin-costa97@cancaonova.com', '1888-03-18', '63569422828', 'f', 'm', 0, '2022-04-05 19:35:43', NULL, NULL, 17, 2, 6),
(7, 'Alexsandro Alves Silva', '3.4818561661973E+126', 'alex@gmail.com', '1999-09-03', '47211298820', 'm', 'm', 0, '2022-04-06 18:24:45', NULL, NULL, 19, NULL, 7),
(8, 'Matilde Cândida de Campos', '3.4818561661973E+126', 'matilde@gmail.com', '1969-01-10', '30080352804', 'f', 'm', 0, '2022-04-06 19:39:26', NULL, NULL, 19, NULL, 9),
(9, ' Elaine Eduarda Priscila Rocha', '1.5828752013994E+139', 'elaine@gmail.com', '1968-02-12', '75099797490', 'f', 'v', 0, '2022-04-12 14:14:35', NULL, NULL, 19, NULL, 10),
(10, 'Cauã Vitor Jesus', '1.5828752013994E+139', 'caue@gmail.com', '1979-04-02', '82824270632', 'm', 'c', 0, '2022-04-12 14:34:55', NULL, NULL, 19, NULL, 11);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tb_carrinho`
--
ALTER TABLE `tb_carrinho`
  ADD PRIMARY KEY (`cd_carrinho`),
  ADD KEY `fk_carrinho_instrumento` (`cd_instrumento`),
  ADD KEY `fk_carrinho_interpretacao` (`cd_interpretacao`),
  ADD KEY `fk_carrinho_servico` (`cd_servico`),
  ADD KEY `fk_carrinho_usuario` (`cd_usuario`);

--
-- Índices para tabela `tb_compra`
--
ALTER TABLE `tb_compra`
  ADD PRIMARY KEY (`cd_compra`),
  ADD KEY `fk_compra_usuario` (`cd_usuario`),
  ADD KEY `fk_compra_carrinho` (`cd_carrinho`);

--
-- Índices para tabela `tb_endereco`
--
ALTER TABLE `tb_endereco`
  ADD PRIMARY KEY (`cd_endereco`);

--
-- Índices para tabela `tb_feedback`
--
ALTER TABLE `tb_feedback`
  ADD PRIMARY KEY (`cd_feedback`),
  ADD KEY `fk_feedback_usuario` (`cd_usuario`),
  ADD KEY `fk_feedback_instrumento` (`cd_instrumento`),
  ADD KEY `fk_feedback_servico` (`cd_servico`),
  ADD KEY `fk_feedback_interpretacao` (`cd_interpretacao`);

--
-- Índices para tabela `tb_fundo`
--
ALTER TABLE `tb_fundo`
  ADD PRIMARY KEY (`cd_fundo`),
  ADD KEY `fk_fundo_imagem` (`cd_imagem`);

--
-- Índices para tabela `tb_imagem`
--
ALTER TABLE `tb_imagem`
  ADD PRIMARY KEY (`cd_imagem`);

--
-- Índices para tabela `tb_instrumento`
--
ALTER TABLE `tb_instrumento`
  ADD PRIMARY KEY (`cd_instrumento`),
  ADD KEY `fk_instrumento_usuario` (`cd_usuario`),
  ADD KEY `fk_instrumento_imagem` (`cd_imagem`);

--
-- Índices para tabela `tb_interpretacao`
--
ALTER TABLE `tb_interpretacao`
  ADD PRIMARY KEY (`cd_interpretacao`),
  ADD KEY `fk_interpretacao_usuario` (`cd_usuario`),
  ADD KEY `fk_interpretacao_imagem` (`cd_imagem`),
  ADD KEY `fk_interpretacao_arquivo` (`cd_arquivo`) USING BTREE;

--
-- Índices para tabela `tb_servico`
--
ALTER TABLE `tb_servico`
  ADD PRIMARY KEY (`cd_servico`),
  ADD KEY `fk_servico_usuario` (`cd_usuario`),
  ADD KEY `fk_servico_imagem` (`cd_imagem`),
  ADD KEY `fk_servico_arquivo` (`cd_arquivo`) USING BTREE;

--
-- Índices para tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`cd_usuario`),
  ADD KEY `fk_usuario_imagem` (`cd_imagem`),
  ADD KEY `fk_usuario_fundo` (`cd_fundo`),
  ADD KEY `fk_usuario_endereco` (`cd_endereco`);

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tb_carrinho`
--
ALTER TABLE `tb_carrinho`
  ADD CONSTRAINT `fk_carrinho_instrumento` FOREIGN KEY (`cd_instrumento`) REFERENCES `tb_instrumento` (`cd_instrumento`),
  ADD CONSTRAINT `fk_carrinho_interpretacao` FOREIGN KEY (`cd_interpretacao`) REFERENCES `tb_interpretacao` (`cd_interpretacao`),
  ADD CONSTRAINT `fk_carrinho_servico` FOREIGN KEY (`cd_servico`) REFERENCES `tb_servico` (`cd_servico`),
  ADD CONSTRAINT `fk_carrinho_usuario` FOREIGN KEY (`cd_usuario`) REFERENCES `tb_usuario` (`cd_usuario`);

--
-- Limitadores para a tabela `tb_compra`
--
ALTER TABLE `tb_compra`
  ADD CONSTRAINT `fk_compra_carrinho` FOREIGN KEY (`cd_carrinho`) REFERENCES `tb_carrinho` (`cd_carrinho`),
  ADD CONSTRAINT `fk_compra_usuario` FOREIGN KEY (`cd_usuario`) REFERENCES `tb_usuario` (`cd_usuario`);

--
-- Limitadores para a tabela `tb_feedback`
--
ALTER TABLE `tb_feedback`
  ADD CONSTRAINT `fk_feedback_instrumento` FOREIGN KEY (`cd_instrumento`) REFERENCES `tb_instrumento` (`cd_instrumento`),
  ADD CONSTRAINT `fk_feedback_interpretacao` FOREIGN KEY (`cd_interpretacao`) REFERENCES `tb_interpretacao` (`cd_interpretacao`),
  ADD CONSTRAINT `fk_feedback_servico` FOREIGN KEY (`cd_servico`) REFERENCES `tb_servico` (`cd_servico`),
  ADD CONSTRAINT `fk_feedback_usuario` FOREIGN KEY (`cd_usuario`) REFERENCES `tb_usuario` (`cd_usuario`);

--
-- Limitadores para a tabela `tb_fundo`
--
ALTER TABLE `tb_fundo`
  ADD CONSTRAINT `fk_fundo_imagem` FOREIGN KEY (`cd_imagem`) REFERENCES `tb_imagem` (`cd_imagem`);

--
-- Limitadores para a tabela `tb_instrumento`
--
ALTER TABLE `tb_instrumento`
  ADD CONSTRAINT `fk_instrumento_imagem` FOREIGN KEY (`cd_imagem`) REFERENCES `tb_imagem` (`cd_imagem`),
  ADD CONSTRAINT `fk_instrumento_usuario` FOREIGN KEY (`cd_usuario`) REFERENCES `tb_usuario` (`cd_usuario`);

--
-- Limitadores para a tabela `tb_interpretacao`
--
ALTER TABLE `tb_interpretacao`
  ADD CONSTRAINT `fk_interpretacao_imagem` FOREIGN KEY (`cd_imagem`) REFERENCES `tb_imagem` (`cd_imagem`),
  ADD CONSTRAINT `fk_interpretacao_usuario` FOREIGN KEY (`cd_usuario`) REFERENCES `tb_usuario` (`cd_usuario`);

--
-- Limitadores para a tabela `tb_servico`
--
ALTER TABLE `tb_servico`
  ADD CONSTRAINT `fk_servico_imagem` FOREIGN KEY (`cd_imagem`) REFERENCES `tb_imagem` (`cd_imagem`),
  ADD CONSTRAINT `fk_servico_usuario` FOREIGN KEY (`cd_usuario`) REFERENCES `tb_usuario` (`cd_usuario`);

--
-- Limitadores para a tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD CONSTRAINT `fk_usuario_endereco` FOREIGN KEY (`cd_endereco`) REFERENCES `tb_endereco` (`cd_endereco`),
  ADD CONSTRAINT `fk_usuario_fundo` FOREIGN KEY (`cd_fundo`) REFERENCES `tb_fundo` (`cd_fundo`),
  ADD CONSTRAINT `fk_usuario_imagem` FOREIGN KEY (`cd_imagem`) REFERENCES `tb_imagem` (`cd_imagem`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

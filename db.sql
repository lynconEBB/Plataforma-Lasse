-- MySQL dump 10.13  Distrib 8.0.18, for Win64 (x86_64)
--
-- Host: localhost    Database: dbLPM
-- ------------------------------------------------------
-- Server version	8.0.18

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tbAtividade`
--

DROP TABLE IF EXISTS `tbAtividade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbAtividade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` int(11) NOT NULL,
  `idTarefa` int(11) DEFAULT NULL,
  `tipo` varchar(45) DEFAULT 'funcionario',
  `tempoGasto` decimal(8,2) NOT NULL,
  `comentario` varchar(100) NOT NULL,
  `dataRealizacao` date NOT NULL,
  `totalGasto` decimal(9,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbAtividade_tbUsuario` (`idUsuario`),
  KEY `fk_tbAtividade_tbTarefa` (`idTarefa`),
  CONSTRAINT `fk_tbAtividade_tbTarefa` FOREIGN KEY (`idTarefa`) REFERENCES `tbTarefa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tbAtividade_tbUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `tbUsuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbAtividade`
--

LOCK TABLES `tbAtividade` WRITE;
/*!40000 ALTER TABLE `tbAtividade` DISABLE KEYS */;
INSERT INTO `tbAtividade` VALUES (7,27,NULL,'Reunião Interna',6.00,'Reunião para decidir novo projeto a ser criado','2019-10-12',50.58),(8,26,NULL,'Workshop Externo',6.60,'Workshop Network','2019-04-12',43.89),(9,27,16,'Design',5.00,'Design do ambiente','2019-04-23',40.00),(10,26,19,'Desenvolvimento',8.00,'CRUD usuários','2019-10-12',53.20),(11,27,15,'Desenvolvimento',6.00,'Desenvolvimento de Primeiro personagem','2019-10-01',48.00),(12,26,19,'Aprimoramento',3.00,'Vídeo aulas de javascript','2019-10-07',19.95),(13,27,17,'Desenvolvimento',10.00,'Definir design de ambiente de personagem','2019-10-02',80.00),(14,26,21,'Design',9.00,'Design das antenas','2019-10-22',59.85),(15,26,NULL,'Projeto Interno',5.00,'Desenvolvimento de jogo com React','2019-07-21',33.25),(16,27,23,'Aprimoramento',5.00,'Material','2010-10-02',40.00),(17,22,15,'Aprimoramento',3.00,'Roupas de personagem ','2019-10-03',16.29),(18,22,17,'Desenvolvimento',6.00,'Estilo de personagem','2019-10-04',32.58),(19,22,23,'Desenvolvimento',3.00,'Equipamento','2019-10-05',16.29);
/*!40000 ALTER TABLE `tbAtividade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbCompra`
--

DROP TABLE IF EXISTS `tbCompra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbCompra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proposito` varchar(60) NOT NULL,
  `totalGasto` decimal(6,2) DEFAULT '0.00',
  `idTarefa` int(11) NOT NULL,
  `idComprador` int(11) NOT NULL,
  `fonte` varchar(50) NOT NULL,
  `naturezaOrcamentaria` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbTarefa_tbCompra` (`idTarefa`),
  KEY `fk_tbCompra_tbUsuario` (`idComprador`),
  CONSTRAINT `fk_tbCompra_tbTarefa` FOREIGN KEY (`idTarefa`) REFERENCES `tbTarefa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tbCompra_tbUsuario` FOREIGN KEY (`idComprador`) REFERENCES `tbUsuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbCompra`
--

LOCK TABLES `tbCompra` WRITE;
/*!40000 ALTER TABLE `tbCompra` DISABLE KEYS */;
INSERT INTO `tbCompra` VALUES (17,'Melhorar design de gráficos ',120.67,19,26,'3374734','73738374'),(18,'Dar andamento a Impressora 3d',70.00,15,27,'123654','654321'),(19,'Melhorar conforto dos desenvolvedores',672.97,19,26,'627737374','726636373');
/*!40000 ALTER TABLE `tbCompra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbCondutor`
--

DROP TABLE IF EXISTS `tbCondutor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbCondutor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `cnh` varchar(45) NOT NULL,
  `validadeCNH` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbCondutor`
--

LOCK TABLES `tbCondutor` WRITE;
/*!40000 ALTER TABLE `tbCondutor` DISABLE KEYS */;
INSERT INTO `tbCondutor` VALUES (7,'Pedro','56987412','2030-02-17');
/*!40000 ALTER TABLE `tbCondutor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbFormulario`
--

DROP TABLE IF EXISTS `tbFormulario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbFormulario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `caminhoDocumento` longtext NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idViagem` int(11) DEFAULT NULL,
  `idCompra` int(11) DEFAULT NULL,
  `dataModificacao` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbFormulario_tbUsuario` (`idUsuario`),
  KEY `fk_tbFormulario_tbViagem` (`idViagem`),
  KEY `tbFormulario_tbCompra__fk` (`idCompra`),
  CONSTRAINT `fk_tbFormulario_tbUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `tbUsuario` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tbFormulario_tbViagem` FOREIGN KEY (`idViagem`) REFERENCES `tbViagem` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tbFormulario_tbCompra__fk` FOREIGN KEY (`idCompra`) REFERENCES `tbCompra` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbFormulario`
--

LOCK TABLES `tbFormulario` WRITE;
/*!40000 ALTER TABLE `tbFormulario` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbFormulario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbGasto`
--

DROP TABLE IF EXISTS `tbGasto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbGasto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `valor` decimal(9,2) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `idViagem` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbGasto_tbViagem` (`idViagem`),
  CONSTRAINT `fk_tbViagem_tbGasto` FOREIGN KEY (`idViagem`) REFERENCES `tbViagem` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbGasto`
--

LOCK TABLES `tbGasto` WRITE;
/*!40000 ALTER TABLE `tbGasto` DISABLE KEYS */;
INSERT INTO `tbGasto` VALUES (92,50.00,'Aluguel de veículos (locado fora de Foz)',15),(93,100.00,'Combustível',15),(94,12.00,'Estacionamento',15),(95,120.00,'Passagens rodoviárias (metrô/ônibus)',15),(96,0.00,'Passagens rodoviárias internacionais',15),(97,12.50,'Pedágio',15),(98,0.00,'Seguro internacional (obrigatório)',15),(99,0.00,'Táxi',15);
/*!40000 ALTER TABLE `tbGasto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbItem`
--

DROP TABLE IF EXISTS `tbItem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbItem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCompra` int(11) NOT NULL,
  `valor` decimal(9,2) NOT NULL,
  `quantidade` decimal(6,2) NOT NULL,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbProduto_tbCompra` (`idCompra`),
  CONSTRAINT `fk_tbCompra_tbItem` FOREIGN KEY (`idCompra`) REFERENCES `tbCompra` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbItem`
--

LOCK TABLES `tbItem` WRITE;
/*!40000 ALTER TABLE `tbItem` DISABLE KEYS */;
INSERT INTO `tbItem` VALUES (17,17,120.67,1.00,'Licença highcharts'),(18,18,35.00,2.00,'Filamento 30 metros'),(19,19,65.00,2.00,'Teclado Gamer'),(20,19,180.99,3.00,'Cadeira Gamer');
/*!40000 ALTER TABLE `tbItem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbProjeto`
--

DROP TABLE IF EXISTS `tbProjeto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbProjeto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataFinalizacao` date NOT NULL,
  `dataInicio` date NOT NULL,
  `totalGasto` decimal(10,2) DEFAULT '0.00',
  `descricao` longtext NOT NULL,
  `nome` varchar(45) NOT NULL,
  `numCentroCusto` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbProjeto`
--

LOCK TABLES `tbProjeto` WRITE;
/*!40000 ALTER TABLE `tbProjeto` DISABLE KEYS */;
INSERT INTO `tbProjeto` VALUES (14,'2022-03-02','2016-03-12',59.85,'Projeto com objetivo de monitorar as atividades da cidade de Entre Rios','P&D Entre Rios','4354644'),(15,'2025-02-04','2018-03-12',581.37,'Projeto para desenvolver um aplicação de monitoramento da bomba d\'agua em um ambiente virtual','Realidade Virtual','4645463'),(16,'2024-03-09','2008-03-12',866.79,'Projeto para criar uma aplicação web de gerenciamento de projetos','Gerenciador de Projetos','5645654'),(17,'2023-11-05','2010-03-12',56.29,'Projeto para monitorar e executar simulações de sistemas elétricos da Itaipu Binacional','Monitoramento Elétrico','34778587');
/*!40000 ALTER TABLE `tbProjeto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbTarefa`
--

DROP TABLE IF EXISTS `tbTarefa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbTarefa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataInicio` date NOT NULL,
  `dataConclusao` date DEFAULT NULL,
  `estado` varchar(45) DEFAULT 'trabalhando',
  `nome` varchar(45) NOT NULL,
  `descricao` longtext NOT NULL,
  `idProjeto` int(11) NOT NULL,
  `totalGasto` decimal(9,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `fk_tbProjeto_tbTarefa` (`idProjeto`),
  CONSTRAINT `fk_tbProjeto_tbTarefa` FOREIGN KEY (`idProjeto`) REFERENCES `tbProjeto` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbTarefa`
--

LOCK TABLES `tbTarefa` WRITE;
/*!40000 ALTER TABLE `tbTarefa` DISABLE KEYS */;
INSERT INTO `tbTarefa` VALUES (15,'2019-03-12','2020-10-29','Em andamento','Desenvolver Modelos de Personagem','Tarefa para desenvolver modelos 3d desde sua concepção até sua modelagem',15,428.79),(16,'2018-03-22','2019-03-15','Concluída','Definir Escopo','Tarefa para definir o escopo do projeto desde suas funcionalidades mais básicas',15,40.00),(17,'2019-04-22','2020-08-16','Em andamento','Definir design','Tarefa para definir o design do ambiente',15,112.58),(18,'2019-03-12','2019-06-23','Concluída','Definir requisitos','Tarefa para realizar entrevistas e definir requisitos ',16,0.00),(19,'2019-09-17','2020-10-05','Em andamento','Desenvolver API','Tarefa para desenvolvimento da estrutura e código  da ALI Rest',16,866.79),(20,'2020-10-09','2021-05-18','Á fazer','Implementar Funcionalidade ODT','Tarefa para desenvolver funcionalidade de receber ODT',16,0.00),(21,'2019-09-10','2020-12-10','Em andamento','Instalação de antenas','Tarefa para desenvolver e instalar antenas nos locais requisitados',14,59.85),(23,'2010-11-02','2010-12-03','Em andamento','Elétrica','Desenvolver sensores elétricos',17,56.29);
/*!40000 ALTER TABLE `tbTarefa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbUsuario`
--

DROP TABLE IF EXISTS `tbUsuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbUsuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomeCompleto` varchar(45) NOT NULL,
  `cpf` varchar(40) NOT NULL,
  `rg` varchar(20) NOT NULL,
  `dataDeEmissao` date NOT NULL,
  `valorHora` decimal(6,2) NOT NULL,
  `formacao` varchar(45) NOT NULL,
  `atuacao` varchar(45) DEFAULT 'Colaborador',
  `email` varchar(45) NOT NULL,
  `login` varchar(45) NOT NULL,
  `senha` longtext NOT NULL,
  `dtNascimento` date NOT NULL,
  `estado` varchar(45) DEFAULT 'ativado',
  `tokenValido` longtext,
  `admin` tinyint(1) DEFAULT NULL,
  `caminhoFoto` longtext NOT NULL,
  `tokenConfirmacao` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbUsuario`
--

LOCK TABLES `tbUsuario` WRITE;
/*!40000 ALTER TABLE `tbUsuario` DISABLE KEYS */;
INSERT INTO `tbUsuario` VALUES (22,'Camila Gomes Ferreira','242.097.180-92','31.442.752-1','2004-09-12',5.43,'Designer','Bolsista/Voluntário','camilagf2016@gmail.com','Camiggf','$2y$10$tpOG9jGCkJEzb6V0gsgT3u84yCYqefCBeCWLQ1dpD7THC.AUzT3am','1999-10-07','ativado',NULL,0,'assets/files/22/perfil1573940374.jpeg',NULL),(26,'Lyncon Estevan Bernardo Baez','121.128.809-93','12.611.282-3','2008-03-12',6.65,'Programador','Bolsista/Voluntário','lynconlyn@gmail.com','LynconEBB','$2y$10$omG8DIu/HZvo1U0gBKhKk.VrCPHwHoYHdxMsedz6QS50N7NPntTAW','2001-03-24','ativado',NULL,1,'assets/files/26/perfil1573941785.jpeg',NULL),(27,'Ismael Pereira dos Santos','212.790.020-00','23.274.586-9','2004-02-05',8.00,'Analista de Sistemas','Terceiros','camilagf1440@hotmail.com','Ismael270','$2y$10$hF3yNHrly73efs4oCoiw8uWfnMM7ctmsl0zTO5g.hx5fz7/fEq6Wq','2002-03-12','ativado',NULL,0,'assets/files/27/perfil1573942696.jpeg',NULL),(28,'Ana da Silva Borges','175.985.730-00','37.989.863-9','2000-09-07',7.54,'Gerente','Colaborador ','camilagferreira.123456@gmail.com','ana123','$2y$10$OZVIqc2gHErqMFAzP6gGieKA72OmkdoVipNUs3YYKVReKO51dKRSq','1980-03-12','ativado',NULL,0,'assets/files/28/perfil1573942992.jpeg',NULL);
/*!40000 ALTER TABLE `tbUsuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbUsuarioProjeto`
--

DROP TABLE IF EXISTS `tbUsuarioProjeto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbUsuarioProjeto` (
  `idProjeto` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `dono` tinyint(1) NOT NULL,
  PRIMARY KEY (`idUsuario`,`idProjeto`),
  KEY `fk_tbUsuarioProjeto_tbProjeto` (`idProjeto`),
  CONSTRAINT `fk_tbUsuarioProjeto_tbUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `tbUsuario` (`id`),
  CONSTRAINT `tbUsuarioProjeto_ibfk_1` FOREIGN KEY (`idProjeto`) REFERENCES `tbProjeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbUsuarioProjeto`
--

LOCK TABLES `tbUsuarioProjeto` WRITE;
/*!40000 ALTER TABLE `tbUsuarioProjeto` DISABLE KEYS */;
INSERT INTO `tbUsuarioProjeto` VALUES (14,22,1),(15,22,0),(16,22,0),(17,22,0),(14,26,0),(16,26,1),(15,27,1),(17,27,0),(15,28,0),(17,28,1);
/*!40000 ALTER TABLE `tbUsuarioProjeto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbVeiculo`
--

DROP TABLE IF EXISTS `tbVeiculo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbVeiculo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCondutor` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `retirada` datetime NOT NULL,
  `devolucao` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbVeiculo_tbCondutor` (`idCondutor`),
  CONSTRAINT `fk_tbVeiculo_tbCondutor` FOREIGN KEY (`idCondutor`) REFERENCES `tbCondutor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbVeiculo`
--

LOCK TABLES `tbVeiculo` WRITE;
/*!40000 ALTER TABLE `tbVeiculo` DISABLE KEYS */;
INSERT INTO `tbVeiculo` VALUES (9,7,'Fusca','Veículo locado','2019-04-23 13:00:00','2019-05-23 23:00:00');
/*!40000 ALTER TABLE `tbVeiculo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbViagem`
--

DROP TABLE IF EXISTS `tbViagem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbViagem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idVeiculo` int(11) NOT NULL,
  `idTarefa` int(11) NOT NULL,
  `origem` varchar(45) NOT NULL,
  `destino` varchar(45) NOT NULL,
  `dataIda` date NOT NULL,
  `dataVolta` date NOT NULL,
  `justificativa` varchar(200) NOT NULL,
  `observacoes` varchar(200) NOT NULL,
  `passagem` varchar(45) NOT NULL,
  `totalGasto` decimal(12,2) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `entradaHosp` datetime NOT NULL,
  `saidaHosp` datetime NOT NULL,
  `fonte` varchar(45) NOT NULL,
  `atividade` varchar(45) NOT NULL,
  `tipo` varchar(200) NOT NULL,
  `tipoPassagem` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbViagem_tbVeiculo` (`idVeiculo`),
  KEY `fk_tbViagem_tbTarefa` (`idTarefa`),
  CONSTRAINT `fk_tbViagem_tbTarefa` FOREIGN KEY (`idTarefa`) REFERENCES `tbTarefa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tbViagem_tbVeiculo` FOREIGN KEY (`idVeiculo`) REFERENCES `tbVeiculo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbViagem`
--

LOCK TABLES `tbViagem` WRITE;
/*!40000 ALTER TABLE `tbViagem` DISABLE KEYS */;
INSERT INTO `tbViagem` VALUES (15,9,15,'Foz do Iguaçu','Cascavel','2019-04-15','2019-05-16','Buscar equipamento','3 Funcionários para a viagem','587496',294.50,27,'2019-04-24 13:30:00','2019-04-25 14:00:00','235698','Copel.00','Viagem a trabalho','Terrestre nacional');
/*!40000 ALTER TABLE `tbViagem` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-11-16 21:24:20

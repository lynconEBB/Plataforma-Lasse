-- MySQL dump 10.13  Distrib 8.0.17, for Win64 (x86_64)
--
-- Host: localhost    Database: dbLPM
-- ------------------------------------------------------
-- Server version	8.0.17

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
  CONSTRAINT `fk_tbAtividade_tbTarefa` FOREIGN KEY (`idTarefa`) REFERENCES `tbTarefa` (`id`),
  CONSTRAINT `fk_tbAtividade_tbUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `tbUsuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbAtividade`
--

LOCK TABLES `tbAtividade` WRITE;
/*!40000 ALTER TABLE `tbAtividade` DISABLE KEYS */;
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
  CONSTRAINT `fk_tbCompra_tbUsuario` FOREIGN KEY (`idComprador`) REFERENCES `tbUsuario` (`id`),
  CONSTRAINT `fk_tbTarefa_tbCompra` FOREIGN KEY (`idTarefa`) REFERENCES `tbTarefa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbCompra`
--

LOCK TABLES `tbCompra` WRITE;
/*!40000 ALTER TABLE `tbCompra` DISABLE KEYS */;
INSERT INTO `tbCompra` VALUES (1,'Melhorar Vida',0.00,13,19,'86945654','86576495'),(2,'Melhorar Vida',209.88,13,19,'86945654','86576495'),(3,'Melhorar Vida',209.88,13,19,'86945654','86576495'),(4,'Melhorar Vida',0.00,13,19,'86945654','86576495');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbCondutor`
--

LOCK TABLES `tbCondutor` WRITE;
/*!40000 ALTER TABLE `tbCondutor` DISABLE KEYS */;
INSERT INTO `tbCondutor` VALUES (4,'Arlindo','43543654','2022-08-21'),(5,'Arlindo','43543654','2022-08-21'),(6,'Arlindo','43543654','2022-08-21');
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
  `caminhoHTML` longtext NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idViagem` int(11) DEFAULT NULL,
  `idCompra` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbFormulario_tbUsuario` (`idUsuario`),
  KEY `fk_tbFormulario_tbViagem` (`idViagem`),
  KEY `tbFormulario_tbCompra__fk` (`idCompra`),
  CONSTRAINT `fk_tbFormulario_tbUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `tbUsuario` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tbFormulario_tbViagem` FOREIGN KEY (`idViagem`) REFERENCES `tbViagem` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tbFormulario_tbCompra__fk` FOREIGN KEY (`idCompra`) REFERENCES `tbCompra` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbFormulario`
--

LOCK TABLES `tbFormulario` WRITE;
/*!40000 ALTER TABLE `tbFormulario` DISABLE KEYS */;
INSERT INTO `tbFormulario` VALUES (27,'requisicaoViagem10','assets/files/19/requisicaoViagem10/requisicaoViagem10.odt','assets/files/19/requisicaoViagem10/requisicaoViagem10.html',19,10,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbGasto`
--

LOCK TABLES `tbGasto` WRITE;
/*!40000 ALTER TABLE `tbGasto` DISABLE KEYS */;
INSERT INTO `tbGasto` VALUES (49,12.00,'Aluguel de veÃ­culos (locado fora de Foz)',10),(50,65.00,'CombustÃ­vel',10),(51,78.00,'Estacionamento',10),(52,10.00,'Passagens rodoviÃ¡rias (metrÃ´/Ã´nibus)',10),(53,24.00,'Passagens rodoviÃ¡rias internacionais',10),(54,53.00,'PedÃ¡gio',10),(55,32.00,'Seguro internacional (obrigatÃ³rio)',10),(56,43.00,'TÃ¡xi',10),(57,23.00,'Outros',10);
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbItem`
--

LOCK TABLES `tbItem` WRITE;
/*!40000 ALTER TABLE `tbItem` DISABLE KEYS */;
INSERT INTO `tbItem` VALUES (1,2,23.32,3.00,'carro'),(2,2,23.32,6.00,'carro'),(3,3,23.32,3.00,'carro'),(4,3,23.32,6.00,'carro');
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbProjeto`
--

LOCK TABLES `tbProjeto` WRITE;
/*!40000 ALTER TABLE `tbProjeto` DISABLE KEYS */;
INSERT INTO `tbProjeto` VALUES (11,'2100-09-25','2001-03-21',759.76,'Projeto para estabelecer um base de reconhecimento na cidade de Entre RIos - Minas Gerias','Entre rios','1243546'),(12,'2006-09-12','1980-07-12',0.00,'Projeto para criar uma impressora 3d auto replicante com o menor custo possivel','Impressora 3D','12376554'),(13,'2019-06-27','2005-10-23',0.00,'Desenvolver frota de carros eletricos para uso na Itaipu','Carro ElÃ©trico','854974');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbTarefa`
--

LOCK TABLES `tbTarefa` WRITE;
/*!40000 ALTER TABLE `tbTarefa` DISABLE KEYS */;
INSERT INTO `tbTarefa` VALUES (13,'2003-03-21','2005-06-21','Em andamento','Instalacao de Antenas','Fazer instalaÃ§ao de antenas nas casas dos moradores da cidade',11,759.76),(14,'2006-03-20','2006-03-21','Á fazer','gfdjlkfdjg','klgjfdlkgjdflkgj',13,0.00);
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbUsuario`
--

LOCK TABLES `tbUsuario` WRITE;
/*!40000 ALTER TABLE `tbUsuario` DISABLE KEYS */;
INSERT INTO `tbUsuario` VALUES (17,'Lyncon Estevan Baez','121.128.809-93','12.611.223-3','2008-05-21',10.99,'Programador','Colaborador','lynconlyn@gmail.com','LynconBaez','$2y$10$F08VHpeT0J3oDOqDxY7F/.zBjOmd1TPA51srp2nwiIUJ6bqqTcVKi','2001-03-24','ativado',NULL,1,'assets/files/17/perfil.jpeg'),(19,'Daniel da Silva Pereira','180.047.480-66','12.611.223-3','2008-05-21',12.94,'Analista de Sistemas','Bolsista/VoluntÃ¡rio','dan.iel@gmail.com','Daniel','$2y$10$/7IejjwyisLsucypxTE5pevm8AXOKo4XwHQa3np6f/P6GgT.QP9Lq','1999-06-21','ativado','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJMYXNzZS1Qcm9qZWN0LU1hbmFnZXIiLCJhdWQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV2luNjQ7IHg2NDsgcnY6NjkuMCkgR2Vja29cLzIwMTAwMTAxIEZpcmVmb3hcLzY5LjAiLCJpYXQiOjE1Njk4ODQ0NzAsIm5iZiI6MTU2OTg4NDQ3MCwiZXhwIjoxNTY5OTcwODcwLCJkYXRhIjp7ImlkIjoiMTkifX0.P2zUwaqoV8MBcDLmD5L9QWycSO0MH1NQuyfRNbmttxA',0,'assets/files/19/perfil.png'),(20,'Camila Gomes Ferreira','946.202.990-30','40.448.574-1','2005-08-21',9.54,'Designer','Terceiros','camilagf2016@gmail.com','Camiggf','$2y$10$i/ywwFtj0HXLRsfC/IVGnOBgYtiK4VXWeWepTaBx0pKJ44rMrkPdy','1999-10-07','ativado','20-5db5e6a162e86',0,'assets/files/default/perfil.png');
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
INSERT INTO `tbUsuarioProjeto` VALUES (13,17,1),(11,19,1),(12,19,0),(12,20,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbVeiculo`
--

LOCK TABLES `tbVeiculo` WRITE;
/*!40000 ALTER TABLE `tbVeiculo` DISABLE KEYS */;
INSERT INTO `tbVeiculo` VALUES (6,6,'santana','VeÃ­culo locado','2009-08-12 12:09:00','2010-07-12 12:09:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbViagem`
--

LOCK TABLES `tbViagem` WRITE;
/*!40000 ALTER TABLE `tbViagem` DISABLE KEYS */;
INSERT INTO `tbViagem` VALUES (10,6,13,'Foz do IguaÃ§u','Entre Rios','2007-03-21','2009-04-21','Viagem com o intuito de estabelecer antenas nas casas das pessoas','viagem necessitarÃ¡ de muitos dinheiros','32546667',340.00,19,'2004-08-12 12:05:00','2005-07-15 13:09:00','1244546','CMS-09','Viagem a trabalho','Terrestre nacional');
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

-- Dump completed on 2019-10-28 18:19:33

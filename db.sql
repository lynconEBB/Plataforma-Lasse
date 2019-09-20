-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: localhost    Database: dbLPM
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
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
/*!40101 SET character_set_client = utf8 */;
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
INSERT INTO `tbAtividade` VALUES (1,11,NULL,'Aprrmoramento',6.00,'fjkdshfkljdklghf','2003-03-23',601.92);
/*!40000 ALTER TABLE `tbAtividade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbCompra`
--

DROP TABLE IF EXISTS `tbCompra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbCompra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proposito` varchar(60) NOT NULL,
  `totalGasto` decimal(6,2) DEFAULT '0.00',
  `idTarefa` int(11) NOT NULL,
  `idComprador` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbTarefa_tbCompra` (`idTarefa`),
  KEY `fk_tbCompra_tbUsuario` (`idComprador`),
  CONSTRAINT `fk_tbCompra_tbUsuario` FOREIGN KEY (`idComprador`) REFERENCES `tbUsuario` (`id`),
  CONSTRAINT `fk_tbTarefa_tbCompra` FOREIGN KEY (`idTarefa`) REFERENCES `tbTarefa` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbCompra`
--

LOCK TABLES `tbCompra` WRITE;
/*!40000 ALTER TABLE `tbCompra` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbCompra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbCondutor`
--

DROP TABLE IF EXISTS `tbCondutor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbCondutor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `cnh` varchar(45) NOT NULL,
  `validadeCNH` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbCondutor`
--

LOCK TABLES `tbCondutor` WRITE;
/*!40000 ALTER TABLE `tbCondutor` DISABLE KEYS */;
INSERT INTO `tbCondutor` VALUES (1,'Arlindo','45435345','2004-04-23'),(2,'Motorestenho','4593948574','2004-03-23'),(3,'Motorestenho','4593948574','2004-03-23');
/*!40000 ALTER TABLE `tbCondutor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbFormulario`
--

DROP TABLE IF EXISTS `tbFormulario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbGasto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `valor` decimal(9,2) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `idViagem` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbGasto_tbViagem` (`idViagem`),
  CONSTRAINT `fk_tbViagem_tbGasto` FOREIGN KEY (`idViagem`) REFERENCES `tbViagem` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbGasto`
--

LOCK TABLES `tbGasto` WRITE;
/*!40000 ALTER TABLE `tbGasto` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbGasto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbItem`
--

DROP TABLE IF EXISTS `tbItem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbItem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCompra` int(11) NOT NULL,
  `valor` decimal(9,2) NOT NULL,
  `quantidade` decimal(6,2) NOT NULL,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbProduto_tbCompra` (`idCompra`),
  CONSTRAINT `fk_tbCompra_tbItem` FOREIGN KEY (`idCompra`) REFERENCES `tbCompra` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbItem`
--

LOCK TABLES `tbItem` WRITE;
/*!40000 ALTER TABLE `tbItem` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbItem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbProjeto`
--

DROP TABLE IF EXISTS `tbProjeto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbProjeto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataFinalizacao` date NOT NULL,
  `dataInicio` date NOT NULL,
  `totalGasto` decimal(10,2) DEFAULT '0.00',
  `descricao` longtext NOT NULL,
  `nome` varchar(45) NOT NULL,
  `numCentroCusto` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbProjeto`
--

LOCK TABLES `tbProjeto` WRITE;
/*!40000 ALTER TABLE `tbProjeto` DISABLE KEYS */;
INSERT INTO `tbProjeto` VALUES (3,'2300-05-21','2201-03-24',0.00,'projeto projetoso para projetar','Projeto 1','895746965'),(4,'2300-05-21','2201-03-24',0.00,'projeto projetoso para projetar','Projeto 2','895746965'),(6,'2003-03-21','2001-03-12',0.00,'fdgdfgfd','Lyncon','46445645'),(7,'2005-05-25','2003-03-23',0.00,'Jrhsiahe','Rita de Cassia Baez','626e74'),(8,'2008-03-21','2005-03-22',0.00,'porjeto muito massa que eu tenho que escrever pra aumentar','Projeto projetoso','4873543');
/*!40000 ALTER TABLE `tbProjeto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbTarefa`
--

DROP TABLE IF EXISTS `tbTarefa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbTarefa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataInicio` date NOT NULL,
  `dataConclusao` date DEFAULT NULL,
  `estado` varchar(45) DEFAULT 'trabalhando',
  `nome` varchar(45) NOT NULL,
  `descricao` varchar(45) NOT NULL,
  `idProjeto` int(11) NOT NULL,
  `totalGasto` decimal(9,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `fk_tbProjeto_tbTarefa` (`idProjeto`),
  CONSTRAINT `fk_tbProjeto_tbTarefa` FOREIGN KEY (`idProjeto`) REFERENCES `tbProjeto` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbTarefa`
--

LOCK TABLES `tbTarefa` WRITE;
/*!40000 ALTER TABLE `tbTarefa` DISABLE KEYS */;
INSERT INTO `tbTarefa` VALUES (10,'2006-03-02','2006-09-03','Em andamento','gfdgdfg','gfdgdf',8,0.00);
/*!40000 ALTER TABLE `tbTarefa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbUsuario`
--

DROP TABLE IF EXISTS `tbUsuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbUsuario`
--

LOCK TABLES `tbUsuario` WRITE;
/*!40000 ALTER TABLE `tbUsuario` DISABLE KEYS */;
INSERT INTO `tbUsuario` VALUES (11,'Lyncon Baez','121.128.809-93','12.611.282-3','2006-03-24',100.32,'Programador','Colaborador','lyncon.ebb@pti.org.br','lynconebb','$2y$10$hoSWvnClQ9MtJ66M8cJsXe/ZIZ/HK9ztrNMOPGyWQM4vhIluqdzEu','2001-05-24','ativado','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJMYXNzZS1Qcm9qZWN0LU1hbmFnZXIiLCJhdWQiOiJpbnNvbW5pYVwvNi42LjIiLCJpYXQiOjE1Njg5NDA4NzIsIm5iZiI6MTU2ODk0MDg3MiwiZXhwIjoxNTY5MDI3MjcyLCJkYXRhIjp7ImlkIjoiMTEifX0.TiJ1EM71Tu02o2CPZALhmYGJ6dVnXbWVezkh6CKW-Ls',0,'assets/files/11/perfil.png'),(12,'Lyncon Baez','121.128.809-93','12.611.282-3','2006-03-24',100.32,'Programador','Colaborador','lyncon.ebb@pti.org.br','lyncon','$2y$10$35LVpTsCLrNUpECcb3Z1wu9KiHdGp0yozil69rzVGSUhI2MQM/m8W','2001-05-24','ativado','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJMYXNzZS1Qcm9qZWN0LU1hbmFnZXIiLCJhdWQiOiJNb3ppbGxhXC81LjAgKFgxMTsgTGludXggeDg2XzY0KSBBcHBsZVdlYktpdFwvNTM3LjM2IChLSFRNTCwgbGlrZSBHZWNrbykgQ2hyb21lXC83Ni4wLjM4MDkuMTAwIFNhZmFyaVwvNTM3LjM2IiwiaWF0IjoxNTY4OTQzODYyLCJuYmYiOjE1Njg5NDM4NjIsImV4cCI6MTU2OTAzMDI2MiwiZGF0YSI6eyJpZCI6IjEyIn19.T09yMd0UaHNjLoeyIZ6desrLovN6yacUcWBCsoY7jhw',1,'assets/files/12/perfil.png'),(13,'Lyncon Baez','121.128.809-93','12.611.282-3','2006-03-24',100.32,'Programador','Colaborador','lyncon.ebb@pti.org.br','fjkdgfd','$2y$10$zh4tkpLZd2ixibADnBWgp.3uunt2KHgs8ZAIROwqawOLK0/GsaXlm','2001-05-24','ativado',NULL,0,'assets/files/13/perfil.png'),(14,'Rita de Cassia Baez','121.128.809-93','12.611.282-3','2005-04-26',23.89,'Professora','Bolsista/VoluntÃ¡rio','titinhaheart@hotmail.com','ritabaez','$2y$10$z2MKnqLfqrs/fNkyne0chuHFX.W7b9nASMpqKJ04eNFYRTbl2qaSO','1977-09-19','ativado','NULL',0,'assets/files/default/perfil.png'),(15,'Camila Gomes Ferreira','112.900.339-60','123456789','2018-02-01',50.00,'TI','Bolsista/VoluntÃ¡rio','camilagf2016@gmail.com','Camila','$2y$10$eCzS5ea7QRKfsARmgQofsehZ2c0hNdvBfd6RMZJgQ1yXf/D744oxC','1999-10-07','ativado','eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJMYXNzZS1Qcm9qZWN0LU1hbmFnZXIiLCJhdWQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV2luNjQ7IHg2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNzYuMC4zODA5LjEzMiBTYWZhcmlcLzUzNy4zNiIsImlhdCI6MTU2ODc2MzU0MywibmJmIjoxNTY4NzYzNTQzLCJleHAiOjE1Njg4NDk5NDMsImRhdGEiOnsiaWQiOiIxNSJ9fQ.zvyaCRcTm8R-sZ7c5q4XMOdjuM9C4HhegUEYHQ0L3DA',0,'assets/files/15/perfil.jpeg');
/*!40000 ALTER TABLE `tbUsuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbUsuarioProjeto`
--

DROP TABLE IF EXISTS `tbUsuarioProjeto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
INSERT INTO `tbUsuarioProjeto` VALUES (3,11,1),(4,11,1),(8,12,1),(6,14,1),(7,14,1),(8,15,0);
/*!40000 ALTER TABLE `tbUsuarioProjeto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbVeiculo`
--

DROP TABLE IF EXISTS `tbVeiculo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbVeiculo`
--

LOCK TABLES `tbVeiculo` WRITE;
/*!40000 ALTER TABLE `tbVeiculo` DISABLE KEYS */;
INSERT INTO `tbVeiculo` VALUES (1,2,'kombi','Transporte com parceiros/terceiros','2003-01-21 03:00:00','2003-01-25 06:00:00'),(2,1,'Ferrari','alugado','2003-01-21 03:00:00','2005-01-25 06:00:00'),(3,3,'kombi','Transporte com parceiros/terceiros','2003-01-21 03:00:00','2003-01-25 06:00:00');
/*!40000 ALTER TABLE `tbVeiculo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbViagem`
--

DROP TABLE IF EXISTS `tbViagem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbViagem`
--

LOCK TABLES `tbViagem` WRITE;
/*!40000 ALTER TABLE `tbViagem` DISABLE KEYS */;
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

-- Dump completed on 2019-09-19 22:46:10

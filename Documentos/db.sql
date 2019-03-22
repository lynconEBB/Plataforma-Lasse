-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: localhost    Database: dbLPM
-- ------------------------------------------------------
-- Server version	5.7.25-0ubuntu0.16.04.2

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbCompra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proposito` varchar(60) NOT NULL,
  `totalGasto` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`)
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbCondutor`
--

LOCK TABLES `tbCondutor` WRITE;
/*!40000 ALTER TABLE `tbCondutor` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbCondutor` ENABLE KEYS */;
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
  CONSTRAINT `fk_tbGasto_tbViagem` FOREIGN KEY (`idViagem`) REFERENCES `tbViagem` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbGasto`
--

LOCK TABLES `tbGasto` WRITE;
/*!40000 ALTER TABLE `tbGasto` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbGasto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbProduto`
--

DROP TABLE IF EXISTS `tbProduto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbProduto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCompra` int(11) NOT NULL,
  `valor` decimal(9,2) NOT NULL,
  `quantidade` decimal(6,2) NOT NULL,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbProduto_tbCompra` (`idCompra`),
  CONSTRAINT `fk_tbProduto_tbCompra` FOREIGN KEY (`idCompra`) REFERENCES `tbCompra` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbProduto`
--

LOCK TABLES `tbProduto` WRITE;
/*!40000 ALTER TABLE `tbProduto` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbProduto` ENABLE KEYS */;
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
  `descricao` varchar(45) NOT NULL,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbProjeto`
--

LOCK TABLES `tbProjeto` WRITE;
/*!40000 ALTER TABLE `tbProjeto` DISABLE KEYS */;
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
  PRIMARY KEY (`id`),
  KEY `fk_tbProjeto_tbTarefa` (`idProjeto`),
  CONSTRAINT `fk_tbProjeto_tbTarefa` FOREIGN KEY (`idProjeto`) REFERENCES `tbProjeto` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbTarefa`
--

LOCK TABLES `tbTarefa` WRITE;
/*!40000 ALTER TABLE `tbTarefa` DISABLE KEYS */;
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
  `tipo` varchar(45) NOT NULL,
  `valorHora` decimal(6,2) NOT NULL,
  `formacao` varchar(45) NOT NULL,
  `atuacao` varchar(45) DEFAULT 'Colaborador',
  `email` varchar(45) NOT NULL,
  `login` varchar(45) NOT NULL,
  `senha` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbUsuario`
--

LOCK TABLES `tbUsuario` WRITE;
/*!40000 ALTER TABLE `tbUsuario` DISABLE KEYS */;
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
  PRIMARY KEY (`idUsuario`,`idProjeto`),
  KEY `fk_tbUsuarioProjeto_tbProjeto` (`idProjeto`),
  CONSTRAINT `fk_tbUsuarioProjeto_tbProjeto` FOREIGN KEY (`idProjeto`) REFERENCES `tbProjeto` (`id`),
  CONSTRAINT `fk_tbUsuarioProjeto_tbUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `tbUsuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbUsuarioProjeto`
--

LOCK TABLES `tbUsuarioProjeto` WRITE;
/*!40000 ALTER TABLE `tbUsuarioProjeto` DISABLE KEYS */;
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
  `dataRetirada` date NOT NULL,
  `dataDevolucao` date NOT NULL,
  `horarioRetirada` time NOT NULL,
  `horarioDevolucao` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbVeiculo_tbCondutor` (`idCondutor`),
  CONSTRAINT `fk_tbVeiculo_tbCondutor` FOREIGN KEY (`idCondutor`) REFERENCES `tbCondutor` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbVeiculo`
--

LOCK TABLES `tbVeiculo` WRITE;
/*!40000 ALTER TABLE `tbVeiculo` DISABLE KEYS */;
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
  `dataEntradaHosp` date NOT NULL,
  `dataSaidaHosp` date NOT NULL,
  `HorarioEntradaHosp` time NOT NULL,
  `HorarioSaidaHosp` time NOT NULL,
  `totalGasto` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbViagem_tbVeiculo` (`idVeiculo`),
  KEY `fk_tbViagem_tbTarefa` (`idTarefa`),
  CONSTRAINT `fk_tbViagem_tbTarefa` FOREIGN KEY (`idTarefa`) REFERENCES `tbTarefa` (`id`),
  CONSTRAINT `fk_tbViagem_tbVeiculo` FOREIGN KEY (`idVeiculo`) REFERENCES `tbVeiculo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
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

-- Dump completed on 2019-03-14 15:26:09

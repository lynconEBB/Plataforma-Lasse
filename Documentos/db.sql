-- MySQL dump 10.13  Distrib 5.7.26, for Linux (x86_64)
--
-- Host: localhost    Database: dbLPM
-- ------------------------------------------------------
-- Server version	5.7.26-0ubuntu0.16.04.1

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbAtividade`
--

LOCK TABLES `tbAtividade` WRITE;
/*!40000 ALTER TABLE `tbAtividade` DISABLE KEYS */;
INSERT INTO `tbAtividade` VALUES (2,2,10,'Desenvolvimento',0.30,'Programacao de Verificacao de permissao','2019-06-12',67.01),(3,2,10,'Desenvolvimento',0.30,'Programacao de Verificacao de permissao','2019-06-12',67.01);
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
  PRIMARY KEY (`id`),
  KEY `fk_tbTarefa_tbCompra` (`idTarefa`),
  CONSTRAINT `fk_tbTarefa_tbCompra` FOREIGN KEY (`idTarefa`) REFERENCES `tbTarefa` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbCompra`
--

LOCK TABLES `tbCompra` WRITE;
/*!40000 ALTER TABLE `tbCompra` DISABLE KEYS */;
INSERT INTO `tbCompra` VALUES (18,'Melhorar Sala de Aula',48.00,10),(20,'Melhorar Sala de Aula',252.16,10),(21,'Melhorar Sala de Aula',216.16,13);
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbCondutor`
--

LOCK TABLES `tbCondutor` WRITE;
/*!40000 ALTER TABLE `tbCondutor` DISABLE KEYS */;
INSERT INTO `tbCondutor` VALUES (9,'Condutores','4535634','2019-02-01'),(10,'Andressa','11111','2039-02-01');
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
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbGasto`
--

LOCK TABLES `tbGasto` WRITE;
/*!40000 ALTER TABLE `tbGasto` DISABLE KEYS */;
INSERT INTO `tbGasto` VALUES (76,30.00,'Aluguel de Veiculos',9),(85,100.00,'Gasto com AlmoÃ§o',9);
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
  CONSTRAINT `fk_tbProduto_tbCompra` FOREIGN KEY (`idCompra`) REFERENCES `tbCompra` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbItem`
--

LOCK TABLES `tbItem` WRITE;
/*!40000 ALTER TABLE `tbItem` DISABLE KEYS */;
INSERT INTO `tbItem` VALUES (49,18,4.00,6.00,'Passat'),(50,18,4.00,6.00,'Passat'),(81,20,54.04,4.00,'Mesa'),(82,20,12.00,3.00,'Cadeira'),(83,21,54.04,4.00,'Mesa');
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
  `descricao` varchar(45) NOT NULL,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbProjeto`
--

LOCK TABLES `tbProjeto` WRITE;
/*!40000 ALTER TABLE `tbProjeto` DISABLE KEYS */;
INSERT INTO `tbProjeto` VALUES (1,'2038-01-02','2039-02-01',0.00,'Projeto para Projetar','Projeto1'),(3,'2038-01-02','2039-02-01',0.00,'fsdjkhfksd','ProjetoCami'),(5,'2014-03-01','2013-02-02',0.00,'Projeto para Projetar','Bio Gas'),(6,'2038-01-02','2039-02-01',0.00,'Projeto para Projetar','Bio Gas');
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
  CONSTRAINT `fk_tbProjeto_tbTarefa` FOREIGN KEY (`idProjeto`) REFERENCES `tbProjeto` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbTarefa`
--

LOCK TABLES `tbTarefa` WRITE;
/*!40000 ALTER TABLE `tbTarefa` DISABLE KEYS */;
INSERT INTO `tbTarefa` VALUES (10,'2003-03-02','2003-02-01','Concluido','Concluir CRUDS','Tarefa para concluir todos os',1,355.96),(13,'2003-03-02','2003-02-01','Trabalhando','Teste 1','Testando Calculo de gAstos totais',1,346.16);
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
  `dtNascimento` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbUsuario`
--

LOCK TABLES `tbUsuario` WRITE;
/*!40000 ALTER TABLE `tbUsuario` DISABLE KEYS */;
INSERT INTO `tbUsuario` VALUES (1,'Lyncon Estevan Baez','12112880993','3424543','3094-03-02','1',93.00,'Estudante','Bolsista/VoluntÃ¡rio','lynconlyn@gmail.com','Lyncon.ebb','hgj','3094-03-02'),(2,'Camila','1232423423','234543534','2012-02-01','1',93.00,'Estudante','Bolsista/VoluntÃ¡rio','camig@gmail.com','cami','123','2019-02-01'),(3,'Lynconeee','12112880993','3424543','3094-03-02','1',93.00,'Es','Terceiros','lynconlyn@gmail.com','aa','123','3094-03-02'),(4,'Passat','1232423423','3424543','2012-02-01','1',50.03,'Estudante','Terceiros','lynconlyn@gmail.com','Rodrigo','123','2019-02-01');
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
  CONSTRAINT `fk_tbUsuarioProjeto_tbProjeto` FOREIGN KEY (`idProjeto`) REFERENCES `tbProjeto` (`id`),
  CONSTRAINT `fk_tbUsuarioProjeto_tbUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `tbUsuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbUsuarioProjeto`
--

LOCK TABLES `tbUsuarioProjeto` WRITE;
/*!40000 ALTER TABLE `tbUsuarioProjeto` DISABLE KEYS */;
INSERT INTO `tbUsuarioProjeto` VALUES (1,1,0),(6,1,1),(3,2,0),(5,3,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbVeiculo`
--

LOCK TABLES `tbVeiculo` WRITE;
/*!40000 ALTER TABLE `tbVeiculo` DISABLE KEYS */;
INSERT INTO `tbVeiculo` VALUES (10,10,'Passat','Celta','2012-02-01','2039-01-01','12:00:00','07:00:00');
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
  `idUsuario` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbViagem_tbVeiculo` (`idVeiculo`),
  KEY `fk_tbViagem_tbTarefa` (`idTarefa`),
  CONSTRAINT `fk_tbViagem_tbTarefa` FOREIGN KEY (`idTarefa`) REFERENCES `tbTarefa` (`id`),
  CONSTRAINT `fk_tbViagem_tbVeiculo` FOREIGN KEY (`idVeiculo`) REFERENCES `tbVeiculo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbViagem`
--

LOCK TABLES `tbViagem` WRITE;
/*!40000 ALTER TABLE `tbViagem` DISABLE KEYS */;
INSERT INTO `tbViagem` VALUES (9,10,13,'Londres','Curitiva','2039-02-01','2031-02-01','dhfksdhfk','dfhjkshfksdhkf','32424','2012-03-03','2018-02-01','09:00:00','08:00:00',130.00,1);
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

-- Dump completed on 2019-06-17 17:00:03

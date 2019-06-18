-- MySQL dump 10.13  Distrib 8.0.16, for Win64 (x86_64)
--
-- Host: localhost    Database: dblpm
-- ------------------------------------------------------
-- Server version	8.0.16

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8mb4 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tbatividade`
--

DROP TABLE IF EXISTS `tbatividade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbatividade` (
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
  CONSTRAINT `fk_tbAtividade_tbTarefa` FOREIGN KEY (`idTarefa`) REFERENCES `tbtarefa` (`id`),
  CONSTRAINT `fk_tbAtividade_tbUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `tbusuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbatividade`
--

LOCK TABLES `tbatividade` WRITE;
/*!40000 ALTER TABLE `tbatividade` DISABLE KEYS */;
INSERT INTO `tbatividade` VALUES (2,2,10,'Desenvolvimento',0.30,'Programacao de Verificacao de permissao','2019-06-12',67.01),(3,2,10,'Desenvolvimento',0.30,'Programacao de Verificacao de permissao','2019-06-12',67.01),(11,1,NULL,'Desenvolvimento',1.00,'fgdsgsdfg','2012-02-02',93.00),(12,1,21,'Desenvolvimento',1.00,'fgdsgsdfg','2012-02-02',93.00);
/*!40000 ALTER TABLE `tbatividade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbcompra`
--

DROP TABLE IF EXISTS `tbcompra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbcompra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proposito` varchar(60) NOT NULL,
  `totalGasto` decimal(6,2) DEFAULT '0.00',
  `idTarefa` int(11) NOT NULL,
  `idComprador` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbTarefa_tbCompra` (`idTarefa`),
  KEY `fk_tbUsuario_tbCompra` (`idComprador`),
  CONSTRAINT `fk_tbTarefa_tbCompra` FOREIGN KEY (`idTarefa`) REFERENCES `tbtarefa` (`id`),
  CONSTRAINT `fk_tbUsuario_tbCompra` FOREIGN KEY (`idComprador`) REFERENCES `tbusuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbcompra`
--

LOCK TABLES `tbcompra` WRITE;
/*!40000 ALTER TABLE `tbcompra` DISABLE KEYS */;
INSERT INTO `tbcompra` VALUES (29,'Melhorar Quarto',36.00,19,1);
/*!40000 ALTER TABLE `tbcompra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbcondutor`
--

DROP TABLE IF EXISTS `tbcondutor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbcondutor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `cnh` varchar(45) NOT NULL,
  `validadeCNH` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbcondutor`
--

LOCK TABLES `tbcondutor` WRITE;
/*!40000 ALTER TABLE `tbcondutor` DISABLE KEYS */;
INSERT INTO `tbcondutor` VALUES (9,'Condutores','4535634','2019-02-01'),(10,'Andressa','11111','2039-02-01'),(12,'Rodrigo','453654','2019-03-02');
/*!40000 ALTER TABLE `tbcondutor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbgasto`
--

DROP TABLE IF EXISTS `tbgasto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbgasto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `valor` decimal(9,2) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `idViagem` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbGasto_tbViagem` (`idViagem`),
  CONSTRAINT `fk_tbGasto_tbViagem` FOREIGN KEY (`idViagem`) REFERENCES `tbviagem` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbgasto`
--

LOCK TABLES `tbgasto` WRITE;
/*!40000 ALTER TABLE `tbgasto` DISABLE KEYS */;
INSERT INTO `tbgasto` VALUES (95,45.00,'Aluguel de Veiculos',11),(96,321.00,'Combustível',11),(97,89.00,'Estacionamento',11),(98,63.00,'Passagens rodoviárias (metrô/ônibus)',11),(99,10.00,'Passagens rodoviárias internacionais',11),(100,39.00,'Pedágio',11),(103,92.00,'Outras despesas de locação de veículos',11),(104,2000.00,'Comida',11),(105,45.00,'Aluguel de Veiculos',12),(106,321.00,'Combustível',12),(107,89.00,'Estacionamento',12),(108,63.00,'Passagens rodoviárias (metrô/ônibus)',12),(109,87.00,'Passagens rodoviárias internacionais',12),(110,78.00,'Pedágio',12),(111,10.00,'Seguro internacional (obrigatório)',12),(112,54.00,'Taxí',12),(113,87.00,'Outras despesas de locação de veículos',12);
/*!40000 ALTER TABLE `tbgasto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbitem`
--

DROP TABLE IF EXISTS `tbitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCompra` int(11) NOT NULL,
  `valor` decimal(9,2) NOT NULL,
  `quantidade` decimal(6,2) NOT NULL,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tbProduto_tbCompra` (`idCompra`),
  CONSTRAINT `fk_tbProduto_tbCompra` FOREIGN KEY (`idCompra`) REFERENCES `tbcompra` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbitem`
--

LOCK TABLES `tbitem` WRITE;
/*!40000 ALTER TABLE `tbitem` DISABLE KEYS */;
INSERT INTO `tbitem` VALUES (97,29,12.00,3.00,'Microfone');
/*!40000 ALTER TABLE `tbitem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbprojeto`
--

DROP TABLE IF EXISTS `tbprojeto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbprojeto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataFinalizacao` date NOT NULL,
  `dataInicio` date NOT NULL,
  `totalGasto` decimal(10,2) DEFAULT '0.00',
  `descricao` varchar(45) NOT NULL,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbprojeto`
--

LOCK TABLES `tbprojeto` WRITE;
/*!40000 ALTER TABLE `tbprojeto` DISABLE KEYS */;
INSERT INTO `tbprojeto` VALUES (1,'2038-01-02','2039-02-01',0.00,'Projeto para Projetar','Projeto1'),(3,'2038-01-02','2039-02-01',0.00,'fsdjkhfksd','ProjetoCami'),(5,'2014-03-01','2013-02-02',0.00,'Projeto para Projetar','Bio Gas'),(8,'2020-01-01','2019-02-01',0.00,'Desenvolver um RPG BioPunk','Desenvolver Jogo'),(9,'2020-01-01','2019-02-01',870.00,'Desenvolver uma aplicação ','PFC - IFPR'),(10,'2020-01-01','2019-02-01',2800.06,'Desenvolver uma aplicação w','Projeto Verao');
/*!40000 ALTER TABLE `tbprojeto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbtarefa`
--

DROP TABLE IF EXISTS `tbtarefa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbtarefa` (
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
  CONSTRAINT `fk_tbProjeto_tbTarefa` FOREIGN KEY (`idProjeto`) REFERENCES `tbprojeto` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbtarefa`
--

LOCK TABLES `tbtarefa` WRITE;
/*!40000 ALTER TABLE `tbtarefa` DISABLE KEYS */;
INSERT INTO `tbtarefa` VALUES (10,'2003-03-02','2003-02-01','Concluido','Concluir CRUDS','Tarefa para concluir todos os',1,355.96),(18,'2019-02-01','2031-02-01','Concluido','Programar CRUDS','Desenvolver uma aplicação ',8,0.00),(19,'2019-02-01','2031-02-01','Concluido','Terminar Trabalho','Desenvolver um RPG BioPunk',9,36.00),(20,'2019-02-01','2031-02-01','Trabalhando','Oi','Deixar todos os cruds funcionando',9,834.00),(21,'2019-02-01','2031-02-01','Concluido','gcgb','fjghkldfg',10,2752.00),(22,'2019-02-01','2031-02-01','Concluido','Oi','Desenvolver uma aplicação ',10,48.06);
/*!40000 ALTER TABLE `tbtarefa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbusuario`
--

DROP TABLE IF EXISTS `tbusuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbusuario` (
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbusuario`
--

LOCK TABLES `tbusuario` WRITE;
/*!40000 ALTER TABLE `tbusuario` DISABLE KEYS */;
INSERT INTO `tbusuario` VALUES (1,'Lyncon Estevan Bernardo Baez','12112880993','3424543','3094-03-02','1',93.00,'Estudante','Colaborador','lynconlyn@gmail.com','Lyncon.ebb','hgj','3094-03-02'),(2,'Camila','1232423423','234543534','2012-02-01','1',93.00,'Estudante','Bolsista/VoluntÃ¡rio','camig@gmail.com','cami','123','2019-02-01'),(3,'Lynconeee','12112880993','3424543','3094-03-02','1',93.00,'Es','Terceiros','lynconlyn@gmail.com','aa','123','3094-03-02'),(4,'Passat','1232423423','3424543','2012-02-01','1',50.03,'Estudante','Terceiros','lynconlyn@gmail.com','Rodrigo','123','2019-02-01'),(5,'Fone','111.290.033-96','3424543','3094-03-02','1',12.02,'Estudante','Terceiros','lynconlyn@gmail.com','ff','123','3094-03-02');
/*!40000 ALTER TABLE `tbusuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbusuarioprojeto`
--

DROP TABLE IF EXISTS `tbusuarioprojeto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbusuarioprojeto` (
  `idProjeto` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `dono` tinyint(1) NOT NULL,
  PRIMARY KEY (`idUsuario`,`idProjeto`),
  KEY `fk_tbUsuarioProjeto_tbProjeto` (`idProjeto`),
  CONSTRAINT `fk_tbUsuarioProjeto_tbProjeto` FOREIGN KEY (`idProjeto`) REFERENCES `tbprojeto` (`id`),
  CONSTRAINT `fk_tbUsuarioProjeto_tbUsuario` FOREIGN KEY (`idUsuario`) REFERENCES `tbusuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbusuarioprojeto`
--

LOCK TABLES `tbusuarioprojeto` WRITE;
/*!40000 ALTER TABLE `tbusuarioprojeto` DISABLE KEYS */;
INSERT INTO `tbusuarioprojeto` VALUES (9,1,1),(10,1,1),(3,2,0),(9,2,0),(5,3,0);
/*!40000 ALTER TABLE `tbusuarioprojeto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbveiculo`
--

DROP TABLE IF EXISTS `tbveiculo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbveiculo` (
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
  CONSTRAINT `fk_tbVeiculo_tbCondutor` FOREIGN KEY (`idCondutor`) REFERENCES `tbcondutor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbveiculo`
--

LOCK TABLES `tbveiculo` WRITE;
/*!40000 ALTER TABLE `tbveiculo` DISABLE KEYS */;
INSERT INTO `tbveiculo` VALUES (10,10,'Passat','Celta','2012-02-01','2039-01-01','12:00:00','07:00:00'),(11,12,'Testa','Futuristico','2020-01-02','2012-02-02','09:00:00','02:00:00');
/*!40000 ALTER TABLE `tbveiculo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbviagem`
--

DROP TABLE IF EXISTS `tbviagem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `tbviagem` (
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
  CONSTRAINT `fk_tbViagem_tbTarefa` FOREIGN KEY (`idTarefa`) REFERENCES `tbtarefa` (`id`),
  CONSTRAINT `fk_tbViagem_tbVeiculo` FOREIGN KEY (`idVeiculo`) REFERENCES `tbveiculo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbviagem`
--

LOCK TABLES `tbviagem` WRITE;
/*!40000 ALTER TABLE `tbviagem` DISABLE KEYS */;
INSERT INTO `tbviagem` VALUES (11,11,21,'Foz','Londrina','2019-03-01','2031-02-01','jdsfhksdjfh','kjdhskjfhsdk','12324','2019-02-01','2021-02-01','09:00:00','02:00:00',2659.00,1),(12,11,20,'Foz','Londrina','2019-03-01','2031-02-01','jdsfhksdjfh','kjdhskjfhsdk','12324','2019-02-01','2021-02-01','09:00:00','02:00:00',834.00,2);
/*!40000 ALTER TABLE `tbviagem` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-06-17 22:12:37

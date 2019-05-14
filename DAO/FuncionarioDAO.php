<?php

require_once '../Model/Usuario.php';
require_once 'CrudDAO.php';

class FuncionarioDAO extends CrudDAO {

    public function cadastrar(Usuario $func){

        $insert = $this->pdo->prepare("INSERT INTO tbFuncionario (senha,usuario,NomeCompleto,dataNascimento,cpf,rg,dataEmissao,tipo,email) VALUES (:senha, :usuario, :nome, :dtNasc, :cpf, :rg, :dtEmissao, :tipo, :email)");
        $insert->bindValue(1,$func->getSenha());
        $insert->bindValue(2,$func->getLogin());
        $insert->bindValue(3,$func->getNomeCompleto());
        $insert->bindValue(4,$func->getDtNascimento());
        $insert->bindValue(5,$func->getCpf());
        $insert->bindValue(6,$func->getRg());
        $insert->bindValue(7,$func->getDtEmissao());
        $insert->bindValue(8,$func->getTipo());
        $insert->bindValue(9,$func->getEmail());

        $insert->execute();
    }

    public function alterar(Usuario $func){
        $update = $this->pdo->prepare("UPDATE tbFuncionario SET senha=?,usuario=?,NomeCompleto=?,dataNascimento=?,cpf=?,rg=?, dataEmissao=?, tipo=?, email=? WHERE id=?");
        $update->bindValue(1,$func->getSenha());
        $update->bindValue(2,$func->getLogin());
        $update->bindValue(3,$func->getNomeCompleto());
        $update->bindValue(4,$func->getDtNascimento());
        $update->bindValue(5,$func->getCpf());
        $update->bindValue(6,$func->getRg());
        $update->bindValue(7,$func->getDtEmissao());
        $update->bindValue(8,$func->getTipo());
        $update->bindValue(9,$func->getEmail());
        $update->bindValue(10,$func->getId());

        $update->execute();

    }

    public function excluir($id){
        $delete = $this->pdo->prepare("DELETE FROM tbFuncionario WHERE id=?");
        $delete->bindValue(1,$id);

        $delete->execute();
    }

    public function listarPorId($id){
        $buscaId = $this->pdo->prepare("SELECT * FROM tbFuncionario WHERE id=?");
        $buscaId->bindValue(1,$id);
        $buscaId->execute();

        $linha =  $buscaId->fetch(PDO::FETCH_ASSOC);
        $fun = new Usuario();
        $fun->setNomeCompleto($linha['NomeCompleto']);
        $fun->setEmail($linha['email']);
        $fun->setCpf($linha['cpf']);
        $fun->setId($linha['id']);
        $fun->setRg($linha['rg']);
        $fun->setDtNascimento($linha['dataNascimento']);
        $fun->setTipo($linha['tipo']);
        $fun->setDtEmissao('dataEmissao');
        $fun->setSenha($linha['senha']);

        return $fun;
    }

    public function listarPorUsuario($usuario){
        $buscaId = $this->pdo->prepare("SELECT * FROM tbFuncionario WHERE usuario=?");
        $buscaId->bindValue(1,$usuario);
        $buscaId->execute();

        $linha =  $buscaId->fetch(PDO::FETCH_ASSOC);
        $fun = new Usuario();
        $fun->setNomeCompleto($linha['NomeCompleto']);
        $fun->setEmail($linha['email']);
        $fun->setCpf($linha['cpf']);
        $fun->setId($linha['id']);
        $fun->setRg($linha['rg']);
        $fun->setDtNascimento($linha['dataNascimento']);
        $fun->setTipo($linha['tipo']);
        $fun->setDtEmissao('dataEmissao');
        $fun->setSenha($linha['senha']);

        return $fun;
    }

    public function listar(){
        $busca = $this->pdo->prepare("SELECT * FROM tbFuncionario");
        $busca->execute();

        $funcionarios =  array();

        $linhas =  $busca->fetchAll(PDO::FETCH_ASSOC);
        foreach ($linhas as $linha){
            $fun = new Usuario();
            $fun->setNomeCompleto($linha['NomeCompleto']);
            $fun->setEmail($linha['email']);
            $fun->setCpf($linha['cpf']);
            $fun->setId($linha['id']);
            $fun->setRg($linha['rg']);
            $fun->setDtNascimento($linha['dataNascimento']);
            $fun->setTipo($linha['tipo']);
            $fun->setDtEmissao('dataEmissao');
            $fun->setSenha($linha['senha']);
            $fun->setLogin($linha['usuario']);

            $funcionarios[]=$fun;
        }
        return $funcionarios;
    }

    function consultar($login,$senha){
        $stm = $this->pdo->prepare("select * from tbUsuario where login = :usuario and senha = :senha");
        $stm->bindValue(':usuario',$login);
        $stm->bindValue(':senha',$senha);
        $stm->execute();

        if($stm->rowCount()!=0){
            return true;
        }else{
            return false;
        }
    }
}

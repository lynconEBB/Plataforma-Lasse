<?php

require_once '../Services/Autoload.php';

class UsuarioDao extends CrudDao {

    public function cadastrar(UsuarioModel $func){

        $insert = $this->pdo->prepare("INSERT INTO tbUsuario (senha,login,nomeCompleto,cpf,rg,dataDeEmissao,tipo,email,valorHora,formacao,atuacao,dtNascimento) VALUES (:senha, :login, :nomeCompleto, :cpf, :rg, :dataDeEmissao, :tipo, :email, :valorHora, :formacao, :atuacao,:dtNasc)");
        $insert->bindValue(':senha',$func->getSenha());
        $insert->bindValue(':login',$func->getLogin());
        $insert->bindValue(':nomeCompleto',$func->getNomeCompleto());
        $insert->bindValue(':cpf',$func->getCpf());
        $insert->bindValue(':rg',$func->getRg());
        $insert->bindValue(':dataDeEmissao',$func->getDtEmissao());
        $insert->bindValue(':tipo',$func->getTipo());
        $insert->bindValue(':email',$func->getEmail());
        $insert->bindValue(':valorHora',$func->getValorHora());
        $insert->bindValue(':formacao',$func->getFormacao());
        $insert->bindValue(':atuacao',$func->getAtuacao());
        $insert->bindValue(':dtNasc',$func->getDtNascimento());

        $insert->execute();
    }

    public function alterar(UsuarioModel $func){
        $update = $this->pdo->prepare("UPDATE tbUsuario SET login=:login, NomeCompleto=:nome, dtNascimento = :dtNasc, cpf = :cpf, rg = :rg, dataDeEmissao= :dtEmissao, tipo = :tipo, email= :email,
                atuacao = :atuacao, formacao =:formacao, valorHora = :valorHora WHERE id = :id");
        $update->bindValue(':login',$func->getLogin());
        $update->bindValue(':nome',$func->getNomeCompleto());
        $update->bindValue(':dtNasc',$func->getDtNascimento());
        $update->bindValue(':cpf',$func->getCpf());
        $update->bindValue(':rg',$func->getRg());
        $update->bindValue(':dtEmissao',$func->getDtEmissao());
        $update->bindValue(':tipo',$func->getTipo());
        $update->bindValue(':email',$func->getEmail());
        $update->bindValue(':atuacao',$func->getAtuacao());
        $update->bindValue(':formacao',$func->getFormacao());
        $update->bindValue(':valorHora',$func->getValorHora());
        $update->bindValue(':id',$func->getId());

        $update->execute();
    }

    public function excluir($id){
        $delete = $this->pdo->prepare("DELETE FROM tbUsuario WHERE id=?");
        $delete->bindValue(1,$id);

        $delete->execute();
    }

    public function listar(){
        $busca = $this->pdo->prepare("SELECT * FROM tbUsuario");
        $busca->execute();

        $funcionarios =  array();

        $linhas =  $busca->fetchAll(PDO::FETCH_ASSOC);
        foreach ($linhas as $linha){
            $fun = new UsuarioModel($linha['nomeCompleto'],$linha['login'],null,$linha['dtNascimento'],$linha['cpf'],$linha['rg'],$linha['dataDeEmissao'],
                $linha['tipo'],$linha['email'],$linha['atuacao'],$linha['formacao'],$linha['valorHora'],$linha['id']);;
            $funcionarios[]=$fun;
        }
        return $funcionarios;
    }

    public function listarPorLogin($login){
        $stm = $this->pdo->prepare("SELECT * FROM tbUsuario WHERE login= :login");
        $stm->bindValue(':login',$login);
        $stm->execute();

        $linha =  $stm->fetch(PDO::FETCH_ASSOC);
        $fun = new UsuarioModel($linha['nomeCompleto'],$linha['login'],$linha['senha'],$linha['dtNascimento'],$linha['cpf'],$linha['rg'],$linha['dataDeEmissao'],
            $linha['tipo'],$linha['email'],$linha['atuacao'],$linha['formacao'],$linha['valorHora'],$linha['id']);

        return $fun;
    }

    public function listarPorId($id){

        $projetoDAO = new ProjetoDao();
        $projetos = $projetoDAO->listarPorIdUsuario($id);

        $stm = $this->pdo->prepare("SELECT * FROM tbUsuario WHERE id= :id");
        $stm->bindValue(':id',$id);
        $stm->execute();

        $linha =  $stm->fetch(PDO::FETCH_ASSOC);
        $fun = new UsuarioModel($linha['nomeCompleto'],$linha['login'],$linha['senha'],$linha['dtNascimento'],$linha['cpf'],$linha['rg'],$linha['dataDeEmissao'],
            $linha['tipo'],$linha['email'],$linha['atuacao'],$linha['formacao'],$linha['valorHora'],$linha['id'],$projetos);

        return $fun;
    }

    function consultar($login,$senha){
        $stm = $this->pdo->prepare("select * from tbUsuario where login = :usuario and senha = :senha");
        $stm->bindValue(':usuario',$login);
        $stm->bindValue(':senha',$senha);
        $stm->execute();

        if($stm->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
}

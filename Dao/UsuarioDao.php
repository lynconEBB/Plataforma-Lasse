<?php

class UsuarioDao extends CrudDao {

    public function cadastrar(UsuarioModel $usuario){
        $insert = $this->pdo->prepare("INSERT INTO tbUsuario (senha,login,nomeCompleto,cpf,rg,dataDeEmissao,tipo,email,valorHora,formacao,atuacao,dtNascimento) VALUES (:senha, :login, :nomeCompleto, :cpf, :rg, :dataDeEmissao, :tipo, :email, :valorHora, :formacao, :atuacao,:dtNasc)");
        $insert->bindValue(':senha',$usuario->getSenha());
        $insert->bindValue(':login',$usuario->getLogin());
        $insert->bindValue(':nomeCompleto',$usuario->getNomeCompleto());
        $insert->bindValue(':cpf',$usuario->getCpf());
        $insert->bindValue(':rg',$usuario->getRg());
        $insert->bindValue(':dataDeEmissao',$usuario->getDtEmissao());
        $insert->bindValue(':tipo',$usuario->getTipo());
        $insert->bindValue(':email',$usuario->getEmail());
        $insert->bindValue(':valorHora',$usuario->getValorHora());
        $insert->bindValue(':formacao',$usuario->getFormacao());
        $insert->bindValue(':atuacao',$usuario->getAtuacao());
        $insert->bindValue(':dtNasc',$usuario->getDtNascimento());

        $insert->execute();
    }

    public function alterar(UsuarioModel $usuario){
        $update = $this->pdo->prepare("UPDATE tbUsuario SET login=:login, NomeCompleto=:nome, dtNascimento = :dtNasc, cpf = :cpf, rg = :rg, dataDeEmissao= :dtEmissao, tipo = :tipo, email= :email,
                atuacao = :atuacao, formacao =:formacao, valorHora = :valorHora WHERE id = :id");
        $update->bindValue(':login',$usuario->getLogin());
        $update->bindValue(':nome',$usuario->getNomeCompleto());
        $update->bindValue(':dtNasc',$usuario->getDtNascimento());
        $update->bindValue(':cpf',$usuario->getCpf());
        $update->bindValue(':rg',$usuario->getRg());
        $update->bindValue(':dtEmissao',$usuario->getDtEmissao());
        $update->bindValue(':tipo',$usuario->getTipo());
        $update->bindValue(':email',$usuario->getEmail());
        $update->bindValue(':atuacao',$usuario->getAtuacao());
        $update->bindValue(':formacao',$usuario->getFormacao());
        $update->bindValue(':valorHora',$usuario->getValorHora());
        $update->bindValue(':id',$usuario->getId());

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

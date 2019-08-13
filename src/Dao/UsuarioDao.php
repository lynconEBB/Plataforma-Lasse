<?php

namespace Lasse\LPM\Dao;

use Lasse\LPM\Model\UsuarioModel;
use PDO;
use PDOException;

class UsuarioDao extends CrudDao {

    public function cadastrar(UsuarioModel $usuario){
        $insert = $this->pdo->prepare("INSERT INTO tbUsuario (senha,login,nomeCompleto,cpf,rg,dataDeEmissao,email,valorHora,formacao,atuacao,dtNascimento,tipo) VALUES (:senha, :login, :nomeCompleto, :cpf, :rg, :dataDeEmissao, :email, :valorHora, :formacao, :atuacao,:dtNasc,:tipo)");
        $insert->bindValue(':senha',$usuario->getSenha());
        $insert->bindValue(':login',$usuario->getLogin());
        $insert->bindValue(':nomeCompleto',$usuario->getNomeCompleto());
        $insert->bindValue(':cpf',$usuario->getCpf());
        $insert->bindValue(':rg',$usuario->getRg());
        $insert->bindValue(':dataDeEmissao',$usuario->getDtEmissao()->format("Y-m-d"));
        $insert->bindValue(':email',$usuario->getEmail());
        $insert->bindValue(':valorHora',$usuario->getValorHora());
        $insert->bindValue(':formacao',$usuario->getFormacao());
        $insert->bindValue(':atuacao',$usuario->getAtuacao());
        $insert->bindValue(':dtNasc',$usuario->getDtNascimento()->format("Y-m-d"));
        $insert->bindValue(':tipo','1');

        $insert->execute();
    }

    public function alterar(UsuarioModel $usuario){
        $update = $this->pdo->prepare("UPDATE tbUsuario SET login=:login, NomeCompleto=:nome, dtNascimento = :dtNasc, cpf = :cpf, rg = :rg, dataDeEmissao= :dtEmissao, email= :email,
                atuacao = :atuacao, formacao =:formacao, valorHora = :valorHora WHERE id = :id");
        $update->bindValue(':login',$usuario->getLogin());
        $update->bindValue(':nome',$usuario->getNomeCompleto());
        $update->bindValue(':dtNasc',$usuario->getDtNascimento()->format('Y-m-d'));
        $update->bindValue(':cpf',$usuario->getCpf());
        $update->bindValue(':rg',$usuario->getRg());
        $update->bindValue(':dtEmissao',$usuario->getDtEmissao()->format('Y-m-d'));
        $update->bindValue(':email',$usuario->getEmail());
        $update->bindValue(':atuacao',$usuario->getAtuacao());
        $update->bindValue(':formacao',$usuario->getFormacao());
        $update->bindValue(':valorHora',$usuario->getValorHora());
        $update->bindValue(':id',$usuario->getId());

        $update->execute();
    }

    public function excluir($id){
        $delete = $this->pdo->prepare("UPDATE tbUsuario SET estado = :estado WHERE id=:id");
        $delete->bindValue(':id',$id);
        $delete->bindValue(':estado','desativado');
        $delete->execute();
    }

    public function listar(){
        $busca = $this->pdo->prepare("SELECT * FROM tbUsuario WHERE estado='ativado'");
        $busca->execute();

        $funcionarios =  array();

        $linhas =  $busca->fetchAll(PDO::FETCH_ASSOC);
        if (count($linhas) !== 0){
            foreach ($linhas as $linha){
                $fun = new UsuarioModel($linha['nomeCompleto'],$linha['login'],null,$linha['dtNascimento'],$linha['cpf'],$linha['rg'],$linha['dataDeEmissao'],$linha['email'],$linha['atuacao'],$linha['formacao'],$linha['valorHora'],$linha['id']);
                $funcionarios[]=$fun;
            }
            return $funcionarios;
        }else{
            return false;
        }

    }

    public function listarPorIdProjeto($idProjeto){
        $busca = $this->pdo->prepare("SELECT tbUsuario.nomeCompleto,tbUsuario.login, tbUsuario.dtNascimento,tbUsuario.cpf, tbUsuario.rg, tbUsuario.dataDeEmissao, tbUsuario.email,tbUsuario.atuacao,tbUsuario.formacao,
tbUsuario.valorHora,tbUsuario.id FROM tbUsuario inner join tbUsuarioProjeto on tbUsuario.id = tbUsuarioProjeto.idUsuario where tbUsuarioProjeto.idProjeto = :id");
        $busca->bindParam(':id',$idProjeto);
        $busca->execute();

        $funcionarios =  array();
        $linhas = $busca->fetchAll(PDO::FETCH_ASSOC);
        if (count($linhas) != 0) {
            foreach ($linhas as $linha) {
                $fun = new UsuarioModel($linha['nomeCompleto'], $linha['login'], null, $linha['dtNascimento'], $linha['cpf'], $linha['rg'], $linha['dataDeEmissao'], $linha['email'], $linha['atuacao'], $linha['formacao'], $linha['valorHora'], $linha['id']);
                $funcionarios[] = $fun;
            }
            return $funcionarios;
        }else{
            return false;
        }
    }

    public function listarPorLogin($login){
        $stm = $this->pdo->prepare("SELECT * FROM tbUsuario WHERE login = :login AND estado = :estado");
        $stm->bindValue(':login',$login);
        $stm->bindValue(':estado','ativado');
        $stm->execute();

        $linha =  $stm->fetch(PDO::FETCH_ASSOC);
        if ($linha != false) {
            $fun = new UsuarioModel($linha['nomeCompleto'],$linha['login'],$linha['senha'],$linha['dtNascimento'],$linha['cpf'],$linha['rg'],$linha['dataDeEmissao'],$linha['email'],$linha['atuacao'],$linha['formacao'],$linha['valorHora'],$linha['id']);
            return $fun;
        }else {
            return false;
        }
    }

    public function listarPorId($id){
        $stm = $this->pdo->prepare("SELECT * FROM tbUsuario WHERE id= :id");
        $stm->bindValue(':id',$id);
        $stm->execute();

        $linha =  $stm->fetch(PDO::FETCH_ASSOC);
        if ($linha != false) {
            $fun = new UsuarioModel($linha['nomeCompleto'], $linha['login'], $linha['senha'], $linha['dtNascimento'], $linha['cpf'], $linha['rg'], $linha['dataDeEmissao'], $linha['email'], $linha['atuacao'], $linha['formacao'], $linha['valorHora'], $linha['id']);
            return $fun;
        }else{
            return false;
        }
    }
}

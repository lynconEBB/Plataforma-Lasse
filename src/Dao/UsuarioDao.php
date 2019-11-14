<?php

namespace Lasse\LPM\Dao;

use Lasse\LPM\Model\UsuarioModel;
use PDO;
use PDOException;

class UsuarioDao extends CrudDao {

    public function cadastrar(UsuarioModel $usuario){
        $insert = $this->pdo->prepare("INSERT INTO tbUsuario (estado,senha,login,nomeCompleto,cpf,rg,dataDeEmissao,email,valorHora,formacao,atuacao,dtNascimento,admin,caminhoFoto) VALUES 
            ('desativado',:senha, :login, :nomeCompleto, :cpf, :rg, :dataDeEmissao, :email, :valorHora, :formacao, :atuacao,:dtNasc,:tipo,:foto)");
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
        $insert->bindValue(':tipo',$usuario->getAdmin());
        $insert->bindValue(':foto',$usuario->getFoto());

        $insert->execute();
    }

    public function alterar(UsuarioModel $usuario){
        $update = $this->pdo->prepare("UPDATE tbUsuario SET login=:login, NomeCompleto=:nome, dtNascimento = :dtNasc, cpf = :cpf, rg = :rg, dataDeEmissao= :dtEmissao, email= :email,
                atuacao = :atuacao, formacao =:formacao, valorHora = :valorHora, caminhoFoto= :foto WHERE id = :id");
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
        $update->bindValue(':foto',$usuario->getFoto());
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
                $fun = new UsuarioModel($linha['nomeCompleto'],$linha['login'],null,$linha['dtNascimento'],$linha['cpf'],$linha['rg'],$linha['dataDeEmissao'],$linha['email'],$linha['atuacao'],$linha['formacao'],$linha['valorHora'],$linha['caminhoFoto'],$linha['admin'],$linha['id']);
                $funcionarios[]=$fun;
            }
            return $funcionarios;
        }else{
            return false;
        }
    }

    public function listarPorIdProjeto($idProjeto){
        $busca = $this->pdo->prepare("SELECT tbUsuario.nomeCompleto,tbUsuario.login, tbUsuario.dtNascimento,tbUsuario.cpf, tbUsuario.rg, tbUsuario.dataDeEmissao, tbUsuario.email,tbUsuario.atuacao,tbUsuario.formacao,
tbUsuario.valorHora,tbUsuario.id,tbUsuario.admin,tbUsuario.caminhoFoto FROM tbUsuario inner join tbUsuarioProjeto on tbUsuario.id = tbUsuarioProjeto.idUsuario where tbUsuarioProjeto.idProjeto = :id");
        $busca->bindParam(':id',$idProjeto);
        $busca->execute();

        $funcionarios =  array();
        $linhas = $busca->fetchAll(PDO::FETCH_ASSOC);
        if (count($linhas) > 0) {
            foreach ($linhas as $linha) {
                $fun = new UsuarioModel($linha['nomeCompleto'], $linha['login'], null, $linha['dtNascimento'], $linha['cpf'], $linha['rg'], $linha['dataDeEmissao'], $linha['email'], $linha['atuacao'], $linha['formacao'], $linha['valorHora'], $linha['caminhoFoto'],$linha['admin'],$linha['id']);
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
            $fun = new UsuarioModel($linha['nomeCompleto'],$linha['login'],$linha['senha'],$linha['dtNascimento'],$linha['cpf'],$linha['rg'],$linha['dataDeEmissao'],$linha['email'],$linha['atuacao'],$linha['formacao'],$linha['valorHora'],$linha['caminhoFoto'],$linha['admin'],$linha['id']);
            return $fun;
        }else {
            return false;
        }
    }

    public function listarPorCpf($cpf) {
        $stm = $this->pdo->prepare("SELECT * FROM tbUsuario WHERE cpf = :cpf AND estado = :estado");
        $stm->bindValue(':cpf',$cpf);
        $stm->bindValue(':estado','ativado');
        $stm->execute();

        $linha =  $stm->fetch(PDO::FETCH_ASSOC);
        if ($linha != false) {
            $fun = new UsuarioModel($linha['nomeCompleto'],$linha['login'],$linha['senha'],$linha['dtNascimento'],$linha['cpf'],$linha['rg'],$linha['dataDeEmissao'],$linha['email'],$linha['atuacao'],$linha['formacao'],$linha['valorHora'],$linha['caminhoFoto'],$linha['admin'],$linha['id']);
            return $fun;
        }else {
            return false;
        }
    }

    public function listarPorEmail($email) {
        $stm = $this->pdo->prepare("SELECT * FROM tbUsuario WHERE email = :email AND estado = :estado");
        $stm->bindValue(':email',$email);
        $stm->bindValue(':estado','ativado');
        $stm->execute();

        $linha =  $stm->fetch(PDO::FETCH_ASSOC);
        if ($linha != false) {
            $fun = new UsuarioModel($linha['nomeCompleto'],$linha['login'],$linha['senha'],$linha['dtNascimento'],$linha['cpf'],$linha['rg'],$linha['dataDeEmissao'],$linha['email'],$linha['atuacao'],$linha['formacao'],$linha['valorHora'],$linha['caminhoFoto'],$linha['admin'],$linha['id']);
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
            $fun = new UsuarioModel($linha['nomeCompleto'], $linha['login'], $linha['senha'], $linha['dtNascimento'], $linha['cpf'], $linha['rg'], $linha['dataDeEmissao'], $linha['email'], $linha['atuacao'], $linha['formacao'], $linha['valorHora'], $linha['caminhoFoto'],$linha['admin'],$linha['id']);
            return $fun;
        }else{
            return false;
        }
    }

    public function reativar($idUsuario) {
        $comando = "UPDATE tbUsuario SET estado = 'ativado' WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(":id",$idUsuario);
        $stm->execute();
    }

    public function listarUsuariosForaProjeto($idProjeto)
    {
        $busca = $this->pdo->prepare("select login,id from tbUsuario where id not in (select distinct idUsuario from tbUsuarioProjeto where idProjeto = :idProjeto)");
        $busca->bindParam(':idProjeto',$idProjeto);
        $busca->execute();

        $linhas = $busca->fetchAll(PDO::FETCH_ASSOC);
        if (count($linhas) > 0) {
            return $linhas;
        }else{
            return false;
        }
    }

    public function setTokenConfirmacao($token,$idUsuario) {
        $comando = "UPDATE tbUsuario SET tokenConfirmacao = :token WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(":token",$token);
        $stm->bindParam(":id",$idUsuario);
        $stm->execute();
    }

    public function setTokenRecuperacao($token,$idUsuario) {
        $comando = "UPDATE tbUsuario SET tokenValido = :token WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(":token",$token);
        $stm->bindParam(":id",$idUsuario);
        $stm->execute();
    }

    public function getTokenRecuperacao($idUsuario) {
        $comando = "SELECT tokenValido FROM tbUsuario WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(":id",$idUsuario);
        $stm->execute();
        $linha = $stm->fetch(PDO::FETCH_ASSOC);
        return $linha['tokenValido'];
    }

    public function getTokenConfirmacao($idUsuario) {
        $comando = "SELECT tokenConfirmacao FROM tbUsuario WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam(":id",$idUsuario);
        $stm->execute();
        $linha = $stm->fetch(PDO::FETCH_ASSOC);
        return $linha['tokenConfirmacao'];
    }

    public function alterarSenha ($novaSenha,$idUsuario) {
        $comando = "UPDATE tbUsuario SET senha = :senha WHERE id = :id";
        $stm = $this->pdo->prepare($comando);
        $stm->bindParam("senha",$novaSenha);
        $stm->bindParam(":id",$idUsuario);
        $stm->execute();
    }
}

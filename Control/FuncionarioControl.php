<?php
require_once '../Model/Funcionario.php';
require_once '../DAO/FuncionarioDAO.php';
class FuncionarioControl{
    public function __construct(){
        if(isset($_REQUEST['acao'])){
            $this->decideAcao($_REQUEST['acao']);
        }
    }
    public function decideAcao($acao){
        switch ($acao){
            case 1:
                $func = new Funcionario();
                $func->setNomeCompleto($_POST['nome']);
                $func->setEmail($_POST['email']);
                $func->setUsuario($_POST['usuario']);
                $func->setSenha($_POST['senha']);
                $func->setDtNascimento($_POST['dtNasc']);
                $func->setRg($_POST['rg']);
                $func->setDtEmissao($_POST['dtEmissao']);
                $func->setCpf($_POST['cpf']);
                $func->setTipo($_POST['tipo']);

                $funcDAO = new FuncionarioDAO();
                if($funcDAO->inserir($func)){
                    header("Location:../View/menu.php");
                }else{
                    echo "Erro na insersão do novo funcionario";
                }
            case 2:
                $func = new Funcionario();
                $func->setId($_POST['id']);
                $func->setNomeCompleto($_POST['nome']);
                $func->setEmail($_POST['email']);
                $func->setUsuario($_POST['usuario']);
                $func->setSenha($_POST['senha']);
                $func->setDtNascimento($_POST['dtNasc']);
                $func->setRg($_POST['rg']);
                $func->setDtEmissao($_POST['dtEmissao']);
                $func->setCpf($_POST['cpf']);
                $func->setTipo($_POST['tipo']);

                $funcDAO = new FuncionarioDAO();
                if($funcDAO->alterar($func)){
                    header("Location:../View/menu.php");
                }else{
                    echo "Erro na alteração do novo funcionario";
                }
            case 3:
                $funcDAO = new FuncionarioDAO();
                if($funcDAO->excluir($_GET['id'])){
                    header("Location:../View/menuFunc.php");
                }else{
                    echo "Erro na exclusao do funcionario";
                }
        }
    }
}
new FuncionarioControl();

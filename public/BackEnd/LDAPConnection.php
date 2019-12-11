<?php


class LDAPConnection
{
    private const DEFAULT_DN = 'fpti\\bindtpweb';
    private const PASSWORD = 'TlEpIvJeh2OaqQW';
    private const BASE_DN = 'ou=colaboradores,dc=fpti,dc=pti,dc=org,dc=br';
    private const PORT = '389';
    private const HOST = '179.106.218.196';
    private const MAIL_FILTER = '(&(mail={0})(objectClass=user)(memberOf=cn=lasse,ou=lasse,ou=dt,ou=colaboradores,dc=fpti,dc=pti,dc=org,dc=br))';
    private const USER_FILTER = '(&(sAMAccountName={0})(objectClass=user)(memberOf=cn=lasse,ou=lasse,ou=dt,ou=colaboradores,dc=fpti,dc=pti,dc=org,dc=br))';
    private $ldapCon;

    public function __construct()
    {
        $this->ldapCon = ldap_connect(self::HOST,self::PORT);

        if (!$this->ldapCon) {
            die("Erro durante conexão com servidor LDAP");
        } else {
            if (!ldap_bind($this->ldapCon,self::DEFAULT_DN,self::PASSWORD)) {
                die("Erro durante bind com LDAP");
            }
        }
    }

    public function getAllUsers() {
        $result = ldap_search($this->ldapCon,self::BASE_DN, '(&(objectClass=user)(memberOf=cn=lasse,ou=lasse,ou=dt,ou=colaboradores,dc=fpti,dc=pti,dc=org,dc=br))',['mail']);
        $info = ldap_get_entries($this->ldapCon, $result);
        echo "<pre>";
            print_r($info);
        echo "</pre>";
    }

    public function search(string $login,string $password)
    {
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $customFilter =  str_replace('{0}',$login,self::MAIL_FILTER);
        } else {
            $customFilter = str_replace('{0}',$login,self::USER_FILTER);
        }

        $result = ldap_search($this->ldapCon,self::BASE_DN, $customFilter,['mail',]);
        $userInfo = ldap_get_entries($this->ldapCon, $result);

        if ($userInfo['count'] > 0) {
            $userInfo = $userInfo[0];
            $userDN = $userInfo['dn'];
            if (@ldap_bind($this->ldapCon,$userDN,$password)) {
                echo "Logado com sucesso";
            } else {
                echo "Senha inválida";
            }
        } else {
            echo "Usuario não encontrado";
        }
    }

}

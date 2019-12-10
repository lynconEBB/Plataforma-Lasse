<?php


class LDAPConnection
{
    private const USERS_DN = 'fpti\\bindtpweb';
    private const PASSWORD = 'TlEpIvJeh2OaqQW';
    private const BASE_DN = 'ou=colaboradores,dc=fpti,dc=pti,dc=org,dc=br';
    private const PORT = '389';
    private const HOST = '179.106.218.196';
    private const SEARCH_FILTER = '(&(sAMAccountName={0})&(objectClass=user)(memberOf=cn=lasse,ou=lasse,ou=dt,ou=colaboradores,dc=fpti,dc=pti,dc=org,dc=br))';

    public function __construct()
    {
        $ladpCon = ldap_connect(self::HOST,self::PORT);
        if ($ladpCon) {
            if (ldap_bind($ladpCon,self::USERS_DN,self::PASSWORD)) {
                $result = ldap_search($ladpCon,self::BASE_DN, "(&(objectClass=user)(memberOf=cn=lasse,ou=lasse,ou=dt,ou=colaboradores,dc=fpti,dc=pti,dc=org,dc=br))") or die ("Error in search query: ".ldap_error($ladpCon));
                $info = ldap_get_entries($ladpCon, $result);
                echo "<pre>";
                print_r($info);
                echo "</pre>";

            } else {
                echo "erro no bind";
            }
        } else {
            echo "erro";
        }
    }

}

<?php

namespace App\Controller;

use App\Service\HolidayService;
use App\UserDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * @Route("/default", name="default")
     */
    public function index(UserDataProvider $userDataProvider, HolidayService $holidayService)
    {
//        $time = $userDataProvider->getUserTime('ivan.melnichuk', '01/01/2019', '31/01/2019');

        $username = 'ivan.melnichuk';
        $username = trim($username);
        $ldappass = '!rCLV@HVbFkV4p7fNFz4';
        $ldappass = trim($ldappass);

        $ldaprdn = 'uid=' . $username . ',cn=users,cn=accounts,dc=onyxenterprises,dc=com';
        $ldapconn = ldap_connect("ipa1.onyxenterprises.com", '389')
        or die("Unavailable to connect LDAP.");
        if ($ldapconn) {
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

            $bind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
        }
        var_dump($bind);
        $ldapconn = ldap_connect("ipa2.onyxenterprises.com", '389')
        or die("Unavailable to connect LDAP.");
        if ($ldapconn) {
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

            $bind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
        }
        var_dump($bind);
        $ldapconn = ldap_connect("ipa.onyxenterprises.com", '389')
        or die("Unavailable to connect LDAP.");
        if ($ldapconn) {
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

            $bind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
        }
        var_dump($bind);
        $dateTime = new \DateTime();
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}

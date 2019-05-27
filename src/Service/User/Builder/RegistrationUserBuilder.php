<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 21.02.2019
 * Time: 10:03
 */

namespace App\Service\User\Builder;

use App\Entity\SDTEmailAssignee;
use App\Entity\User;
use DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationUserBuilder
{
    public static function build(User $user, UserPasswordEncoderInterface $passwordEncoder, $password)
    {
        $user->setRoles(['ROLE_USER', 'ROLE_SDT_REQUEST']);
        $user->setCreateDate(new DateTime());
        return $user->setPassword($passwordEncoder->encodePassword($user, $password));
    }

    /** TODO: move to own builder
     * @param User $user
     * @return array
     */
    public function buildUserEmails(User $user): array
    {
        $returnArray = [];
        foreach ($this->getEmailByTeam($user->getTeam()->getName()) as $email) {
            $returnArray[] = (new SDTEmailAssignee())->setUser($user)->setEmail($email);
        }
        return $returnArray;
    }

    private function getEmailByTeam(string $team): array
    {
        $returnArray = [];
        if ($team === 'Display Team') {
            $returnArray = [
                'timerecords@onyx.com',
                'team.programmers@onyx.com',
                'valeriya.po@onyx.com',
                'dmitriy.la@onyx.com',
                'vitaliy.ko@onyx.com',
                'ivan.melnichuk@onyx.com'
            ];
        }

        if ($team === 'SELL Team') {
            $returnArray = [
                'timerecords@onyx.com',
                'team.programmers@onyx.com',
                'valeriya.po@onyx.com',
                'dmitriy.la@onyx.com',
                'vitaliy.ko@onyx.com',
            ];
        }

        if ($team === 'Create Team') {
            $returnArray = [
                'timerecords@onyx.com',
                'team.programmers@onyx.com',
                'anna.my@onyx,com',
                'vitaliy.ko@onyx.com',
            ];
        }

        if ($team === 'NOC Team') {
            $returnArray = [
                'timerecords@onyx.com',
                'team.admins@onyx.com',
                'andrey.ku@onyx.com',
                'oleksandra.bi@onyx.com',
            ];
        }
        return $returnArray;
    }
}

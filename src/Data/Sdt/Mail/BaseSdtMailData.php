<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 31.01.2019
 * Time: 11:30
 */

namespace App\Data\Sdt\Mail;

class BaseSdtMailData
{
    private $toEmails = [
        'timerecords@onyx.com',
        'team.programmers@onyx.com',
        'valeriya.po@onyx.com',
        'dmitriy.la@onyx.com',
        'vitaliy.ko@onyx.com',
        'ivan.melnichuk@onyx.com'
    ];
    private $userName;

    public function __construct(string $userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return array
     */
    public function getToEmails(): array
    {
//        return $this->toEmails;
        return [
            'timerecords@onyx.com',
            'team.programmers@onyx.com',
            'valeriya.po@onyx.com',
            'dmitriy.la@onyx.com',
            'vitaliy.ko@onyx.com',
            'ivan.melnichuk@onyx.com'
        ];
    }

    /**
     * @param array $toEmails
     */
    public function setToEmails(array $toEmails): void
    {
        $this->toEmails = $toEmails;
    }

    public function getSubject(): string
    {
        return 'SDT Request || ' . $this->userName . ', Programming Team';
    }

    public function getFromEmail()
    {
        return getenv('LOCAL_EMAIL');
    }
}

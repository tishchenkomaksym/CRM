<?php


namespace App\Service\GlobalObjects;


use App\Entity\UserInfo;
use App\Repository\UserInfoRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GlobalObjects
{
    /**
     * @var UserInfoRepository
     */
    private $userInfoRepository;

    private $user;

    /**
     * GlobalObjects constructor.
     * @param UserInfoRepository $userInfoRepository
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(UserInfoRepository $userInfoRepository,
                                TokenStorageInterface $tokenStorage)
    {
        $this->userInfoRepository = $userInfoRepository;
        if ($tokenStorage->getToken() !== null ){
            $this->user = $tokenStorage->getToken()->getUser();
        }else {
            $this->user = '';
        }

    }

    public function getUserInformation(): ?UserInfo
    {
        return $this->userInfoRepository->findOneBy(['user' => $this->user->getId()]);
    }
}
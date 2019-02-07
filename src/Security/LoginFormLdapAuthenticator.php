<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormLdapAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $entityManager;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * LoginFormLdapAuthenticator constructor.
     * @param EntityManagerInterface $entityManager
     * @param RouterInterface $router
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository $userRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository

    ) {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request)
    {
        return 'app_ldap' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return User|null|\Symfony\Component\Security\Core\User\User|UserInterface
     * @throws \Exception
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = null;
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('Wrong Csrf token');
        }
        if ($userProvider instanceof LdapUserProvider) {
            $userProvider->setSearchPassword($credentials['password']);
            $ldapUser = $userProvider->loadUserByUsername($credentials['email']);
            if ($ldapUser) {
                $user = $this->getDbUser($ldapUser->getUsername(), $credentials['password']);
            }
        } else {
            throw new \RuntimeException('Wrong type of authenticator');
        }
//        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if ($user === null) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        // For example : return new RedirectResponse($this->router->generate('some_route'));
        return new RedirectResponse($this->router->generate('default'));
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('app_ldap');
    }

    /**
     * @param $credentialsEmail
     * @param $ldapUserName
     * @param $credentialsPassword
     * @return User|User[]
     */
    public function getDbUser($ldapUserName, $credentialsPassword)
    {
        $dbUser = $this->userRepository->findOneBy(['email' => $ldapUserName . '@onyx.com']);
        if ($dbUser && $dbUser instanceof User) {
            return $dbUser;
        }
        $userEntity = new User();
        $userEntity->setEmail($ldapUserName . '@onyx.com');
        $userEntity->setPassword(
            $this->passwordEncoder->encodePassword(
                $userEntity,
                $credentialsPassword
            )
        );
        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
        return $userEntity;
    }
}

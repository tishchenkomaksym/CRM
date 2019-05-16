<?php


namespace App\Calendar\Sdt;


use App\Entity\Sdt;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class UserSdtLinkGenerator implements SdtLinkGeneratorInterface
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getLink(User $currentUser, Sdt $sdt): ?string
    {
        $link = null;
        if ($currentUser->getId() === $sdt->getUser()->getId()) {
            $link = $this->router->generate(
                'sdt_show',
                ['id' => $sdt->getId()],
                UrlGeneratorInterface::ABSOLUTE_PATH
            );
        }
        return $link;
    }
}
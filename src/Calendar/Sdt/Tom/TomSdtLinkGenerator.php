<?php


namespace App\Calendar\Sdt\Tom;


use App\Calendar\Sdt\SdtLinkGeneratorInterface;
use App\Entity\Sdt;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class TomSdtLinkGenerator implements SdtLinkGeneratorInterface
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
        return $this->router->generate(
            'sdt_show',
            ['id' => $sdt->getId()],
            UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }
}
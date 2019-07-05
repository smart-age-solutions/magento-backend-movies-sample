<?php
namespace Sas\Movies\Model\Api;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\ObjectManagerInterface;

final class ClientFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        ObjectManagerInterface $objectManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->objectManager = $objectManager;
        $this->scopeConfig = $scopeConfig;
    }

    public function create(): \Tmdb\Client
    {
        $tokenString = $this->scopeConfig->getValue('sas_movies/api/token');

        $token = $this->objectManager->create(
            \Tmdb\ApiToken::class,
            ['api_token' => $tokenString]
        );

        return $this->objectManager->create(
            \Tmdb\Client::class,
            ['token' => $token]
        );
    }
}

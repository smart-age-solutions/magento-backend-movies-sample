<?php
namespace Sas\Movies\Model\Api;

use Magento\Framework\ObjectManagerInterface;

final class MovieRepositoryFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;
    /**
     * @var ClientFactory
     */
    private $clientFactory;

    public function __construct(
        ObjectManagerInterface $objectManager,
        ClientFactory $clientFactory
    ) {
        $this->objectManager = $objectManager;
        $this->clientFactory = $clientFactory;
    }

    public function create(): \Tmdb\Repository\MovieRepository
    {
        return $this->objectManager->create(
            \Tmdb\Repository\MovieRepository::class,
            ['client' => $this->clientFactory->create()]
        );
    }
}

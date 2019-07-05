<?php
namespace Sas\Movies\Model\Api;

use Magento\Framework\ObjectManagerInterface;

final class SearchRepositoryFactory
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

    public function create(): \Tmdb\Repository\SearchRepository
    {
        return $this->objectManager->create(
            \Tmdb\Repository\SearchRepository::class,
            ['client' => $this->clientFactory->create()]
        );
    }
}

<?php

namespace Peteleco\Movie\Model\ResourceModel;

use Peteleco\Movie\Api\MovieRepositoryInterface;
use Peteleco\Movie\Model\Movie\NotificationStorage;
use Peteleco\Movie\Model\MovieFactory;
use Peteleco\Movie\Model\MovieFavoriteFactory;

class MovieRepository implements MovieRepositoryInterface
{
    /**
     * @var MovieFactory
     */
    protected $movieFactory;
    /**
     * @var MovieFavoriteFactory
     */
    private $movieFavoriteFactory;

    public function __construct(
        MovieFactory $movieFactory,
        MovieFavoriteFactory $movieFavoriteFactory
    ) {
        $this->movieFactory = $movieFactory;
        $this->movieFavoriteFactory = $movieFavoriteFactory;
    }
//    public function __construct(NotificationStorage $notificationStorage)
//    {
//        $this->notificationStorage = $notificationStorage;
//    }

    /**
     * @var NotificationStorage
     */
    private $notificationStorage;

    /**
     * @param int $movieId
     * @return bool
     * @throws \Exception
     */
    public function disableById($movieId)
    {
        $movie = $this->movieFactory->create()->load($movieId);

        $movie->setData([
            'id' => $movieId,
            \Peteleco\Movie\Api\Data\MovieInterface::ENABLED => false,
        ])->save();
        return true;
    }

    /**
     * @param int $movieId
     * @return bool
     * @throws \Exception
     */
    public function enableById($movieId)
    {
        $movie = $this->movieFactory->create()->load($movieId);

        $movie->setData([
            'id' => $movieId,
            \Peteleco\Movie\Api\Data\MovieInterface::ENABLED => true,
        ])->save();
        return true;
    }

    /**
     * @param $movieId
     * @return \Peteleco\Movie\Model\Movie
     */
    public function getById($movieId)
    {
        return $this->movieFactory->create()->load($movieId);
    }
}

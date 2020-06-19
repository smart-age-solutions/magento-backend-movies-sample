<?php

namespace Peteleco\Movie\Model\Movie;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Cache\FrontendInterface;
use Magento\Framework\Serialize\SerializerInterface;

class NotificationStorage
{
    const UPDATE_MOVIE_SESSION = 'update_movie_session';

    /**
     * @var FrontendInterface
     */
    private $cache;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * NotificationStorage constructor.
     * @param FrontendInterface $cache
     * @param SerializerInterface $serializer
     */
    public function __construct(
        FrontendInterface $cache,
        SerializerInterface $serializer = null
    ) {
        $this->cache = $cache;
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(SerializerInterface::class);
    }

    /**
     * Add notification in cache
     *
     * @param string $notificationType
     * @param string $movieId
     * @return void
     */
    public function add($notificationType, $movieId)
    {
        $this->cache->save(
            $this->serializer->serialize([
                'movie_id' => $movieId,
                'notification_type' => $notificationType
            ]),
            $this->getCacheKey($notificationType, $movieId)
        );
    }

    /**
     * Check whether notification is exists in cache
     *
     * @param string $notificationType
     * @param string $movieId
     * @return bool
     */
    public function isExists($notificationType, $movieId)
    {
        return $this->cache->test($this->getCacheKey($notificationType, $movieId));
    }

    /**
     * Remove notification from cache
     *
     * @param string $notificationType
     * @param string $movieId
     * @return void
     */
    public function remove($notificationType, $movieId)
    {
        $this->cache->remove($this->getCacheKey($notificationType, $movieId));
    }

    /**
     * Retrieve cache key
     *
     * @param string $notificationType
     * @param string $movieId
     * @return string
     */
    private function getCacheKey($notificationType, $movieId)
    {
        return 'notification_' . $notificationType . '_' . $movieId;
    }
}

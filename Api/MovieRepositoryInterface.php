<?php
namespace Peteleco\Movie\Api;

interface MovieRepositoryInterface
{
//    /**
//     * Disable Movie.
//     *
//     * @param \Peteleco\Movie\Api\Data\MovieInterface $movie
//     * @return bool true on success
//     * @throws \Magento\Framework\Exception\LocalizedException
//     */
//    public function disable(\Peteleco\Movie\Api\Data\MovieInterface $movie);


    /**
     * Disable movie by Movie ID.
     *
     * @param int $movieId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function disableById($movieId);

    /**
     * Enable movie by Movie ID.
     *
     * @param int $movieId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function enableById($movieId);

    /**
     * @param $movieId
     * @return \Peteleco\Movie\Model\Movie
     */
    public function getById($movieId);

//    /**
//     * @param $movieId
//     * @param $customerId
//     * @return bool
//     */
//    public function wasAddToFavoriteByCustomerId($movieId, $customerId);
}

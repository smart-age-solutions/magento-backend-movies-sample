<?php

namespace Lima\Movie\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface QueueInterface
 * @package Lima\Movie\Api\Data
 */
interface QueueInterface extends ExtensibleDataInterface
{
    /**
     * @return mixed
     */
    public function getImportId();

    /**
     * @param $importId
     * @return mixed
     */
    public function setImportId($importId);

    /**
     * @return mixed
     */
    public function getMovieId();

    /**
     * @param $movieId
     * @return mixed
     */
    public function setMovieId($movieId);

    /**
     * @return mixed
     */
    public function getPrice();

    /**
     * @param $price
     * @return mixed
     */
    public function setPrice($price);

    /**
     * @return mixed
     */
    public function getStock();

    /**
     * @param $stock
     * @return mixed
     */
    public function setStock($stock);

    /**
     * @return mixed
     */
    public function getImage();

    /**
     * @param $image
     * @return mixed
     */
    public function setImage($image);

    /**
     * @return mixed
     */
    public function getTitle();

    /**
     * @param $title
     * @return mixed
     */
    public function setTitle($title);

    /**
     * @return mixed
     */
    public function getOverview();

    /**
     * @param $overview
     * @return mixed
     */
    public function setOverview($overview);

    /**
     * @return mixed
     */
    public function getAdult();

    /**
     * @param $adult
     * @return mixed
     */
    public function setAdult($adult);

    /**
     * @return mixed
     */
    public function getReleaseDate();

    /**
     * @param $releaseDate
     * @return mixed
     */
    public function setReleaseDate($releaseDate);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param $createdAt
     * @return mixed
     */
    public function setCreatedAt($createdAt);

    /**
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * @param $updatedAt
     * @return mixed
     */
    public function setUpdatedAt($updatedAt);

    /**
     * @return mixed
     */
    public function getPending();

    /**
     * @param $pending
     * @return mixed
     */
    public function setPending($pending);

    /**
     * @return mixed
     */
    public function getAttributeSetId();

    /**
     * @param $attributeSetId
     * @return mixed
     */
    public function setAttributeSetId($attributeSetId);

    /**
     * @return mixed
     */
    public function getLanguage();

    /**
     * @param $language
     * @return mixed
     */
    public function setLanguage($language);
}

<?php

namespace Lima\Movie\Model;

use Magento\Framework\Model\AbstractModel;
use Lima\Movie\Api\Data\QueueInterface;

/**
 * Class Queue
 * @package Lima\Movie\Model
 */
class Queue extends AbstractModel implements QueueInterface
{
	protected function _construct()
	{
		$this->_init('Lima\Movie\Model\ResourceModel\Queue');
	}

    /**
     * @return int
     */
    public function getImportId()
    {
        return (int) $this->getData('import_id');
    }

    /**
     * @param int $importId
     * @return Queue
     */
    public function setImportId($importId)
    {
        return $this->setData('import_id', $importId);
    }

    /**
     * @return array|int|mixed|null
     */
    public function getMovieId()
    {
        return $this->getData('movie_id');
    }

    /**
     * @param int $movieId
     * @return Queue
     */
    public function setMovieId($movieId)
    {
         return $this->setData('movie_id', $movieId);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getPrice()
    {
        return $this->getData('price');
    }

    /**
     * @param string $price
     * @return Queue
     */
    public function setPrice($price)
    {
        return $this->setData('price', $price);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getStock()
    {
        return $this->getData('stock');
    }

    /**
     * @param string $stock
     * @return Queue
     */
    public function setStock($stock)
    {
        return $this->setData('stock', $stock);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getImage()
    {
        return $this->getData('image');
    }

    /**
     * @param string $image
     * @return Queue
     */
    public function setImage($image)
    {
        return $this->setData('image', $image);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getTitle()
    {
        return $this->getData('title');
    }

    /**
     * @param $title
     * @return Queue
     */
    public function setTitle($title)
    {
        return $this->setData('title', $title);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getOverview()
    {
        return $this->getData('overview');
    }

    /**
     * @param string $overview
     * @return Queue
     */
    public function setOverview($overview)
    {
        return $this->setData('overview', $overview);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getAdult()
    {
        return $this->getData('adult');
    }

    /**
     * @param string $adult
     * @return Queue
     */
    public function setAdult($adult)
    {
        return $this->setData('adult', $adult);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getReleaseDate()
    {
        return $this->getData('release_date');
    }

    /**
     * @param string $releaseDate
     * @return Queue
     */
    public function setReleaseDate($releaseDate)
    {
        return $this->setData('release_date', $releaseDate);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getCreatedAt()
    {
        return $this->getData('created_at');
    }

    /**
     * @param string $createdAt
     * @return Queue
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData('created_at', $createdAt);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getUpdatedAt()
    {
        return $this->getData('updated_at');
    }

    /**
     * @param string $updatedAt
     * @return Queue
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData('update_at', $updatedAt);
    }

    /**
     * @return array|mixed|string|null
     */
    public function getPending()
    {
        return $this->getData('pending');
    }

    /**
     * @param string $pending
     * @return Queue
     */
    public function setPending($pending)
    {
        return $this->setData('pending', $pending);
    }

    /**
     * @return array|mixed|null
     */
    public function getAttributeSetId()
    {
        return $this->getData('attribute_set_id');
    }

    /**
     * @param $attributeSetId
     * @return Queue
     */
    public function setAttributeSetId($attributeSetId)
    {
        return $this->setData('attribute_set_id', $attributeSetId);
    }

    /**
     * @return array|mixed|null
     */
    public function getLanguage()
    {
        return $this->getData('language');
    }

    /**
     * @param $language
     * @return Queue
     */
    public function setLanguage($language)
    {
        return $this->setData('language', $language);
    }

}

<?php

namespace Peteleco\Movie\Block;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Http\Context as AuthContext;
use Magento\Framework\View\Element\Template;
use Peteleco\Movie\Api\MovieRepositoryInterface;
use Peteleco\Movie\Model\ResourceModel\MovieFavorite\CollectionFactory;

class View extends Template
{
    /**
     * @var MovieRepositoryInterface
     */
    protected $movieRepository;

    protected $movie;

    protected $collectionFactory;

    protected $isMovieAtFavoriteList = false;
    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @var AuthContext
     */
    private $authContext;

    public function __construct(
        Template\Context $context,
        MovieRepositoryInterface $movieRepository,
        CollectionFactory $collectionFactory,
        Session $customerSession,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->movieRepository = $movieRepository;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param bool $isMovieAtFavoriteList
     * @return View
     */
    public function setIsMovieAtFavoriteList($isMovieAtFavoriteList)
    {
        $this->isMovieAtFavoriteList = $isMovieAtFavoriteList;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMovieAtFavoriteList()
    {
        return $this->isMovieAtFavoriteList;
    }

    protected function getMovieData()
    {
        $movieId = ((int)$this->getRequest()->getParam('id')) ?: null;
        return $this->movieRepository->getById($movieId);
    }

    public function wasAddToFavoriteList()
    {
        $customerId = $this->getData('customerId');

        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('store_id', $this->getStoreId());
        $collection->addFieldToFilter('movie_id', $this->getMovieData()->getId());
        $collection->addFieldToFilter('customer_id', $customerId);

        return ($collection->getSize() > 0);
    }

    /**
     * @param mixed $movie
     * @return View
     */
    protected function setMovie($movie)
    {
        $this->movie = $movie;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMovie()
    {
        return $this->movie;
    }

    protected function _prepareLayout()
    {
        $this->setMovie($this->getMovieData());
//        $this->handleFavoriteList();
        $this->pageConfig->getTitle()->set($this->getMovie()->getTitle());
        return parent::_prepareLayout();
    }

    /**
     * Get store identifier
     *
     * @return  int
     */
    protected function getStoreId()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getStoreId();
    }
}

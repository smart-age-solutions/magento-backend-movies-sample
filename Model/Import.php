<?php

namespace Lima\Movie\Model;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Lima\Movie\Service\ImportImageService;
use Lima\Movie\Api\QueueRepositoryInterface;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class Import
 * @package Lima\Movie\Model
 */
class Import
{
	const MOVIE_PREFIX_SKU = 'movie-';

	/**
	 * Product Model Factory
	 *
	 * @var \Magento\Catalog\Model\ProductFactory
	 */
	protected $_productFactory;

	/**
     * Core Date
     *
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_coreDate;

    /**
     * Image Import Service
     *
     * @var \Lima\Movie\Service\ImportImageService
     */
    protected $_importImageService;

    /*
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /*
     * @var \Lima\Movie\Api\QueueRepositoryInterface
     */
    protected $_queueRepositoryInterface;

    /**
     * @var ManagerInterface
     */
    protected $_messageManager;

    /**
     * Import constructor.
     * @param ProductFactory $productFactory
     * @param DateTime $coreDate
     * @param ImportImageService $importImageService
     * @param StoreManagerInterface $storeManager
     * @param QueueRepositoryInterface $queueRepositoryInterface
     * @param ManagerInterface $messageManager
     */
    public function __construct(
		ProductFactory $productFactory,
		DateTime $coreDate,
		ImportImageService $importImageService,
        StoreManagerInterface $storeManager,
        QueueRepositoryInterface $queueRepositoryInterface,
        ManagerInterface $messageManager
    ) {
		$this->_productFactory = $productFactory;
		$this->_coreDate = $coreDate;
		$this->_importImageService = $importImageService;
		$this->_storeManager = $storeManager;
		$this->_queueRepositoryInterface = $queueRepositoryInterface;
		$this->_messageManager = $messageManager;
    }

    /**
     * @param Queue $queueModel
     * @return bool|int|mixed
     * @throws \Exception
     */
	public function item(\Lima\Movie\Model\Queue $queueModel)
	{
		$productModel = $this->_productFactory->create();
		$product = $this->_setProductData($productModel, $queueModel);

		try {
			$product->save();
		} catch (\Magento\Framework\Exception\CouldNotSaveException $e) {
			$this->_messageManager->addErrorMessage(
                __('Could not save product')
            );
            return $this->resultRedirectFactory->create()->setPath('*/*/');
		}

		$queueRepository = $this->_queueRepositoryInterface;
		$queue = $queueRepository->getById($queueModel->getImportId());
		$queue->setData('pending', 0);
		$queue->setData('updated_at', $this->_coreDate->gmtDate());

		try {
			$queueRepository->save($queue);
		} catch (\Magento\Framework\Exception\CouldNotSaveException $e) {
			$this->_messageManager->addErrorMessage(
                __('Could not update queue status')
            );
            return $this->resultRedirectFactory->create()->setPath('*/*/');
		}

		return $product->getId();
	}

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param Queue $queue
     * @return bool|\Magento\Catalog\Model\AbstractModel|\Magento\Catalog\Model\Product
     * @throws \Exception
     */
	protected function _setProductData(\Magento\Catalog\Model\Product $product ,\Lima\Movie\Model\Queue $queue)
	{
		$sku = self::MOVIE_PREFIX_SKU . $queue->getMovieId();

		/* If product already exists, update it */
		$productLoaded = $product->loadByAttribute('sku', $sku);

		if($productLoaded) {
			$product = $productLoaded;
		}

		/* Setting product data from queue */
		$stock = [
			'use_config_manage_stock' => 0,
			'manage_stock' => 1,
			'is_in_stock' => 1,
			'qty' => $queue->getStock()
		];

		$storeId = Store::DEFAULT_STORE_ID;
		$defaultWebsiteId = $this->_storeManager->getDefaultStoreView()->getWebsiteId();

		$product->setName($queue->getTitle())
			->setPrice($queue->getPrice())
			->setDescription($queue->getOverview())
			->setShortDescription($queue->getOverview())
			->setSku($sku)
			->setAttributeSetId($queue->getAttributeSetId())
			->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
			->setWeight(10)
			->setVisibility(4)
			->setTaxClassId(0)
			->setUrlKey($this->_createProductURLKey($queue->getTitle(), $queue->getMovieId()))
			->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
            ->setStockData($stock)
			->setStoreId($storeId)
            ->setWebsiteIds([$defaultWebsiteId])
			->setCreatedAt($this->_coreDate->gmtDate())
            ->setIsMovie(true)
            ->setMovieId($queue->getMovieId())
            ->setLanguage($queue->getMovieLanguage())
            ->setReleaseDate($queue->getMovieReleaseDate())
            ->setAdult($queue->getMovieAdult());

		// Import Image
		$this->_importImageService->execute($product, $queue->getImage(), $visible = false, ['image', 'small_image', 'thumbnail']);

		return $product;
	}

    /**
     * @param $productName
     * @param $movieId
     * @return string|string[]|null
     */
	protected function _createProductURLKey($productName, $movieId)
	{
		return preg_replace('#[^0-9a-z]+#i', '-', $productName . '_' . $movieId);
	}
}

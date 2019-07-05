<?php
namespace Sas\Movies\Model\Movie;

use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

class Import
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var ProductResource
     */
    private $productResource;
    /**
     * @var ProductInterfaceFactory
     */
    private $productFactory;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var Import\Image
     */
    private $image;
    /**
     * @var ManagerInterface
     */
    private $eventManager;

    public function __construct(
        StoreManagerInterface $storeManager,
        ProductResource $productResource,
        ProductInterfaceFactory $productInterfaceFactory,
        ProductRepositoryInterface $productRepository,
        Import\Image $image,
        ManagerInterface $eventManager
    ) {
        $this->storeManager = $storeManager;
        $this->productResource = $productResource;
        $this->productFactory = $productInterfaceFactory;
        $this->productRepository = $productRepository;
        $this->image = $image;
        $this->eventManager = $eventManager;
    }

    public function execute(\Tmdb\Model\Movie $movie)
    {
        try {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $this->productRepository->get($movie->getId());
        } catch (\Magento\Framework\Exception\NoSuchEntityException $noSuchEntityException) {
            $product = $this->productFactory->create();
            $product->setTypeId('movie');
            $product->setSku($movie->getId());
            $product->isObjectNew(true);
            $product->setWebsiteIds([1]);
            $product->setAttributeSetId(4);
            $product->setStockData([
                'use_config_manage_stock' => 0,
                'manage_stock' => 0,
                'is_in_stock' => 1,
                'qty' => 1,
            ]);
            $product->setVisibility(Visibility::VISIBILITY_BOTH);
        }

        $product->setName($movie->getTitle());
        $product->setDescription($movie->getOverview());

        $product = $this->productRepository->save($product);

        $images = array_unique([
            $movie->getPosterImage()->getFilePath(),
            $movie->getImages()->filterBestVotedImage()->getFilePath()
        ]);

        $this->image->import($product, $images);

        /**
         * Flush FPC
         */
        $fpcObject = new class implements IdentityInterface {
            public function getIdentities()
            {
                return \Sas\Movies\Block\Result::$identities;
            }
        };
        $this->eventManager->dispatch('clean_cache_by_tags', ['object' => $fpcObject]);

        return $product;
    }
}

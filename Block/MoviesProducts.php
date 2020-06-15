<?php
namespace Lima\Movie\Block;

/**
 * Class MoviesProducts
 * @package Lima\Movie\Block
 */
class MoviesProducts extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $_imageHelper;

    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $_cartHelper;

    /**
     * @var \Lima\Movie\Helper\Favorite
     */
    protected $_favoriteHelper;

    /**
     * @var \Magento\Catalog\Helper\Data
     */
    protected $_catalogHelper;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;

    /**
     * MoviesProducts constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     * @param \Magento\Catalog\Helper\Data $catalogHelper
     * @param \Lima\Movie\Helper\Favorite $favoriteHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Magento\Catalog\Helper\Data $catalogHelper,
        \Lima\Movie\Helper\Favorite $favoriteHelper,
        array $data = array()
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_imageHelper = $imageHelper;
        $this->_cartHelper = $cartHelper;
        $this->_catalogHelper = $catalogHelper;
        $this->_favoriteHelper = $favoriteHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getCollection()
    {
        $productCollection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')->addAttributeToFilter('is_movie', ['eq' => 1]);

        return ($productCollection->count()) ? $productCollection : false;
    }

    /**
     * @return mixed
     */
    public function getProduct(){
        if(is_null($this->_product)){
            $this->_product = $this->_catalogHelper->getProduct();
        }
        return $this->_product;
    }

    /**
     * @param $product
     * @param string $imageId
     * @return string
     */
    public function getProductImageUrl($product, $imageId = 'product_base_image')
    {
        return $this->_imageHelper->init($product, $imageId)->getUrl();
    }

    /**
     * @param $product
     * @param array $additional
     * @return string
     */
    public function getAddToCartUrl($product, $additional = [])
	{
  	    return $this->_cartHelper->getAddUrl($product, $additional);
	}

	/**
     * @param $product
     * @param array $additional
     * @return string
     */
    public function getAddToWishlistUrl($product, $additional = [])
	{
	    $additional['return_url'] = $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
  	    return $this->_favoriteHelper->getAddUrl($product, $additional);
	}

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param null $priceType
     * @param string $renderZone
     * @param array $arguments
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductPriceHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType = null,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }
        $arguments['zone'] = isset($arguments['zone'])
            ? $arguments['zone']
            : $renderZone;
        $arguments['price_id'] = isset($arguments['price_id'])
            ? $arguments['price_id']
            : 'old-price-' . $product->getId() . '-' . $priceType;
        $arguments['include_container'] = isset($arguments['include_container'])
            ? $arguments['include_container']
            : true;
        $arguments['display_minimal_price'] = isset($arguments['display_minimal_price'])
            ? $arguments['display_minimal_price']
            : true;

        $priceRender = $this->getLayout()->getBlock('product.price.render.default');

        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
                $product,
                $arguments
            );
        }
        return $price;
    }

    /**
     * @return bool|int|null
     */
    public function getCacheLifetime()
    {
        return null;
    }
}

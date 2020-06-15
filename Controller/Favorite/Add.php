<?php

namespace Lima\Movie\Controller\Favorite;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Add extends \Magento\Framework\App\Action\Action implements HttpPostActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customer;

    /**
     * @var \Lima\Movie\Model\FavoriteFactory
     */
    protected $favoriteFactory;

    /**
     * Add constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Customer\Model\Session $customer
     * @param \Lima\Movie\Model\FavoriteFactory $favoriteFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Customer\Model\Session $customer,
        \Lima\Movie\Model\FavoriteFactory $favoriteFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
        $this->formKeyValidator = $formKeyValidator;
        $this->productRepository = $productRepository;
        $this->customer = $customer ;
        $this->favoriteFactory = $favoriteFactory;
        parent::__construct($context);
    }

    /**
     * Initialize product instance from request data
     *
     * @return \Magento\Catalog\Model\Product|false
     */
    protected function _initProduct()
    {
        $productId = (int)$this->getRequest()->getParam('product');
        if ($productId) {
            $storeId = $this->_objectManager->get(
                \Magento\Store\Model\StoreManagerInterface::class
            )->getStore()->getId();
            try {
                return $this->productRepository->getById($productId, false, $storeId);
            } catch (NoSuchEntityException $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        /*
         *  Validate Request
         */
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(
                __('Your session has expired')
            );
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        /*
         *  Validate Current Customer
         */
        if(!$customerId = $this->_getCustomerId()) {
            $this->messageManager->addErrorMessage(
                __('You must login or register to add movies as your favorite')
            );
            return $this->resultRedirectFactory->create()->setUrl('/customer/account/');
        }

        $params = $this->getRequest()->getParams();

        if(isset($params['product']) && $customerId) {

            $favoriteFactory = $this->favoriteFactory->create();
            $favoriteCollection = $favoriteFactory->getCollection()
                                                        ->addFieldToFilter('customer_id', ['eq' => $customerId])
                                                        ->addFieldToFilter('product_id', ['eq' => $params['product']]);

            if($favoriteCollection->count()) {
                $this->messageManager->addErrorMessage(
                    __('You already added this movie as your favorite')
                );
                return $this->_goBack($params['return_url']);
            } else {
                $favoriteFactory
                    ->setCustomerId($customerId)
                    ->setProductId($params['product']);

                try {
                    $favoriteFactory->save();
                } catch (NoSuchEntityException $e) {
                    $this->messageManager->addErrorMessage(
                        __('An error happened, the movie could not be added as a favorite')
                    );
                    return $this->_goBack($params['return_url']);
                }
            }

            $product = $this->_initProduct();
            $message = __('You added %1 as your favorite movie', $product->getName());
            $this->messageManager->addSuccessMessage($message);
        } else {
            $this->messageManager->addErrorMessage(
                __('An error happened, the movie could not be added as a favorite')
            );
            return $this->_goBack($params['return_url']);
        }

        return $this->_goBack($params['return_url']);
    }

    /**
     * @return bool|int|null
     */
    protected function _getCustomerId()
    {
        if($this->customer->isLoggedIn() && $this->customer->getId()) {
            return $this->customer->getId();
        }
        return false;
    }

    /**
     * @param null $backUrl
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function _goBack($backUrl = null)
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $resultRedirect->setUrl($backUrl);

        return $resultRedirect;
    }
}

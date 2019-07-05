<?php
namespace Sas\Movies\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class Favorite extends Action
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct($context);
        $this->productRepository = $productRepository;
    }

    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $requestParams = $this->getRequest()->getParams();

        $productId = isset($requestParams['movie_id']) ? (int)$requestParams['movie_id'] : null;
        if (!$productId) {
            $resultRedirect->setPath('*/');
            return $resultRedirect;
        }

        try {
            $product = $this->productRepository->getById($productId);
            $favoriteCount = $product->getData('favorite') ?? 0;
            $product->setData('favorite', $favoriteCount + 1);
            $this->productRepository->save($product);
            $this->_eventManager->dispatch('clean_cache_by_tags', ['object' => $product]);
        } catch (NoSuchEntityException $noSuchEntityException) {
            $resultRedirect->setPath('*/');
            return $resultRedirect;
        }

        $this->_redirect($this->_redirect->getRefererUrl());
    }
}

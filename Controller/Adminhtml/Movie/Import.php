<?php
namespace Sas\Movies\Controller\Adminhtml\Movie;

use Magento\Catalog\Controller\Adminhtml\Product;

class Import extends \Magento\Catalog\Controller\Adminhtml\Product
{
    /**
     * @var \Sas\Movies\Model\Movie\Import
     */
    private $productImport;
    /**
     * @var \Sas\Movies\Model\Api\MovieRepositoryFactory
     */
    private $movieRepositoryFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Product\Builder $productBuilder,
        \Sas\Movies\Model\Movie\Import $productImport,
        \Sas\Movies\Model\Api\MovieRepositoryFactory $movieRepositoryFactory
    ) {
        parent::__construct($context, $productBuilder);
        $this->productImport = $productImport;
        $this->movieRepositoryFactory = $movieRepositoryFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $movieRepository = $this->movieRepositoryFactory->create();
        $movie = $movieRepository->load($this->getRequest()->getParam('id'));
        $product = $this->productImport->execute($movie);

        return $this->_redirect('catalog/product/edit', ['id' => $product->getId()]);
    }
}

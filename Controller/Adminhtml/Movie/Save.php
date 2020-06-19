<?php

namespace Peteleco\Movie\Controller\Adminhtml\Movie;

use Peteleco\Movie\Model\MovieFactory;

class Save extends \Magento\Backend\App\Action
{
    private $movieFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        MovieFactory $movieFactory
    )
    {
        $this->movieFactory = $movieFactory;
        parent::__construct($context);
    }

    /**
     * Get store identifier
     *
     * @return  int
     */
    private function getStoreId()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        return $storeManager->getStore()->getStoreId();
    }

    public function execute()
    {
        $this->movieFactory->create()
            ->setData($this->getData())
            ->save();
        return $this->resultRedirectFactory->create()->setPath('movie/index/index');
    }

    /**
     *
     * @return mixed
     */
    protected function getData()
    {
        $data = $this->getRequest()->getPostValue()['general'];
        $data['store_id'] = $this->getStoreId();
        return $data;
    }
}

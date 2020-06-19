<?php

namespace Peteleco\Movie\Controller\Movie;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class View extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        /* @var \Peteleco\Movie\Block\View $block */
        $block = $page->getLayout()->getBlock('movie_movie_view');

        $block->setData('customerId', $this->getCustomerId());
        return $page;
    }

    protected function getCustomerId()
    {
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $om->get('Magento\Customer\Model\Session');
        return $customerSession->getCustomer()->getId();
    }
}

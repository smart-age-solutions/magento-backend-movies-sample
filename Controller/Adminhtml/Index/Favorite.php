<?php

namespace Peteleco\Movie\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

class Favorite extends \Magento\Backend\App\Action
{
        public function execute()
        {
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        }
}

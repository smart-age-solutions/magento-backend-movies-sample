<?php

namespace Juniorfreitas\Movie\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
//use Mageplaza\HelloWorld\Helper\Data;

class Collect extends Action
{

    protected $resultJsonFactory;

    protected $helper;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * Collect relations data
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
//        try {
//            $this->_getSyncSingleton()->collectRelations();
//        } catch (\Exception $e) {
//            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
//        }
//
//        $lastCollectTime = $this->helper->getLastCollectTime();
//        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultJsonFactory->create();

        return $result->setData(['success' => true, 'time' => '']);
    }


    protected function _getSyncSingleton()
    {
        //return $this->_objectManager->get('Mageplaza\HelloWorld\Model\Relation');
    }
}
?>

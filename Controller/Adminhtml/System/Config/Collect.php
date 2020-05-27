<?php

namespace Juniorfreitas\Movie\Controller\Adminhtml\System\Config;

use Juniorfreitas\Movie\Model\ApiRequest\Http\Client;
use Juniorfreitas\Movie\Model\ApiRequest\Rest\Request;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Collect extends Action
{

    protected $resultJsonFactory;

    protected $helper;

    protected $client;

    protected $request;

    const URI = 'https://api.themoviedb.org/4/list/1';

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Request $request,
        Client $client
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->request = $request;
        $this->client = $client;

        parent::__construct($context);
    }

    /**
     * Collect relations data
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $this->request->setUri(self::URI)
            ->setParams(['api_key'=> '6772e02b42001d7856a928e31790edf7']);
        //$data = [];
        try {
            $data = $this->client->requestGetCurl($this->request);
//            $this->_getSyncSingleton()->collectRelations();
        } catch (\Exception $e) {
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
        }

//        $lastCollectTime = $this->helper->getLastCollectTime();

        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultJsonFactory->create();

        return $result->setData(['success' => true, 'time' => $data]);
    }


    protected function _getSyncSingleton()
    {
        //return $this->_objectManager->get('Mageplaza\HelloWorld\Model\Relation');
    }
}
?>

<?php

namespace Juniorfreitas\Movie\Controller\Adminhtml\System\Config;

use Juniorfreitas\Movie\Model\ApiRequest\Http\Client;
use Juniorfreitas\Movie\Model\ApiRequest\Rest\Request;
use Juniorfreitas\Movie\Model\Config;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Collect extends Action
{

    protected $resultJsonFactory;

    protected $helper;

    protected $client;

    protected $request;

    protected $model;

    const URI = 'https://api.themoviedb.org/4/list/1';

    public function __construct(
        \Magento\Catalog\Model\ProductFactory $productFactory,
        Context $context,
        JsonFactory $resultJsonFactory,
        Request $request,
        Client $client,
        Config $model
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->request = $request;
        $this->client = $client;
        $this->model = $model;
        $this->_product = $productFactory;

        parent::__construct($context);
    }

    /**
     * Collect relations data
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $this->request->setUri(self::URI)
            ->setParams(['api_key'=> $this->model->getApiKey()]);

        try {
            $movies = $this->client->requestGetCurl($this->request);

            $this->saveOnMagento($movies);

            return $result->setData(['success' => true, 'msg' => 'Movies Importados']);

        } catch (\Exception $e) {
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            return $result->setStatusHeader(500)->setData(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    private function saveOnMagento($movies)
    {
        $moviesToArray = json_decode($movies);

        foreach ($moviesToArray->results as $movie) {
            $_product = $this->_product->create();

            $_product->setName($movie->original_title);
            $_product->setTypeId('simple');
            $_product->setAttributeSetId(4);
            $_product->setSku((string)$movie->id);

            $_product->save();
        }

        return $movies;
    }

}
?>

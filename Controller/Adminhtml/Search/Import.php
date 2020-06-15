<?php

namespace Lima\Movie\Controller\Adminhtml\Search;

use Lima\Movie\Model\QueueFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Class Import
 * @package Lima\Movie\Controller\Adminhtml\Search
 */
class Import extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;
    protected $jsonHelper;
    protected $_queueFactory;

    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @var ManagerInterface
     */
    protected $_messageManager;

    /**
     * Import constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Psr\Log\LoggerInterface $logger
     * @param RequestInterface $request
     * @param ManagerInterface $messageManager
     * @param QueueFactory $queueFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Psr\Log\LoggerInterface $logger,
        RequestInterface $request,
        ManagerInterface $messageManager,
        QueueFactory $queueFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
        $this->_request = $request;
        $this->_messageManager = $messageManager;
        $this->_queueFactory = $queueFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $result = false;

        if ($this->getRequest()->isAjax())
        {
            $items = $this->getRequest()->getParam('items');

            $rowsAffected = 0;

            foreach ($items as $key => $item) {
                $queue = $this->_queueFactory->create();
                try {
                    $queue->setData((array) $item);
                    $queue->setPending(1);
                    $queue->setMovieId($item['id']);
                    $queue->save();
                    $rowsAffected++;
                } catch (\Magento\Framework\Exception\CouldNotSaveException $e) {
                    $this->_messageManager->addErrorMessage(
                        __('Could not import the movie')
                    );
                    return $this->resultRedirectFactory->create()->setPath('*/*/');
                }
            }

            $this->_messageManager->addSuccess('form submitted succesfully.');
        }

        try {
            return $this->jsonResponse($rowsAffected);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }
    }

    /**
     * @param string $response
     * @return mixed
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}

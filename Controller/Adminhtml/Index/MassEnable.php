<?php

namespace Peteleco\Movie\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Ui\Component\MassAction\Filter;
use Peteleco\Movie\Api\MovieRepositoryInterface;
use Peteleco\Movie\Model\ResourceModel\Movie\CollectionFactory;

/**
 * Class MassDelete
 */
class MassEnable extends AbMassAction implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Peteleco_Movie::enable';

    /**
     * @var string
     */
    protected $redirectUrl = '*/*/index';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var MovieRepositoryInterface
     */
    protected $movieRepository;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param MovieRepositoryInterface $movieRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        MovieRepositoryInterface $movieRepository
    ) {
        parent::__construct($context, $filter, $collectionFactory);
        $this->movieRepository = $movieRepository;
    }

    protected function massAction(AbstractCollection $collection)
    {
        $moviesEnabled = 0;
        foreach ($collection->getAllIds() as $movieId) {
            $this->movieRepository->enableById($movieId);
            $moviesEnabled++;
        }

        if ($moviesEnabled) {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) were disabled.', $moviesEnabled));
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath($this->getComponentRefererUrl());

        return $resultRedirect;
    }
}

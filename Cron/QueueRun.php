<?php

namespace Lima\Movie\Cron;

use \Psr\Log\LoggerInterface;
use \Lima\Movie\Model\ResourceModel\Queue\CollectionFactory;
use \Lima\Movie\Model\Import;

/**
 * Class QueueRun
 * @package Lima\Movie\Cron
 */
class QueueRun
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Import
     */
    protected $import;

    /**
     * QueueRun constructor.
     * @param LoggerInterface $logger
     * @param CollectionFactory $collectionFactory
     * @param Import $import
     */
    public function __construct(
        LoggerInterface $logger,
        CollectionFactory $collectionFactory,
        Import $import
    ) {
        $this->logger = $logger;
        $this->collectionFactory = $collectionFactory;
        $this->import = $import;
    }

    public function execute()
    {
        $collection = $this->_getCollection();
        $this->logger->info('Importing Pending Movies');

        if($collection){
            foreach ($collection as $key => $item) {
                $this->logger->info('Importing Movie: ' . $item->getImportId());
                $this->import->item($item);
            }
        }
    }

    private function _getCollection()
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('pending', ['eq' => 1])
                ->setPageSize(10)
                ->setCurPage(1)
                ->load();

        return $collection->count() ? $collection : false;
    }
}

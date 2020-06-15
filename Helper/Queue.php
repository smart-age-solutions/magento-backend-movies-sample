<?php
namespace Lima\Movie\Helper;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use Lima\Movie\Model\QueueFactory;

/**
 * Class Queue
 * @package Lima\Movie\Helper
 */
class Queue extends AbstractData
{
    /**
     * @var QueueFactory
     */
	protected $_queueFactory;

    /**
     * @var ScopeConfigInterface
     */
	protected $_scopeConfig;

    /**
     * Queue constructor.
     * @param QueueFactory $queueFactory
     * @param ScopeConfigInterface $scopeConfig
     */
	public function __construct(
		QueueFactory $queueFactory,
		ScopeConfigInterface $scopeConfig
	){
		$this->_scopeConfig = $scopeConfig;
		$this->_queueFactory = $queueFactory;
	}

    /**
     * @param array $item
     * @return bool|int
     * @throws \Exception
     */
	public function addItem(array $item)
	{
		$queue = $this->_queueFactory->create();

		$queueItem = $this->extractDataToQueue($item);

		if($queueItem) {
			$queue->setData($queueItem);
			$queue->save();

			return $queue->getImportId();
		} else {
			return false;
		}
	}

    /**
     * @param array $item
     * @return array
     */
	public function extractDataToQueue(array $item)
	{
		$queueItem = [];

		if(isset($item['id'])) {
			$queueItem = [
				'movie_id' => $item['id'],
				'price' => isset($item['price']) ? $item['price'] : $this->getDefaultPrice(),
				'stock' => isset($item['stock']) ? $item['stock'] : $this->getDefaultStock(),
				'image' => isset($item['poster_path']) ? $this->getMoviePoster($item['poster_path']) : null,
				'title' => $item['title'],
				'overview' => $item['overview'],
				'release_date' => $item['release_date'],
				'adult' => $item['adult'],
				'pending' => 1,
			];
		}

		return $queueItem;
	}
}

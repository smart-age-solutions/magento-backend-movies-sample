<?php

namespace Lima\Movie\Model;

/**
 * Class QueueManagement
 * @package Lima\Movie\Model
 */
class QueueManagement
{
    /**
     * @var QueueFactory
     */
	protected $_queueFactory;

    /**
     * @var \Lima\Movie\Helper\Queue
     */
	protected $_helper;

    /**
     * QueueManagement constructor.
     * @param QueueFactory $queueFactory
     * @param \Lima\Movie\Helper\Queue $helper
     */
	public function __construct(
		\Lima\Movie\Model\QueueFactory $queueFactory,
		\Lima\Movie\Helper\Queue $helper
	) {
		$this->_queueFactory = $queueFactory;
		$this->_helper = $helper;
	}

    /**
     * @param $items
     * @return array
     * @throws \Exception
     */
	public function addItems($items)
	{
		$return = [];

		foreach ($items as $key => $item) {
			$queueId = $this->_helper->addItem($item);

			if($queueId) {
				$return['success'][] = $queueId;
			} else {
				$return['fail'] += 1;
			}
		}

		return $return;
	}

    /**
     * @return array
     */
	public function getItems()
	{
		$collection = $this->_queueFactory->create()->getCollection();

		$return = $collection->getData();

		return $return;
	}

}

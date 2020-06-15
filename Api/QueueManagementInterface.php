<?php

namespace Lima\Movie\Api;

/**
 * Interface QueueManagementInterface
 * @package Lima\Movie\Api
 */
interface QueueManagementInterface
{
	/**
	 * POST for Queue api
	 * @param mixed $items
	 * @return string
	 */
	public function addItems($items);

	/**
	 * GET for Queue api
	 * @return string
	 */
	public function getItems();

}

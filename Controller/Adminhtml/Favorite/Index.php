<?php

namespace Lima\Movie\Controller\Adminhtml\Favorite;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package Lima\Movie\Controller\Adminhtml\FavoriteList
 */
class Index extends Action
{
	const MENU_ID = 'Lima_Movie::favorite_list';

    /**
     * @var PageFactory
     */
	protected $resultPageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
	public function __construct(
		Context $context,
		PageFactory $resultPageFactory
	) {
		parent::__construct($context);

		$this->resultPageFactory = $resultPageFactory;
	}

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|Page
     */
	public function execute()
	{
		$resultPage = $this->resultPageFactory->create();
		$resultPage->setActiveMenu(static::MENU_ID);
		$resultPage->getConfig()->getTitle()->prepend(__('Favorite List'));

		return $resultPage;
	}
}

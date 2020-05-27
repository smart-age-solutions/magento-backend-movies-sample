<?php


namespace Juniorfreitas\Movie\Controller\Adminhtml;

class Credentials extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

        $resultPage->setActiveMenu('Juniorfreitas_Movie::credentials');

        $resultPage->getConfig()->getTitle()->prepend(__('Credenciais'));

        return $resultPage;
    }

    public function index()
    {
        echo "<pre>";
        var_dump("dsfdsf");
        echo "</pre>";
        die();
    }
}

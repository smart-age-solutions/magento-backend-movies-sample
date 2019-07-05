<?php
namespace Sas\Movies\Helper;

use Magento\Search\Model\QueryFactory;

class Data extends \Magento\Search\Helper\Data
{
    public function getResultUrl($query = null)
    {
        if ($this->_getRequest()->getModuleName() == 'movies') {
            return $this->_getUrl(
                'movies',
                ['_query' => [QueryFactory::QUERY_VAR_NAME => $query], '_secure' => $this->_request->isSecure()]
            );
        }

        return parent::getResultUrl($query);
    }
}

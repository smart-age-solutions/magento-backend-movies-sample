<?php
namespace Lima\Movie\Block\Adminhtml;

use Magento\Framework\View\Element\Template;
use Lima\Movie\Helper\AbstractData;

/**
 * Class Search
 * @package Lima\Movie\Block\Adminhtml
 */
class Search extends \Magento\Framework\View\Element\Template
{
    /**
     * @var AbstractData
     */
    protected $_helper;

    /**
     * Search constructor.
     * @param Template\Context $context
     * @param AbstractData $helper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        AbstractData $helper,
        array $data = array()
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;

        $this->setData('default_values', [
            'price' => $this->_helper->getDefaultPrice(),
            'stock' => $this->_helper->getDefaultStock(),
        ]);

        $this->setData('default_search', [
            'adult' => $this->_helper->getIncludeAdult(),
            'language' => $this->_helper->getLanguage(),
        ]);
    }
}

<?php
namespace Sas\Movies\Block;

use Magento\Framework\DataObject\IdentityInterface;

class Result extends \Magento\CatalogSearch\Block\Result implements IdentityInterface
{
    public static $identities = [
        'movies_fpc_tag',
    ];

    public function getSearchQueryText()
    {
        $query = $this->catalogSearchData->getEscapedQueryText();

        if (strlen($query)) {
            return __("Search results for: '%1'", $this->catalogSearchData->getEscapedQueryText());
        }

        return __('Movies');
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return self::$identities;
    }
}

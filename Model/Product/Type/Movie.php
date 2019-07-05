<?php
namespace Sas\Movies\Model\Product\Type;

class Movie extends \Magento\Catalog\Model\Product\Type\Virtual
{
    public function isSalable($product)
    {
        return false;
    }
}

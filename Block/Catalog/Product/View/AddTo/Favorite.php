<?php
namespace Sas\Movies\Block\Catalog\Product\View\AddTo;

class Favorite extends \Magento\Catalog\Block\Product\View
{
    public function getFavoriteUrl()
    {
        return $this->getUrl('movies/index/favorite', ['movie_id' => $this->getProduct()->getId()]);
    }

    public function getFavoriteCount()
    {
        return (int) $this->getProduct()->getFavorite();
    }
}

<?php
namespace Sas\Movies\Ui\DataProvider\Movies;

use Magento\Catalog\Model\AttributeHandler;
use Magento\Framework\App\RequestInterface;
use Sas\Movies\Model\Api\SearchRepositoryFactory;
use Tmdb\Model\Search\SearchQuery\MovieSearchQueryFactory;

class Listing extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var MovieSearchQueryFactory
     */
    private $movieSearchQueryFactory;
    /**
     * @var string
     */
    private $query = '*';

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        SearchRepositoryFactory $collectionFactory,
        MovieSearchQueryFactory $movieSearchQueryFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->request = $request;
        $this->collection = $collectionFactory->create();
        $this->movieSearchQueryFactory = $movieSearchQueryFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        /** @var \Tmdb\Model\Search\SearchQuery\MovieSearchQuery $searchQuery */
        $searchQuery = $this->movieSearchQueryFactory->create();

        $page = $this->request->getParam('paging', ['current' => 1]);
        $searchQuery->page($page['current']);
        $movies = $this->collection->searchMovie($this->query, $searchQuery);

        $items = [];
        foreach ($movies as $movie) {
            $items[] = [
                'id' => $movie->getId(),
                'title' => $movie->getTitle(),
                'thumbnail' => \Sas\Movies\Model\Movie\Import\Image::URL_PREFIX . $movie->getPosterPath()
            ];
        }

        return [
            'totalRecords' => $movies->getTotalResults(),
            'items' => $items
        ];
    }

    public function setLimit($offset, $size)
    {
        //Stub method
    }

    public function addOrder($field, $direction)
    {
        //Stub method
    }

    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if ($filter->getField() == 'title') {
            $this->query = $filter->getValue();
        }
    }

    public function addField($field, $alias = null)
    {
        //Stub method
    }
}

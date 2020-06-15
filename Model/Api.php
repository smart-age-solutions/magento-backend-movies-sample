<?php
namespace Lima\Movie\Model;

use Magento\Framework\HTTP\Client\Curl;
use Lima\Movie\Helper\Api as ApiHelper;

/**
 * Class Api
 * @package Lima\Movie\Model
 */
class Api
{
    const ENDPOINT_SEARCH_MOVIE = 'search/movie';

    /**
     * @var Curl
     */
    protected $_curl;

    /**
     * @var ApiHelper
     */
    protected $_helper;

    /**
     * Api constructor.
     * @param Curl $curl
     * @param ApiHelper $helper
     */
	public function __construct(
        Curl $curl,
        ApiHelper $helper
    ) {
		$this->_curl = $curl;
        $this->_helper = $helper;
    }

    /**
     * https://developers.themoviedb.org/3/search/search-movies
     * @param array $filter
     * @return mixed
     */
    public function searchMovie(array $filter)
    {
        $url =  $this->_helper->buildCall($filter, self::ENDPOINT_SEARCH_MOVIE);

        $this->_curl->get($url);

        $response = json_decode($this->_curl->getBody(), true);

        if(!empty($response['results'])) {
            foreach($response['results'] as $key => $RowData){

                if($RowData['poster_path']) {
                    $response['results'][$key]['poster_path'] = $this->_helper->getMoviePoster($RowData['poster_path']);
                }

                /* Setting default values */
               $response['results'][$key]['price'] = $this->_helper->getDefaultPrice();
               $response['results'][$key]['stock'] = $this->_helper->getDefaultStock();
               $response['results'][$key]['attribute_set_id'] = $this->_helper->getAttributeSetId();
            }
        }

        return $response;
    }

}

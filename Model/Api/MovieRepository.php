<?php
namespace Sas\Movies\Model\Api;

use Sas\Movies\Api\MovieRepositoryInterface;

class MovieRepository implements MovieRepositoryInterface
{
    /**
     * @var MovieFactory
     */
    private $movieFactory;

    public function __construct(MovieFactory $movieFactory)
    {
        $this->movieFactory = $movieFactory;
    }

    /**
     * Add/Edit a movie
     *
     * @param \Sas\Movies\Api\Data\MovieInterface $movie
     * @return \Sas\Movies\Api\Data\MovieInterface
     */
    public function save(\Sas\Movies\Api\Data\MovieInterface $movie): \Sas\Movies\Api\Data\MovieInterface
    {
        // TODO: Implement save() method.
    }

    /**
     * Get a movie by ID
     *
     * @param int $movieId
     * @return \Sas\Movies\Api\Data\MovieInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function get(int $movieId): \Sas\Movies\Api\Data\MovieInterface
    {
        $token  = new \Tmdb\ApiToken('621a6a79ca8acb692da94ef1d8424365');
        $client = new \Tmdb\Client($token);
        $movieData = $client->getMoviesApi()->getMovie($movieId);

        $movie = $this->movieFactory->create();
        $movie->setData($movieData);

        return $movie;
    }

    /**
     * Delete a movie from the catalog
     *
     * @param \Sas\Movies\Api\Data\MovieInterface $movie
     * @return bool
     */
    public function delete(\Sas\Movies\Api\Data\MovieInterface $movie): bool
    {
        // TODO: Implement delete() method.
    }

    /**
     * Get movie list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Sas\Movies\Api\Data\MovieInterface[]
     */
    public function getList(\Sas\Movies\Api\Data\MovieInterface $searchCriteria): array
    {
        // TODO: Implement getList() method.
    }
}

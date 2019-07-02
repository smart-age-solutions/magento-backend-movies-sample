<?php
namespace Sas\Movies\Api;

/**
 * @api
 */
interface MovieRepositoryInterface
{
    /**
     * Add/Edit a movie
     *
     * @param \Sas\Movies\Api\Data\MovieInterface $movie
     * @return \Sas\Movies\Api\Data\MovieInterface
     */
    public function save(\Sas\Movies\Api\Data\MovieInterface $movie): \Sas\Movies\Api\Data\MovieInterface;

    /**
     * Get a movie by ID
     *
     * @param int $movieId
     * @return \Sas\Movies\Api\Data\MovieInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function get(int $movieId): \Sas\Movies\Api\Data\MovieInterface;

    /**
     * Delete a movie from the catalog
     *
     * @param \Sas\Movies\Api\Data\MovieInterface $movie
     * @return bool
     */
    public function delete(\Sas\Movies\Api\Data\MovieInterface $movie): bool;

    /**
     * Get movie list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Sas\Movies\Api\Data\MovieInterface[]
     */
    public function getList(\Sas\Movies\Api\Data\MovieInterface $searchCriteria): array;
}

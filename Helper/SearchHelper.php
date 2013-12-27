<?php
/**
 * Created by PhpStorm.
 * User: Gabriele Perego
 * Date: 07/12/13
 * Time: 13:53
 */

namespace Snce\UtilityBundle\Helper;

use eZ\Publish\API\Repository\Repository,
    eZ\Publish\API\Repository\Values\Content\Query,
    eZ\Publish\API\Repository\Values\Content\Query\Criterion;

/**
 * Helper class to search inside an eZ Publish repository.
 */
class SearchHelper
{
    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    private $repository;

    /**
     * @var array
     */
    private $excludedContentType;

    public function __construct( Repository $repository, $excludedContentType )
    {
        $this->repository = $repository;
        $this->excludedContentType = $excludedContentType;
    }

    /**
     * Return full text search results
     *
     * @param string $textQuery
     * @param array $contentType
     * @param int $limit
     * @param int $offset
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Search\SearchResult
     */
    public function fullTextResults( $textQuery = '', $contentType = null, $limit = 10, $offset = 0 )
    {
        $searchService = $this->repository->getSearchService();
        $locationService = $this->repository->getLocationService();

        $query = new Query();
        $criterions[] = new Criterion\Subtree( $locationService->loadLocation( 2 )->pathString );
        $criterions[] = new Criterion\FullText( $textQuery );
        $criterions[] = new Criterion\LogicalNot( new Criterion\ContentTypeIdentifier( $this->excludedContentType ) );

        if ( $contentType )
        {
            $criterions[] = new Criterion\ContentTypeIdentifier( $contentType );
        }

        $query->criterion = new Criterion\LogicalAnd( $criterions );

        $query->limit = $limit;
        $query->offset = $offset;

        $results = $searchService->findContent( $query  );

        return $results;
    }
}

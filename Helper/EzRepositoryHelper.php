<?php
/**
 * Created by PhpStorm.
 * User: Gabriele Perego
 * Date: 04/01/14
 * Time: 10:29
 */

namespace Snce\UtilityBundle\Helper;

use eZ\Publish\API\Repository\Repository,
    RuntimeException;

/**
 * Helper class to manipulate an eZ Publish repository.
 */
class EzRepositoryHelper {
    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    private $repository;

    public function __construct( Repository $repository )
    {
        $this->repository = $repository;
    }

    /**
     * Return LocationService object
     *
     * @return \eZ\Publish\API\Repository\LocationService
     */
    private function getContentService( )
    {
        return $this->repository->getContentService();
    }

    /**
     * Return LocationService object
     *
     * @return \eZ\Publish\API\Repository\LocationService
     */
    private function getLocationService( )
    {
        return $this->repository->getLocationService();
    }

    /**
     * Return LocationService object
     *
     * @param int $locationId
     *
     * @return \eZ\Publish\API\Repository\LocationService
     */
    private function getContentIdFromLocationId( $locationId )
    {

        return $this->getLocationService()->loadLocation( $locationId )->getContentInfo()->{'id'};
    }

    /**
     * Return MainLocation object from a ContentId
     *
     * @param int $contentId
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Location
     */
    private function getMainLocationFromContentId( $contentId )
    {
        $childContent = $this->getContentFromContentId( $contentId );
        $mainLocationId = $childContent->getVersionInfo()->getContentInfo()->mainLocationId;
        return $this->getLocationService()->loadLocation( $mainLocationId );
    }

    /**
     * Return content object
     *
     * @param int $contentId
     *
     * @return \eZ\Publish\Core\Repository\Values\Content\Content
     */
    public function getContentFromContentId( $contentId )
    {
        $contentService = $this->getContentService();
        return $contentService->loadContent( $contentId );
    }

    /**
     * Return content object
     *
     * @param int $locationId
     *
     * @return \eZ\Publish\Core\Repository\Values\Content\Content
     */
    public function getContentFromLocationId( $locationId )
    {
        $contentId = $this->getContentIdFromLocationId( $locationId );
        return $this->getContentFromContentId( $contentId );
    }

    /**
     * Return content object
     *
     * @param int $childContentId
     *
     * @return \eZ\Publish\Core\Repository\Values\Content\Content
     */
    public function getParentContentFromChildContentId( $childContentId )
    {
        $mainLocation = $this->getMainLocationFromContentId( $childContentId );
        return $this->getContentFromLocationId( $mainLocation->parentLocationId );
    }
} 

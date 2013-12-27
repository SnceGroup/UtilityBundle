<?php
/**
 * Created by PhpStorm.
 * User: Gabriele Perego
 * Date: 07/12/13
 * Time: 13:53
 */

namespace Snce\UtilityBundle\Twig;

use eZ\Publish\API\Repository\Repository;
use Twig_Extension;
use Twig_SimpleFunction;
use RuntimeException;

class SnceTwigExtension extends Twig_Extension
{

    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    private $repository;

    public function __construct( Repository $repository )
    {
        $this->repository = $repository;
    }

    /**
     * Returns functions objects.
     *
     * @return array Function objects
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'snce_utility_content',
                array(
                    $this,
                    'content'
                ),
                array(
                    'contentId' => 2
                )
            ),
            new \Twig_SimpleFunction(
                'snce_utility_content_from_locationId',
                array(
                    $this,
                    'contentFromLocationId'
                ),
                array(
                    'parentLocationId' => 2
                )
            ),
            new \Twig_SimpleFunction(
                'snce_utility_parent_content',
                array(
                    $this,
                    'parentContent'
                ),
                array(
                    'childContentId' => 2
                )
            )
        );
    }

    /**
     * Return content object
     *
     * @param string $contentId
     *
     * @throws \RuntimeException
     *
     * @return \eZ\Publish\Core\Repository\Values\Content\Content
     */
    public function content( $contentId )
    {
        if ( !is_int( (int)$contentId ) )
        {
            throw new RuntimeException( '$contentId must be an integer' );
        }

        $contentService = $this->repository->getContentService();

        $content = $contentService->loadContent( (int)$contentId );
        return $content;
    }

    /**
     * Return a content object from a location id
     *
     * @param integer $locationId
     *
     * @throws \RuntimeException
     *
     * @return \eZ\Publish\Core\Repository\Values\Content\Content
     */
    public function contentFromLocationId( $locationId )
    {
        if ( !is_int( $locationId ) )
        {
            throw new RuntimeException( '$locationId must be an integer' );
        }

        $locationService = $this->repository->getLocationService();
        $contentService = $this->repository->getContentService();

        $content = $contentService->loadContent( $locationService->loadLocation( $locationId )->{'contentInfo'}->{'id'} );

        return $content;
    }

    /**
     * Return parent content object
     *
     * @param string $childContentId
     *
     * @throws \RuntimeException
     *
     * @return \eZ\Publish\Core\Repository\Values\Content\Content
     */
    public function parentContent( $childContentId )
    {
        if ( !is_int( (int)$childContentId ) )
        {
            throw new RuntimeException( '$contentId must be an integer' );
        }

        $contentService = $this->repository->getContentService();
        $locationService = $this->repository->getLocationService();

        $childContent = $contentService->loadContent( (int)$childContentId );
        $locations = $locationService->loadLocations( $childContent->contentInfo );

        $content = $this->contentFromLocationId( $locations[0]->parentLocationId );

        return $content;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'snce_utility';
    }
}
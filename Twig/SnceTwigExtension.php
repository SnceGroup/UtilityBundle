<?php
/**
 * Created by PhpStorm.
 * User: Gabriele Perego
 * Date: 07/12/13
 * Time: 13:53
 */

namespace Snce\UtilityBundle\Twig;

use eZ\Publish\API\Repository\Repository,
    Snce\UtilityBundle\Helper\EzRepositoryHelper,
    Twig_Extension,
    Twig_SimpleFunction,
    RuntimeException;

class SnceTwigExtension extends Twig_Extension
{

    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    private $repository;

    /**
     * @var \Snce\UtilityBundle\Helper\EzRepositoryHelper
     */
    private $ezRepositoryHelper;

    public function __construct( Repository $repository, EzRepositoryHelper $ezRepositoryHelper )
    {
        $this->repository = $repository;
        $this->ezRepositoryHelper = $ezRepositoryHelper;
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
     * @param mixed $contentId
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

        return $this->ezRepositoryHelper->getContentFromContentId( (int)$contentId );
    }

    /**
     * Return a content object from a location id
     *
     * @param mixed $locationId
     *
     * @throws \RuntimeException
     *
     * @return \eZ\Publish\Core\Repository\Values\Content\Content
     */
    public function contentFromLocationId( $locationId )
    {
        if ( !is_int( (int)$locationId ) )
        {
            throw new RuntimeException( '$locationId must be an integer' );
        }

        return $this->ezRepositoryHelper->getContentFromLocationId( (int)$locationId );
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
            throw new RuntimeException( '$childContentId must be an integer' );
        }

        return $this->ezRepositoryHelper->getParentContentFromChildContentId( $childContentId );
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
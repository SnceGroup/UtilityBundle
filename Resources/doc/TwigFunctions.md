#Twig Functions

##snce_utility_content
Given a content id the function return a \eZ\Publish\Core\Repository\Values\Content\Content
Particularly useful with content object rleations

###Usage
```twig
snce_utility_content( field.value.destinationContentId )
```

##snce_utility_content_from_locationId
Given a location id the function return a \eZ\Publish\Core\Repository\Values\Content\Content
Particularly useful to retrive the parent content object

###Usage
```twig
snce_utility_content_from_locationId( location.parentLocationId )
```

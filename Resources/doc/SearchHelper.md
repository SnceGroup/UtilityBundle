#SearchHelper class
Helper class useful to simplify standard eZ Publish full text search

##Usage
```php
$searchHelper = $this->get( 'snce_utility.search_helper' );
$searchResults = $searchHelper->fullTextResults( $query );
```

##Parameters
string $textQuery: text query  
array $contentType: contentTypes to consider in search, default all  
int $limit: search limit, default 10  
int $offset: search offset, default 0  

##Other parameters
excluded_content_type: contentTypes to exclude from search, must be specified in parameters.yml (%excluded_content_type%)

##Returns
\eZ\Publish\API\Repository\Values\Content\Search\SearchResult

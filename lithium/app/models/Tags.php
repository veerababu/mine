<?php

namespace app\models;
use lithium\util\Inflector;

class Tags extends \lithium\data\Model 
{
	
	// Look up tags in DB. make a list of their parent tags
	// stick any new tags in the DB. 
	// RETURN: list of searchTags
	public static function createSearchTags($story)
	{
		
		$searchTags=$story['tags'];
		array_push($searchTags,Inflector::slug($story['hood']));
		array_push($searchTags,Inflector::slug($story['city']));
		array_push($searchTags,Inflector::slug($story['author']));
		
		
		// TODO: remove dup tags
		foreach($searchTags as $key => &$tag)
		{
			$tag=Inflector::slug($tag);
			if( empty($tag) ) unset( $searchTags[$key] );
		}
		
		return( array_values($searchTags) );
	}
	
	public static function cleanFormTags($tags)
	{
		// TODO: remove dup tags
		$tags=explode(',',$tags);
    			
		foreach($tags as $key => &$tag)
		{
			$tag=trim($tag);
			if( empty($tag) ) unset( $tags[$key] );
		}
		return( array_values($tags) );
	}
}

?>
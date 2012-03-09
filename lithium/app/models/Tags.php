<?php

namespace app\models;

class Tags extends \lithium\data\Model 
{
	
	// Look up tags in DB. make a list of their parent tags
	// stick any new tags in the DB. 
	// RETURN: list of searchTags
	public static function processTags($story)
	{
		
		$tags=$story['tags'];
		array_push($tags,$story['hood']);
		array_push($tags,$story['city']);
		array_push($tags,$story['author']);
		
		
		// TODO: remove dup tags
		foreach($tags as $key => &$tag)
		{
			$tag=trim($tag);
			if( empty($tag) ) unset( $tags[$key] );
		}
		
		return( array_values($tags) );
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
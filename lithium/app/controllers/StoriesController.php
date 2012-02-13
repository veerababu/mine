<?php

namespace app\controllers;

use app\models\Story;

class StoriesController extends \lithium\action\Controller 
{
	public function index($tags = null) 
	{
		$conditions = $tags ? compact('tags') : array();
		$stories = Story::all(compact('conditions'));
		$title='Find a Story';
		
		return compact('stories',$title);
		
        
    }
}

?>
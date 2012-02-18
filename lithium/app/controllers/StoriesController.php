<?php

namespace app\controllers;

use app\models\Story;
use lithium\storage\Session;

class StoriesController extends \lithium\action\Controller 
{
	public function index($tags = null) 
	{
		$conditions = $tags ? compact('tags') : array('status' => 'accepted' );
		$status="accepted";
		//$stories = Story::all(compact('conditions','status'));
		$stories = Story::all(compact('conditions'));
		$title='Find a Story';
		
		return compact('stories',$title);
    }
    
    // if null username it will return the current user's stories 
    public function user($username = null)
    {
    	if($username)
    	{
    		$stories = Story::all( array( 'author' => $username , 'status' => 'accepted' ) );
    	}else
    	{
    		$username=Session::read('user.username');
    		$stories = Story::all( array( 'author' => $username ) );
        }
    	
    	$this->render(array('json' => compact('stories')));
    }
    
}

?>
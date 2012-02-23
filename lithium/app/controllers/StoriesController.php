<?php

namespace app\controllers;

use app\models\Story;
use lithium\storage\Session;

class StoriesController extends \lithium\action\Controller 
{
	public function index() 
	{
		$title='Find a Story';
		
		return compact($title);
    }
    
    
    
    ////////////////////////////////////////
    //// AJAX functions
    
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
    
   
    
    public function fetch()
    {
    	$conditions = array('status' => 'accepted' );
    	
		$stories = Story::all(compact('conditions'));
		
		
		$this->render(array('json' => compact('stories')));
    }
    
}

?>
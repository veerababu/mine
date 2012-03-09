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
    		$stories = Story::find('all',array('conditions' =>  array( 'author' => $username , 'status' => 'accepted' )) );
    	}else
    	{
    		$username=Session::read('user.username');
    		//echo($username);
    		$stories = Story::find('all', array('conditions' => array( 'author' => $username ) ) );
        }
    	
    	$this->render(array('json' => compact('stories')));
    }
    
   
    
    public function fetch()
    {
    	//print_r($this->request->data);
    	$page="1";
    	
    	if($this->request->data)
    	{
    		if(isset($this->request->data['page'])) $page=$this->request->data['page'];
    		
    		$tags=array();
    		foreach($this->request->data as $key => $value)
    		{
    			if($key[0]=='t') array_push($tags,$value);
    		}
    		
    		if(empty($tags)) $conditions = array('status' => 'accepted');
    		else $conditions = array('status' => 'accepted' , 'searchTags' => array('$all' => $tags));
    		
    		
    	}else
    	{
    		$conditions = array('status' => 'accepted' );
    	}
    	
    	$count= Story::count(compact('conditions'));
    	
    	$limit=3;
    	
    	//print_r($conditions);
		$stories = Story::all(compact('conditions','limit','page'));
		
		
		// TODO: save filters so we know what the common ones are
		
		
		$this->render(array('json' => compact('stories','page','count')));
    }
    
}

?>
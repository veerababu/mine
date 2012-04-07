<?php

namespace app\controllers;

use app\models\Story;
use lithium\util\Inflector;
use lithium\storage\Session;

class StoriesController extends \lithium\action\Controller 
{
	public function index() 
	{
		//print_r($this->request);
		
		$title='Find a Story';
		$tags='';
    	if(isset($this->request->query['tags']))
    	{	
    		
    		
    		 $tags='"'.implode('","', $this->request->query['tags'] ).'"';
    	}
    	//echo("tags: $tags<br>");
    	
    	$search='';
    	if(isset($this->request->query['search'])) $search=$this->request->query['search'];
    	$page=1;
    	if(isset($this->request->query['page'])) $page=$this->request->query['page'];
		
		
		return compact('title','tags','search','page');
    }
    
    public function feed()
    {
    	$title='Feed';
		
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
    		$username=Session::read('user.title');
    		//echo($username);
    		$stories = Story::find('all', array('conditions' => array( 'author' => $username ) ) );
        }
    	
    	$this->render(array('json' => compact('stories')));
    }
    
   
    // User searches for stories
    public function fetch()
    {
    	//print_r($this->request->data);
    	$page="1";
    	
    	if($this->request->data)
    	{
    		if(isset($this->request->data['page'])) $page=$this->request->data['page'];
    		
    		$searchTags=array();
    		if(isset($this->request->data['tags'])) $searchTags=$this->request->data['tags'];
    		
    		$search=array();
    		if(isset($this->request->data['search'])) $search=$this->request->data['search'];
    		
    		
    		$conditions = array('status' => 'accepted');
    		
    		if(!empty($searchTags)) 
    		{
    			foreach($searchTags as &$tag)
				{
    				$tag=Inflector::slug($tag);
				}
    			$conditions['searchTags'] = array('$all' => $searchTags);
    		}
    		
    		
    	}else
    	{
    		$conditions = array('status' => 'accepted' );
    	}
    	
    	$count= Story::count(compact('conditions'));
    	
    	$limit=3;
    	$order= array('updated' => -1);
    	
    	//print_r($conditions);
		$stories = Story::all(compact('conditions','limit','page','order'));
		
		/*
		if(count($stories)==0)
		{
		} */ 
		
		
		// TODO: save filters so we know what the common ones are
		
		
		$this->render(array('json' => compact('stories','page','count')));
    }
    
}

?>
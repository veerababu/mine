<?php


namespace app\controllers;

use app\models\Tags;
use app\models\Image;
use lithium\security\Auth;
use lithium\storage\Session;



class TagsController extends \lithium\action\Controller 
{
	
	
	public function index() 
	{
        return array('title' => 'Tag List');
    }
    
    
    
    ////////////////////////////////////////
    // AJAX functions
    
    public function get()
    {
    	$tags=array();
    	$tags[0]='food';
    	$tags[1]='sleep';
    	$tags[2]='kids';
    	$this->render(array('json' => compact('tags')));
    }
    
    public function getCities()
    {
    	$tags=array();
    	$tags[0]='food';
    	$tags[1]='sleep';
    	$tags[2]='kids';
    	$this->render(array('json' => compact('tags')));
    }
    
    public function getHoods()
    {
    	$tags=array();
    	$tags[0]='food';
    	$tags[1]='sleep';
    	$tags[2]='kids';
    	$this->render(array('json' => compact('tags')));
    }
    
    // returns the tags to display based on what is already filtered
    public function getCommon()
    {
    	// check cache
    	// if cache is old or empty refigure cache
    	
    	$tags=array();
    	$tags[0]='food';
    	$tags[1]='sleep';
    	$tags[2]='kids';
    	$tags[3]='outside';
    	$tags[4]='morsels';
    	$tags[5]='musings';
    	$tags[6]='adventure';
    	
    	$this->render(array('json' => compact('tags')));
    	
    	/*
    	print_r($this->request->data);
    	
    	$story = Story::find($this->request->id);
    	if($story)
    	{
    		$this->render(array('json' => compact('story')));
    	}else
    	{
    		$error="Story not found?";
    		
    		$this->render(array('json' => compact('error')));
    	}*/
    }
    
    
}

?>
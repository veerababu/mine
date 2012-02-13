<?php

namespace app\controllers;

use app\models\Story;
use app\models\Image;
//use app\controllers\HomeController;

class StoryController extends \lithium\action\Controller 
{
	
	
	public function index() 
	{
        return array('title' => 'Story');
    }
    
    public function edit() 
    {
		//$post = Story::find(1);
		//return compact('post');
    	//return array('title' => 'Submit Story');
    	$image = Image::create();
    	

    	if($this->request->data) 
    	{
    		//print_r($this->request);
        	$story = Story::create($this->request->data);
        	$success = $story->save();
    	}else
    	{
    		$story = Story::create();
    		$story->title = 'Story Title';
			$story->desc = 'Enter description here';
			$story->address = 'Story Address';
    	}
    	
    	return compact('story','image');
    }
    
    public function publish() 
    {
    	
    	return $this->redirect('/');
    }
    
    public function delete() 
    {
    	$storyID=$this->request->params['id'];
    	if($storyID)
    	{
    		Story::remove(array('_id' => $storyID ));
    		//return array('title' => 'Story Deleted');
    	}
    	return $this->redirect('/');
    }
}

?>
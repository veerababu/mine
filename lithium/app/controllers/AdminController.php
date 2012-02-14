<?php

namespace app\controllers;

use app\models\Story;
use app\models\Image;
//use app\controllers\HomeController;

/* 

Should show a list of the pending stories
 
*/

class AdminController extends \lithium\action\Controller 
{
	
	
	public function index() 
	{
		if(Session::read('default.role') == 'admin')
		{
			$conditions = array('status' => 'pending' );
			$stories = Story::all(compact('conditions'));
			$title='Admin Center';
			
			return compact('stories',$title);
		}
		return $this->redirect('/');
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
    
    public function approve() 
    {
    	$storyID=$this->request->params['id'];
    	if($storyID)
    	{
    		
    	}
    	return $this->redirect('/admin/');
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

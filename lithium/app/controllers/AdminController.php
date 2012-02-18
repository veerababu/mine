<?php

namespace app\controllers;

use app\models\Story;
use app\models\Image;
use lithium\storage\Session;
//use app\controllers\HomeController;

/* 

Should show a list of the pending stories
 
*/

class AdminController extends \lithium\action\Controller 
{
	
	
	public function index() 
	{
		if(Session::read('user.role') != 'admin') return $this->redirect('/');
		
		$conditions = array('status' => 'pending' );
		$stories = Story::all(compact('conditions'));
		//$stories = Story::all();
		$title='Admin Center';
		
		//print_r($stories);
		
		return compact('stories',$title);
		
		
    }
    
    public function edit() 
    {
    	if(Session::read('user.role') != 'admin') return $this->redirect('/');
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
    
  ////////////////////////////////////////
  // AJAX functions
    
    public function approve($storyID) 
    {
    	if(Session::read('user.role') != 'admin') return $this->redirect('/');
    	
    	if($this->request->data) 
		{
			$this->request->data['status']="accepted";	
			Story::update($this->request->data);
 
			$status="Approved";
			$remove=$storyID;
    	 	// update the stories list
    	 	$this->render(array('json' => compact('status','remove')));
		}else
		{
			$error="No data found?";
			$this->render(array('json' => compact('error')));
		}
    	
    }
    
     public function reject($storyID) 
    {
    	if(Session::read('user.role') != 'admin') return $this->redirect('/');
    	if($this->request->data) 
		{
			$this->request->data['status']="working";	
			Story::update($this->request->data);
 
			$status="Rejected";
			$remove=$storyID;
    	 	// update the stories list
    	 	$this->render(array('json' => compact('status','remove')));
		}else
		{
			$error="No data found?";
			$this->render(array('json' => compact('error')));
		}
    }
    
    public function delete($storyID) 
    {
    	if(Session::read('user.role') != 'admin') return $this->redirect('/');
    	
    	if($storyID)
    	{
    		Story::remove(array('_id' => $storyID ));
    		$status="Deleted";
			$remove=$storyID;
    	 	// update the stories list
    	 	$this->render(array('json' => compact('status','remove')));
    	}
    	
    }
}

?>

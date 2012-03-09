<?php

namespace app\controllers;

use app\models\Story;
use app\models\Tags;
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
			
    }
    
    public function stories()
    {
    	if(Session::read('user.role') != 'admin') return $this->redirect('/');
    		
		$title='Admin Center';
		
		return compact($title);
    }
    
    public function edit() 
    {
    	if(Session::read('user.role') != 'admin') return $this->redirect('/');
		
		$title='Admin: Pending Stories';
    	
    	return compact($title);
    }
    
    // LATER: Maybe we don't need this since we are approving the story so we are implicitly approving the tags.
    public function tags() 
    {
    	if(Session::read('user.role') != 'admin') return $this->redirect('/');
		$title='Admin: Manage Tags';
    	return compact($title);
    }
    
    
    
  ////////////////////////////////////////
  // AJAX functions
  
  	public function getPending()
  	{
  		if(Session::read('user.role') != 'admin') return $this->redirect('/');
  		
  		$conditions = array('status' => 'pending' );
		$stories = Story::all(compact('conditions'));
		
		$this->render(array('json' => compact('status','stories')));
  	}
    
    public function approve() 
    {
    	if(Session::read('user.role') != 'admin') return $this->redirect('/');
    	
    	if($this->request->data) 
		{
			$stories=$this->updateStory($this->request->data,"accepted");
	 		
			$status="Approved";
			
    	 	// update the stories list
    	 	$this->render(array('json' => compact('status','stories')));
		}else
		{
			$error="No data found?";
			$this->render(array('json' => compact('error')));
		}
    	
    }
    
     public function reject() 
    {
    	if(Session::read('user.role') != 'admin') return $this->redirect('/');
    	if($this->request->data) 
		{
			$stories=$this->updateStory($this->request->data,"working");
 
			$status="Rejected. Showing Next story...";
    	 	// update the stories list
    	 	$this->render(array('json' => compact('status','stories')));
		}else
		{
			$error="No data found?";
			$this->render(array('json' => compact('error')));
		}
    }
    
    public function delete() 
    {
    	if(Session::read('user.role') != 'admin') return $this->redirect('/');
    	$storyID=$this->request->data['_id'];
    	if($storyID)
    	{
    		echo($storyID);
    		Story::remove(array('_id' => $storyID ));
    		$status="Deleted";
    		
			$conditions = array('status' => 'pending' );
			$stories = Story::all(compact('conditions'));
			$story=Story::find('first', compact('conditions') );
			
    	 	// update the stories list
    	 	$this->render(array('json' => compact('status','stories','story')));
    	}
    }
    
   
    
    function updateStory($story,$status)
    {
    	$id=$story['_id'];
    	$story['status']=$status;	
		$story['tags']=Tags::cleanFormTags($story['tags']);
		
		unset($story['_id']);
		
		if($status=='accepted')
		{
			$story['searchTags']=Tags::processTags($story);
			$story['created']=time();
		}
		
	 	Story::update($story, array('_id' => $id ));
	 		
	 	$conditions = array('status' => 'pending' );
		$stories = Story::all(compact('conditions'));
		$story=Story::find('first', compact('conditions') );
		return(compact('stories','story'));
    }
}

?>

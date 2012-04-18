<?php

namespace app\controllers;

use app\models\Users;
use app\models\Story;
use app\models\Tags;
use app\models\Image;
use lithium\storage\Session;
use app\controllers\StoryController;
use lithium\util\Inflector;

/* 

Should show a list of the pending stories
 
*/

class AdminController extends \lithium\action\Controller 
{
	
	
	public function index() 
	{
		if(Session::read('user.role') != 'admin') return $this->redirect('/');
			
		$title='Admin: Stats';
		
		$userCount=Users::count();
		$storyCount=Story::count();
    	
    	return compact('title','userCount','storyCount');
    }
    
    public function stories()
    {
    	if(Session::read('user.role') != 'admin') return $this->redirect('/');
    		
		//print_r($this->request);
    	if($this->request->params['args'] && $this->request->params['args'][0]) $storyTitle=$this->request->params['args'][0];
    	else $storyTitle='';
		
		$title='Admin: Pending Stories';
    	
    	return compact('title','storyTitle');
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
			$this->updateStory($this->request->data,"accepted"," Approved!");
	 		
			
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
			$stories=$this->updateStory($this->request->data,"working",", no way! Blasted that crap back.");
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
    		//echo($storyID);
    		Story::remove(array('_id' => $storyID ));
    		$status="Deleted";
    		
			$conditions = array('status' => 'pending' );
			$stories = Story::all(compact('conditions'));
			$story=Story::find('first', compact('conditions') );
			
    	 	// update the stories list
    	 	$this->render(array('json' => compact('status','stories','story')));
    	}
    }
    
   
    
    function updateStory($story,$status,$returnStatus)
    {
    	$id=$story['_id'];
    	$story['title']=trim($story['title']);
    	$story['slug']=Inflector::slug($story['title']);
    	if( isset($story['layout']) ) $story['layout']=1;
    	else  $story['layout']=0;
    	
    	if(StoryController::isUnique($story['slug'],$id))
		{
			$story['tags']=Tags::cleanFormTags($story['tags']);
			
			unset($story['_id']);
			$story['url']=str_replace("http://","",$story['url']);
			$story['url']=str_replace("https://","",$story['url']);
			
			if($status=='accepted')
			{
				$story['searchTags']=Tags::createSearchTags($story);
				if($story['status'] != 'accepted') 
				{
					$story['created']=time();
					$story['updated']=$story['created'];
				}else $story['updated']=time();
			}
			
			$story['status']=$status;	
			
			
			
		 	Story::update($story, array('_id' => $id ));
		 		
		 	$conditions = array('status' => 'pending' );
			$stories = Story::all(compact('conditions'));
			$story=Story::find('first', compact('conditions') );
			$status=$story['title'].$returnStatus.' Moving to the next Story...';
				
	    	$this->render(array('json' => compact('status','stories','story')));
		}else
		{
			$error="Change the title. That one is taken.";
			$this->render(array('json' => compact('error')));
		}
 
    }
}

?>

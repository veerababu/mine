<?php


namespace app\controllers;

use app\models\Story;
use app\models\Tags;
use app\models\Users;
use app\models\Image;
use lithium\security\Auth;
use lithium\storage\Session;
use lithium\util\Inflector;
use app\extensions\images\Images;
//use app\controllers\HomeController;



class StoryController extends \lithium\action\Controller 
{
	
	
	public function index() 
	{
        return array('title' => 'Story');
    }
    
    public function edit() 
    {
    	 if(!Auth::check('user')) 
    	 {
    	 	Session::write('message', 'Please Login to Contribute.');
            return $this->redirect('Users::login');
         }
    	

    	$story = Story::create();
    	$story->_id=0;
    	$story->title = 'Story Title';
		$story->desc = 'Enter description here';
		$story->address = 'Story Address';
    	
    	$title="Sumbit!";
    	return compact('title','story');
    }
    
    public function test() 
    {
    	 if(!Auth::check('user')) 
    	 {
    	 	Session::write('message', 'Please Login to Contribute.');
            return $this->redirect('Users::login');
         }
    	

    	$story = Story::create();
    	$story->_id=0;
    	$story->title = 'Story Title';
		$story->desc = 'Enter description here';
		$story->address = 'Story Address';
    	
    	$title="Test Sumbit!";
    	return compact('title','story');
    }
    
    public function view()
    {
    	
    	//print_r($this->request->params->args);
    	$storyTitle=$this->request->params['args'][0];
    	$title=$storyTitle;
    	$admin=(Session::read('user.role') == 'admin');
    	return compact('title','storyTitle','admin');
    }
    
    ////////////////////////////////////////
    // AJAX functions
    public function get()
    {
    	$story = Story::find($this->request->id);
    	if($story)
    	{
    		$this->render(array('json' => compact('story')));
    	}else
    	{
    		$error="Story not found?";
    		
    		$this->render(array('json' => compact('error')));
    	}
    }
    
    public function getByTitle()
    {
    	$storyTitle=$this->request->params['args'][0];
    	$storyTitle=strtolower(urldecode($storyTitle));
    	
    	//echo($storyTitle);
    	$story = Story::first(array('conditions' => array('slug' => $storyTitle)));
    	if($story)
    	{
    		$this->render(array('json' => compact('story')));
    	}else
    	{
    		$error="Story not found?";
    		
    		$this->render(array('json' => compact('error')));
    	}
    }
    
    public function save() 
    {
    	$this->updateStory("working"," saved.",true);
    }
    
    public function publish() 
    {
    	$this->updateStory("pending"," sent to editors for review.",false);
    }
    
    function updateStory($status,$returnText,$returnStory)
    {
    	if(Auth::check('user')) 
    	{
    		if($this->request->data) 
    		{
    			//print_r($this->request->data);
    			
    			$author=Session::read('user.title');
    			
    			//echo($author);
    			$story=$this->request->data;
    			$story['title']=trim($story['title']);
    			$story['slug']=Inflector::slug($story['title']);
    			if( isset($story['layout']) ) $story['layout']=1;
    			else  $story['layout']=0;
    			
		    	if(StoryController::isUnique($story['slug'],$story['_id']))
		    	{
	    			$story['status']=$status;
	    			
	    			$story['tags']=Tags::cleanFormTags($story['tags']);
	    			
	    			$story['url']=str_replace("http://","",$story['url']);
	    			$story['url']=str_replace("https://","",$story['url']);
	    			
	    			/* TODO: not sure the best way to handle this
	    			$story['tags'][]=$story['hood'];
	    			$story['tags'][]=$story['city'];
	    			unset($story['hood']);
	    			unset($story['city']);
	    			*/
	    			
	    			if(isset($story['_id']) && $story['_id']==0)
	    			{ 
	    				unset($story['_id']);
	    			}
	    			
	    			if(isset($story['_id']) )
	    			{	    	 		
		    	 		$id=$story['_id'];
		    	 		unset($story['_id']);
		    	 		
		    	 		//print_r($story);
		    	 		
		    	 		Story::update($story, array('_id' => $id ));
		    	 		
		    	 		//print_r($ret);
		    	 			 		
		    	 		$story['author']=$author;
		    	 		$story['authorSlug']=Inflector::slug($author);
		    	 		$story['_id']=$id;
		    	 		$returnStory=false;
		    	 		
	    			}else
	    			{
	    				$story['author']=$author;
	    				$story['authorSlug']=Inflector::slug($author);
	    				$story = Story::create($story);
	        			$success = $story->save();
	    			}
	    			
	    			$status=$story['title'].$returnText;
	    			
	    			
					$stories = Story::all(array('conditions' =>  array( 'author' => $author ), 'fields' => array('title','status')));
					
		    	 	// update the stories list
		    	 	if($returnStory) $this->render(array('json' => compact('status','story','stories')));
		    	 	else $this->render(array('json' => compact('status','stories')));
		    	}else
		    	{
		    		$error="Sorry a story with that name already exists. Please choose another.";
    				$this->render(array('json' => compact('error')));
		    	}
    		}else
    		{
    			$error="No data found?";
    			$this->render(array('json' => compact('error')));
    		}
        }else
    	{
    		$error="You are no longer logged in. Please <a href='/login'>Login</a> to continue.";
    		
    		$this->render(array('json' => compact('error')));
    	}
    }
    
    public static function isUnique($slug, $id)
    {
    	$story=Story::first(array('conditions' => array('slug' => $slug),'fields' => array('_id')));
    	if($story && $story['_id'] != $id) return(false);
    	return(true);
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
    
    // This image may or may not eventually be included in the story
    // stick it in the DB. return the imageID so we can add it to the form to be saved or published
    public function addImage()
    {
    	$success=false;
    	
    	if(Auth::check('user')) 
    	{
    		//print_r($this->request->data);
    		$allowedExtensions = array();
			// max file size in bytes
			$sizeLimit = 10 * 1024 * 1024;
			
			$username=Session::read('user.title');
			
			$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
			$photoID = $uploader->handleUpload($username);
			//echo($photoID);
			
			// TODO: check result
			if($photoID)
			{
				$success=true;
				//$this->render(array('json' => compact('photoID','success')));
				$this->render(array('json' => array('photoID' => "$photoID" ,'success' => true )));
			} 
				
        }else
    	{
    		$error="You are no longer logged in. Please <a href='/login'>Login</a> to continue.";
    		
    		$this->render(array('json' => compact('error','success')));
    	}
    	
    	
    	//$this->render(array('json' => compact('success')));
    }
    public function saveImage()
	{
    	if(Auth::check('user')) 
    	{
    		if(isset($_POST['i']) && isset($_POST['name'])) 
			{
				$index=$_POST['index'];
			    $data=$_POST['i'];
			    $filteredData=substr($data, strpos($data, ",")+1);
			    $decodedData=base64_decode($filteredData);
			    $tmp_name = tempnam(sys_get_temp_dir(), "FOO");

				$temp = fopen($tmp_name, "w");
				$size=fwrite($temp, $decodedData);
				fclose($temp);
				
				$name= $_POST['name'];
				$type="image/jpeg";
		        $error=0;
		        $file=compact('name','type','tmp_name','error','size');
		        
		        $author=Session::read('user.title');
		        $photo = Image::create();
		        $data=compact('author','file');
		        //print_r($data);
		        $photo->save($data);
				$photoID=$photo->_id;
				$this->render(array('json' => array('photoID' => "$photoID", 'photoIndex' => "$index" )));
			    
		   	}else 
		   	{
			    $error="no data?";
	    		
	    		$this->render(array('json' => compact('error')));
		   	}
	   
    		
				
        }else
    	{
    		$error="You are no longer logged in. Please <a href='/login'>Login</a> to continue.";
    		
    		$this->render(array('json' => compact('error')));
    	}
		 
	}
}

?>
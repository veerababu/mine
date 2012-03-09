<?php


namespace app\controllers;

use app\models\Story;
use app\models\Tags;
use app\models\Image;
use lithium\security\Auth;
use lithium\storage\Session;
//use app\controllers\HomeController;



/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
     function save($author,$name) 
    {    
    	$tmp_name = tempnam(sys_get_temp_dir(), "FOO");

		$temp = fopen($tmp_name, "w");

        $input = fopen("php://input", "r");
        $size = stream_copy_to_stream($input, $temp);
        fclose($input);
        fclose($temp);
       
        
        if ($size != $this->getSize()){            
            return false;
        }
        // [file] => Array ( [name] => dog.jpg [type] => image/jpeg [tmp_name] => C:\Windows\Temp\phpDFC3.tmp [error] => 0 [size] => 7349 ) ) 
        $type="image/jpeg";
        $error=0;
        $file=compact('name','type','tmp_name','error','size');
        
        
        $photo = Image::create();
        $data=compact('author','file');
        //print_r($data);
        $photo->save($data);
        
        return $photo->_id;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }   
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
    	// TODO:
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        //JED $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($username)
    {    
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'File is empty');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
        
        
        $photoID=$this->file->save($username, $filename . '.' . $ext);
        if($photoID){
            return $photoID;
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
        
    }    
}

/*
// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array();
// max file size in bytes
$sizeLimit = 10 * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
$result = $uploader->handleUpload('uploads/');
// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
*/

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
    	
    	
    	return compact('story');
    }
    
    public function view()
    {
    	//print_r($this->request->params->args);
    	$storyTitle=$this->request->params['args'][0];
    	return compact('storyTitle');
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
    	$storyTitle=urldecode($storyTitle);
    	
    	$story = Story::first(array('conditions' => array('title' => $storyTitle)));
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
    	$this->updateStory("working",false);
    }
    
    public function publish() 
    {
    	$this->updateStory("pending",false);
    }
    
    function updateStory($status,$admin)
    {
    	if(Auth::check('user')) 
    	{
    		if($this->request->data) 
    		{
    			//print_r($this->request->data);
    			
    			$username=Session::read('user.username');
    			$story=$this->request->data;
    			$story['status']=$status;
    			
    			$story['tags']=Tags::cleanFormTags($story['tags']);
    			
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
	    	 			 		
	    	 		$story['author']=$username;
	    	 		$story['_id']=$id;
	    	 		
    			}else
    			{
    				$story['author']=$username;
    				$story = Story::create($story);
        			$success = $story->save();
    			}
    			
    			$status="Saved.";
    			// TODO: we only need some of this data
				$stories = Story::all(array('conditions' =>  array( 'username' => $username )));
	    	 	// update the stories list
	    	 	$this->render(array('json' => compact('status','story','stories')));
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
			
			$username=Session::read('user.username');
			
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
}

?>
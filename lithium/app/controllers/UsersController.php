<?php

namespace app\controllers;

use lithium\security\Auth;
use app\models\Users;
use app\models\Tags;
use app\models\Story;
use lithium\storage\Session;
use app\controllers\StoryController;
use lithium\util\Inflector;


class UsersController extends \lithium\action\Controller 
{
    public function index() 
    {
    	$users = Users::all();
        return compact('users');
    }
    
    public function profile() 
    {
    	 if(!Auth::check('user')) 
    	 {
    	 	Session::write('message', 'Please Login first.');
            return $this->redirect('Users::login');
         }
         
    	$title='Edit your Profile';
    	//$userSlug=Inflector::slug(Session::read('user.title'));
    	
    	return compact('title');
    }
    
    public function view() 
    {
    	$userSlug=$this->request->params['args'][0];
    	$title=$userSlug;
    	
    	return compact('title','userSlug');
    }
    
    public static function isUnique($name,$slug)
    {
    	$name=strtolower($name);
    	if(Users::first(array('conditions' => array('username' => $name)))) return(false);
    	if(Users::first(array('conditions' => array('slug' => $slug)))) return(false);
    	return(true);
    }
    
    
    

    public function register() 
    {
        if($this->request->data)
        {
        	if($this->request->data['password'])
        	{
        		$this->request->data['username']=trim($this->request->data['username']);
      			//$displayName=$this->request->data['username'];
      			$this->request->data['title']=$this->request->data['username'];		
      			//$this->request->data['slug']=$this->request->data['username'];
        		$this->request->data['username']=strtolower($this->request->data['username']);
        		$this->request->data['slug']=Inflector::slug( $this->request->data['title'] );
        		
        		$this->request->data['public']=1;
        		$this->request->data['created']=time();
        		$this->request->data['updated']=$this->request->data['created'];
        		
	        	if(	UsersController::isUnique($this->request->data['username'], $this->request->data['slug']) )
				{
					$user = Users::create($this->request->data);
					 
	        		if($user->save()) 
			        {
			        	if(Auth::check('user', $this->request))
			        	{
			        		Session::write('message', 'Welcome');
			            	return $this->redirect('/');
			        	}else 
			            {
			            	Session::write('message', 'Login Failed');
			            	//return $this->redirect('/');
			            }
	        			
			        }else
			        {
			        	$user = Users::create($this->request->data);
						Session::write('message', 'Something went wrong?');
			        }
				}else
				{
					$user = Users::create($this->request->data);
					Session::write('message', 'Sorry that username is already taken.');
				}
        	}else
			{
				$user = Users::create($this->request->data);
				Session::write('message', 'You must enter a password.');
			}  
		}else
		{
			$user = Users::create($this->request->data);
        }
		
		return compact('user');
    }
    
    
    public function login() 
    {
        if($this->request->data) 
        {
        	$this->request->data['username']=strtolower($this->request->data['username']);
        	if(Auth::check('user', $this->request))
        	{
        		//print_r(Session::read());
        		//Session::write('message',Session::read('user.role'));
            	return $this->redirect('/');
        	}else  
        	{
        		//echo("fail");
        		Session::write('message', 'Login Failed');
        	}
        	
        }//else Session::write('message', 'Welcome');
    }

	public function logout() 
	{
        Auth::clear('user');
        return $this->redirect('/');
    }
    //////////////////////////////////////////////////////////////
    /// AJAX
    
    public function fetchSelf()
    {
    	$userSlug=Inflector::slug(Session::read('user.title'));
    	
    	$user = Users::first(array('conditions' => array('slug' => $userSlug), 'fields' => array('title','text','tags','city','hood','country','url','email','public')));
    	if($user)
    	{
    		$this->render(array('json' => compact('user')));
    	}else
    	{
    		$error="User not found?";
    		
    		$this->render(array('json' => compact('error')));
    	}
    }
    
    public function getBySlug()
    {
    	$userSlug=$this->request->params['args'][0];
    	$userSlug=strtolower(urldecode($userSlug));
    	
    	//echo($userSlug);
    	$story = Users::first(array('conditions' => array('slug' => $userSlug, 'public' => 1), 'fields' => array('title','text','tags','city','hood','country','url','updated')));
    	if($story)
    	{
    		$story['author']=$story['title'];
    		$story['authorSlug']=$userSlug;
    		$story['slug']=$userSlug;
    		$this->render(array('json' => compact('story')));
    	}else
    	{
    		$error="User not found?";
    		
    		$this->render(array('json' => compact('error')));
    	}
    }
    
    public function save()
    {
    	$error='';
		if(Auth::check('user')) 
    	{
    		if($this->request->data) 
    		{
    			//print_r($this->request->data);
    			
    			$username=Session::read('user.username');
    			$oldTitle=Session::read('user.title');
    			
    			
    			$story=$this->request->data;
    			$story['title']=trim($story['title']);
    			//echo($story['title']);
    			//echo("  old one  $oldTitle");
    			if($story['title'] != $oldTitle)
    			{	// we are changing the Display name
    				$story['slug']=Inflector::slug($story['title']);
    				if(Users::first(array('conditions' => array('slug' => $story['slug'])))) 
		    		{
		    			$error="Sorry that name is taken. Please choose another.";
    					$this->render(array('json' => compact('error')));
		    			return;
		    		}
		    		$oldSlug=Inflector::slug($oldTitle);
		    		$update=array( 	'$set' => array( 'author' => $story['title'] , 'authorSlug' => $story['slug']), 
		    						'$addToSet' => array( 'searchTags' => $story['slug'] ) );
		    		$update2=array( '$pull' => array('searchTags' => $oldSlug) );
		    		// TODO: $pull not working?
		    			
		    		//echo("old: $oldSlug");	
		    		//print_r($update);		
		    		//$update['author']=$story['title'];
		    		//$update['authorSlug']=$story['slug'];
		    		//$update['searchTags']=$story['
		    		if(	!Story::update($update, array( 'authorSlug' => $oldSlug ) ) || 
		    			!Story::update($update2, array( 'authorSlug' => $oldSlug ) ) )
		    		{
		    			$error="Problem changing old stories";
		    		}
		    		
		    		Session::write('user.title',$story['title']);		
    			}
    			
    			
    			$id=$story['_id'];
    			unset($story['_id']);
		    	
	    			
	    			
		    	 	
    			$story['tags']=Tags::cleanFormTags($story['tags']);
    			$story['searchTags']=Tags::createSearchTags($story);
    			$story['updated']=time();
    			
    			Users::update($story, array('_id' => $id ));
    			
    			
    			$status="Profile Saved!";
				
	    	 	
	    	 	$this->render(array('json' => compact('status','error')));
		    	
    		}else
    		{
    			$error="No data found?";
    			$this->render(array('json' => compact('error')));
    		}
    	 	
		}else
		{
			$error="You must log in first.";
			
			$this->render(array('json' => compact('error')));
		}
    }
}

?>
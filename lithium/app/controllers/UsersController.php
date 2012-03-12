<?php

namespace app\controllers;

use lithium\security\Auth;
use app\models\Users;
use lithium\storage\Session;
use app\controllers\StoryController;


class UsersController extends \lithium\action\Controller 
{
    public function index() 
    {
    	$users = Users::all();
        return compact('users');
    }
    
    public function profile($username) 
    {
    	
    }
    

    public function register() 
    {
        if($this->request->data)
        {
        	if($this->request->data['password'])
        	{
        		$this->request->data['displayName']=$this->request->data['username'];		
        		$this->request->data['username']=strtolower($this->request->data['username']);
        		$this->request->data['created']=time();
        		
	        	if(StoryController::isUnique($this->request->data['username']))
				{
					$user = Users::create($this->request->data);
					 
	        		if($user->save()) 
			        {
			        	if(Auth::check('user', $this->request))
			            	return $this->redirect('Users::index');
			            else 
			            {
			            	Session::write('message', 'Login Failed');
			            	//return $this->redirect('/');
			            }
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
        	}else  Session::write('message', 'Login Failed');
        	
        }//else Session::write('message', 'Welcome');
    }

	public function logout() 
	{
        Auth::clear('user');
        return $this->redirect('/');
    }
}

?>
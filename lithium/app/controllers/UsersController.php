<?php

namespace app\controllers;

use lithium\security\Auth;
use app\models\Users;
use lithium\storage\Session;

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
        $user = Users::create($this->request->data);

        if(($this->request->data) && $user->save()) 
        {
        	if(Auth::check('user', $this->request))
            	return $this->redirect('Users::index');
            else 
            {
            	Session::write('message', 'Login Failed');
            	//return $this->redirect('/');
            }
        }
        return compact('user');
    }
    
    
    public function login() 
    {
        if($this->request->data) 
        {
        	if(Auth::check('user', $this->request))
        	{
        		//print_r(Session::read());
        		Session::write('message',Session::read('user.role'));
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
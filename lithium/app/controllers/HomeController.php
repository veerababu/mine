<?php

namespace app\controllers;
use app\models\Story;


class HomeController extends \lithium\action\Controller 
{
	
	
	public function index() 
	{
        return array('title' => 'Welcome');
    }
    
    ////////////////////////////////
    // AJAX
    
    // fetch a random 6 stories
    public function fetch()
    {
    	$limit=6;
    	$conditions = array('status' => 'accepted');
    	$stories = Story::all(compact('conditions','limit'));	
		$this->render(array('json' => compact('stories')));
    }
    
   
}

?>

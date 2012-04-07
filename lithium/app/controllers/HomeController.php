<?php

namespace app\controllers;
use app\models\Story;
use lithium\data\collection\RecordSet;

class HomeController extends \lithium\action\Controller 
{
	
	
	public function index() 
	{
        return array('title' => 'Welcome');
    }
    
    ////////////////////////////////
    // AJAX
    
    // fetch a random 6 stories
    // Oh come on! Add a random function mongo.
    public function fetch()
    {
    	$stories=array();
    	$conditions = array('status' => 'accepted' );
    	$count= Story::count(compact('conditions'));
    	for($loop=0; $loop<100; $loop++)
    	{   	
    		//echo("$loop ");
    		$page = mt_rand( 0,$count);
    		
    		
    		$story = Story::find('first',compact('conditions','page'));
    		if($story)
    		{
    			//echo($story['_id']);
    			$dup=false;
    			foreach($stories as $value)
    			{
    				if($value['_id']==$story['_id']) 
    				{
    					$dup=true;
    					break;
    				}
    			}
    			if(!$dup)
    			{
    				//array_push($stories,$story);
    				$stories[] = $story;
    				if(count($stories)>=9) break;
    			}
    		}
    		
    	}
    	$stories = new RecordSet(array('data' => $stories));
    	
		$this->render(array('json' => compact('stories')));
    	
    	
    	/*
    	$limit=6;
    	$conditions = array('status' => 'accepted');
    	$stories = Story::all(compact('conditions','limit'));	
    	
    	//print_r($stories);
    	//echo("_________");
    	//print_r( compact('stories') );
    	
		$this->render(array('json' => compact('stories')));
		
		*/
		
		/*
    	//$stories=array('stories' => {} );
    	$stories=array();
 
 		for($loop=0; $loop<100; $loop++)
    	{   	
    		//echo("$loop ");
    		$rand = mt_rand( 1331937596,time());
    		//$rand = mt_rand( time()-100,time());
    		echo("$rand ");
    		$conditions = array('status' => 'accepted', 'updated' => array('$gte' => $rand)  );
    		$story = Story::find('first',compact('conditions'));
    		if($story)
    		{
    			//echo($story['_id']);
    			$dup=false;
    			foreach($stories as $value)
    			{
    				if($value['_id']==$story['_id']) 
    				{
    					$dup=true;
    					break;
    				}
    			}
    			if(!$dup)
    			{
    				//array_push($stories,$story);
    				$stories[] = $story;
    				if(count($stories)>=9) break;
    			}
    		}
    		
    	}
    	
    	//print_r($stories);
    	 $stories = new RecordSet(array('data' => $stories));
    	
		$this->render(array('json' => compact('stories')));
		//$this->render(array('json' => array('stories' => $stories)));
		//$this->render(array('json' => $stories));
		*/
		
    }
    
   
}

?>

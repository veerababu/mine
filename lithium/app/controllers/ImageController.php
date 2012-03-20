<?php
/*
namespace app\controllers;

use app\models\Image;

class ImageController extends \lithium\action\Controller 
{
	
	public function index($tags = null) 
	{
		
		$conditions = $tags ? compact('tags') : array();
		$photos = Image::all(compact('conditions'));
		
		return compact('photos');
	}

	public function view() 
	{
		
		$photo = Image::first($this->request->id);
		return compact('photo');
	}
	
	public function add() {
		//print_r($this->request->data);
		
		
		$photo = Image::create();

		if (($this->request->data) && $photo->save($this->request->data)) 
		{
			$this->redirect(array('Image::view', 'id' => $photo->_id));
		}
		$this->_render['template'] = 'edit';
		return compact('photo');
	}
	
    
    public function edit() 
    {
		$photo = Image::find($this->request->id);

		if (!$photo) {
			$this->redirect('Image::index');
		}
		if (($this->request->data) && $photo->save($this->request->data)) {
			$this->redirect(array('Image::view', 'id' => $photo->_id));
		}
		return compact('photo');
    }
}
*/

?>
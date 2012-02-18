<?php

namespace app\models;

//use li3_geo\extensions\Geocoder;
//use li3_geo\extensions\data\behavior\Locatable;

class Image extends \lithium\data\Model 
{
	public $validates = array();

	protected $_meta = array('source' => 'fs.files');

	//protected $_actsAs = array(
	//	'Locatable' => array('fields' => array('location.latitude', 'location.longitude'))
	//);

	public function save($entity, $data = null, array $options = array()) 
	{
		//print_r($entity);
		
		if($data) {
			$entity->set($data);
		}

		
		return parent::save($entity, null, $options);
	}
	
	
	public function save2($entity, $data = null, array $options = array()) 
	{
		if ($data) {
			$entity->set($data);
		}

		//if (!$entity->exists() && isset($entity->file->tmp_name)) {
		//	$entity->location = Geocoder::exifCoords(exif_read_data($entity->file->tmp_name));
		//}

		if ($entity->tags && !is_array($entity->tags)) {
			$entity->tags = array_map('trim', explode(',', $entity->tags));
		}
		return parent::save($entity, null, $options);
	}
	
	
}

?>
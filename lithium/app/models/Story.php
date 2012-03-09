<?php

namespace app\models;

class Story extends \lithium\data\Model 
{
	public static function update2($data, $conditions = array(), array $options = array()) 
	{
		update($data,$conditions,$options);
	}
}

?>
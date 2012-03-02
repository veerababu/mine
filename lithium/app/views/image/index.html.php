<?php if (!count($photos)): ?>
	<em>No photos</em>. <?=$this->html->link('Add one', 'Image::add'); ?>.
<?php endif ?>

<?php foreach ($photos as $photo): ?>
	<?=$this->html->image("/image/view/{$photo->_id}", array('width'=> 100)); ?>
	<?=$this->html->link($photo->title, array('Image::view', 'id' => "{$photo->_id}")); ?>
	
	<?=$photo->title ?>
<?php endforeach ?>

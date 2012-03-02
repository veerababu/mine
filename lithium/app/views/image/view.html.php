<h1><?=$photo->title; ?></h1>
<p><?=$photo->description; ?></p>
<p><?=$this->html->link('Edit', array('Image::edit', 'id' => $photo->_id)); ?></p>



<?=$this->html->image("/image/view/{$photo->_id}", array('alt' => $photo->title)); ?>

<?php foreach ($photo->tags as $tag): ?>
	<?=$this->html->link($tag, array('Image::index', 'args' => array($tag))); ?>
<?php endforeach ?>
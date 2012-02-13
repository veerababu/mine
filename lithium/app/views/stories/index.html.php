

<h1>Here are all the stories</h1>
<div class="row"> <div class="span12">
<div class="row">
	<div class="span2 well">
	filter menu<p>lots of them<p> ad<p>
	</div>
	

	<div class="span10">
	<?php foreach ($stories as $story): ?>
		<div class="row">
		<?=$this->html->image("/image/view/{$story->_id}.jpg", array('width'=> 100)); ?>
		<?=$this->html->link($story->title, array('Image::view', 'id' => "{$story->_id}")); ?>
		</div>
	<?php endforeach ?>
	
	<div class="row">
	Think we missed something? <?=$this->html->link('Tell us what.', 'Story::edit'); ?>
	</div>
	</div>
</div></div></div>



<script>
function fillInStory()
{
	
}
</script>
<h1>Anyone can contribute. Submit your favorite spot!</h1>
<p>
<div class="row">
	<div class="span9">
		<?=$this->form->create($story,array("class" => "well")); // Echoes a <form> tag and binds the helper to $post ?>
		
			<label>Story Title</label>
			<?=$this->form->text('title'); // Echoes an <input /> element, pre-filled with $post's title ?>
		
		
			<label>Story Description</label>
			<?=$this->form->textarea('desc',array("class" => "span8")); // Echoes an <input /> element, pre-filled with $post's title ?>
		
		
		
			<label>Address</label>
			<?=$this->form->textarea('address'); // Echoes an <input /> element, pre-filled with $post's title ?>
		
		
		<label>Photo</label>
		<?php if(!$image->exists()) { ?>
			
		        <?=$this->form->file('file', array('type' => 'file')); ?>
		    <?php }else { ?>
		    	<img src=<?=$image->url ?> />
		    	<?php } ?>
		
		
		
		<label>Save your post to edit later or submit it to be approved by our editors.</label>
			<div class="span8">
			<?=$this->form->submit('Save to edit later',array("class" => "btn-info")); ?>
			<?=$this->form->submit('Submit to our editors',array("class" => "btn-success")); ?>
			</div>
			<div class="span2">
			<?php if($story->_id) { ?>
				<input type="button" value="Delete" class="btn-danger" onClick="parent.location='/story/delete/<?=$story->_id ?>'" />
			<?php } ?>
			</div>
		<div class="row"></div>
		<?=$this->form->end(); // Echoes a </form> tag & unbinds the form ?>
	</div>
	<div class="span2 well">
		<h2>Your stories</h2>
		<hr>
		<h4> Still Editing </h4>
		this is<p>
		the <p>
		list <p>
		<hr>
		<h4> Pending... </h4>
		of your <p>
		other stories
		<hr>
		<h4> Accepted </h4>
		<p><a href="/story/edit">Create a New Story</a>
	</div>
</div>
<div class="row">
	<div id="info_status" class="alert alert-info"> Status info back from the server goes here
	</div>
</div>
<div class="row">
	<div id="error_status" class="alert alert-error"> Status info back from the server goes here
	</div>
</div>






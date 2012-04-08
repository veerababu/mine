
<script>
$(document).ready(function(){
	$.post("/users/getBySlug/<?=$userSlug ?>", null , onStory , "json" );
});

function onStory(data)
{
	onServer(data);
	
	if(data.story)
	{
		storyStr=createStoryStr(data.story);
		$('#story').html(storyStr);
	}
}

function clickTag(filter)
{ 
	window.location.href = '/stories?tags[]='+filter;
}

</script>

<div class="row"> 
	<?php  echo $this->_render('element', 'cta_nav'); ?>
	
	

	<div class="span9">
		<div id="story"></div>
	</div>
</div>

	
<div class="row">
	<div id="status" class="alert alert-info"></div>
</div>
<div class="row">
	<div id="error" class="alert alert-error"></div>
</div>


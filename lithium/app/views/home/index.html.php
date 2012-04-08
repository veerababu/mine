<script>
$(document).ready(function(){		
	fetchStories(); // we should call periodically
});

function fetchStories()
{
	$.post("/home/fetch", null , onStories , "json" );
}

function onStories(data)
{
	onServer(data);
	
	if(data.stories)
	{	
		for(var index = 0, len = data.stories.length; index < len; ++index) 
		{
			addStory(data.stories[index],index);
		}
	}
}

function addStory(story,index)
{	
	if(index<9)
	{
		var storyStr=createStoryThumbStr(story);
		$('#Thumb'+index).html(storyStr);
	}
}

</script>

<div class="row"> 
	<?php  echo $this->_render('element', 'cta_nav'); ?>
	
	

	<div class="span9">
		<div class="row">
			<div id="Thumb0" class="span3 thumb"></div>
			<div id="Thumb1" class="span3 thumb"></div>
			<div id="Thumb2" class="span3 thumb"></div>
		</div>
		<div class="row">
			<div id="Thumb3" class="span3 thumb"></div>
			<div id="Thumb4" class="span3 thumb"></div>
			<div id="Thumb5" class="span3 thumb"></div>
		</div>
		<div class="row">
			<div id="Thumb6" class="span3 thumb"></div>
			<div id="Thumb7" class="span3 thumb"></div>
			<div id="Thumb8" class="span3 thumb"></div>
		</div>
	</div>
</div>



<div class="row">
	<div id="status" class="alert alert-info"></div>
</div>
<div class="row">
	<div id="error" class="alert alert-error"></div>
</div>                                                                                                                                                                                                                                                                                                                                                                                                                                                        
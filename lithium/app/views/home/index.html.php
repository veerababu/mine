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
	if(index<6)
	{
		var storyStr=createStoryThumbStr(story);
		$('#Thumb'+index).html(storyStr);
	}
}

</script>

<div class="row"> 
	<div class="span2">
		<div class="row well">
			<h2>BYC!</h2>
			<h2>Local Stories by Global Users</h2>
			<a href="/stories/edit">Get started and share your story!</a>
		</div>
		<div class="row well">
			<h3>Top Ten:</h3>
			<ol id="TopTen">
				<li>a</li>
				<li>a</li>
				<li>a</li>
				<li>a</li>
			</ol>
		</div>
	</div>
	
	

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
	</div>
</div>



<div class="row">
	<div id="status" class="alert alert-info"></div>
</div>
<div class="row">
	<div id="error" class="alert alert-error"></div>
</div>                                                                                                                                                                                                                                                                                                                                                                                                                                                        
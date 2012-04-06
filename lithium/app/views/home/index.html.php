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
		<div class="row well redWell">
			<a href="/story/edit"><img src="/img/cta.png" /></a>
		</div>
		<div class="row well black">
			<h3>Cities</h3>
			<a href="/stories?tags[]=San Francisco">San Francisco</a><br>
			<a href="/stories?tags[]=Berkeley">Berkeley</a><br>
			<a href="/stories?tags[]=New York">New York</a><br>
			<a href="/stories?tags[]=Oakland">Oakland</a><br>
			<a href="/stories?tags[]=Yontville">Yontville</a><br>
			<a href="/stories?tags[]=Nosara">Nosara, Costa Rica</a><br>
			<p>
			<p>
			<hr>
			<h3>Categories</h3>
			<a href="/stories?tags[]=food">Food</a><br>
			<a href="/stories?tags[]=outdoors">Outdoors</a><br>
			<a href="/stories?tags[]=kids">Kids</a><br>
			<a href="/stories?tags[]=musings">Musings</a><br>
			<a href="/stories?tags[]=morsels">Morsels</a><br>
			
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
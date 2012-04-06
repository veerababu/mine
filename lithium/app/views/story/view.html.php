
<script>
$(document).ready(function(){
	$.post("/story/getByTitle/<?=$storyTitle ?>", null , onStory , "json" );
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
	<div class="span2">
		<div class="row well black">
			<h2>BYC!</h2>
			<h2>Local Stories by Global Users</h2>
			<a href="/stories/edit">Get started and share your story!</a>
		</div>
		<div class="row well black">
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
		<div id="story"></div>
	</div>
</div>


<?php if($admin){ ?>
	<a href="/admin/stories/<?=$storyTitle ?>">edit this story</a>
<?php } ?>
	
<div class="row">
	<div id="status" class="alert alert-info"></div>
</div>
<div class="row">
	<div id="error" class="alert alert-error"></div>
</div>

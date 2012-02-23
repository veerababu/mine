
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

</script>

<div id="story"></div>

<div class="row">
	<div id="status" class="alert alert-info"></div>
</div>
<div class="row">
	<div id="error" class="alert alert-error"></div>
</div>

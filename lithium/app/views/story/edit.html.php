
<script type="text/javascript" src="/js/markitup/jquery.markitup.js"></script>
<script type="text/javascript" src="/js/markitup/sets/bbcode/set.js"></script>
<script type="text/javascript" src="/js/jcrop/jquery.jcrop.js"></script>
<link rel="stylesheet" type="text/css" href="/js/markitup/skins/simple/style.css" />
<link rel="stylesheet" type="text/css" href="/js/markitup/sets/bbcode/style.css" />
<link href="/css/jcrop/jcrop.css?1" rel="stylesheet" type="text/css"  />
<script src="/js/edit.js?14" type="text/javascript"></script>
<script src="/js/crop.js?9" type="text/javascript" ></script>

<style>

</style>
      
<script>


$(document).ready(function(){
	// fetch all the stories you have written
	$.post("/stories/user", null , onStories , "json" );
	
	$('#thumbnailEdit').hide();
	
});



function saveStory()
{
	uploadImages();
	if(edit.imagesSaving>0)
	{
		edit.userWantsSave=true;
		$('#status').text="Uploading Images first. ";
	}else
	{
		edit.userWantsSave=false;
		$('#status').text="Saving...";
		$('#error').text="";
		
			
		$.post("/story/save", createStoryPostStr() , onStories , "json" );
	}
	
	return(false);
}

function publishStory()
{
	uploadImages();
	if(edit.imagesSaving>0)
	{
		edit.userWantsPublish=true;
		$('#status').text="Uploading Images first. ";
	}else
	{
		$('#status').text="Publishing...";
		$('#error').text="";
			
		$.post("/story/publish", createStoryPostStr() , onStories , "json" );
	}
	return(false);
}

function changeStory(storyID)
{
	// TODO: check if we have unsaved changes
	
	$('#status').text="Loading...";
	$('#error').text="";
		
	$.post("/story/get/"+storyID, null , onStories , "json" );
	
	return(false);
}


function onStories(data)
{
	onServer(data);
	
	if(data.story)
	{
		// change the data of the current story
		updateForm(data.story);
		updatePreview(data.story);
	}else updatePreview(data.story);
	
	// fill out the stories you have already written
	
	if(data.stories)
	{
		$('#storyStill').empty();
		$('#storyPending').empty();
		$('#storyAccepted').empty();
		
		for(var index = 0, len = data.stories.length; index < len; ++index) 
		{
			addStory(data.stories[index]);
		}
	}
	
}


var tempStr='<div><a onclick="changeStory(\'#{_id}\')">#{title}</a></div>';	
//var template=new Template(tempStr);
	
function addStory(story)
{	
	var storyStr=i4_tmpl(tempStr,story);
	if(story.status=='working')
	{
		$('#storyStill').append( storyStr );
	}else if(story.status=='pending')
	{
		$('#storyPending').append( storyStr );
	}else if(story.status=='accepted')
	{
		$('#storyAccepted').append( storyStr );
	}else 
	{
		addStatus("Unknown status: "+story.status);
	}
	
}

</script>
<h1>Put your City on the Map!</h1>
<p>
<div class="row">
	<div class="span9">
		<?=$this->form->create($story,array("class" => "well","id" => "form1" )); // Echoes a <form> tag and binds the helper to $post ?>
		
			<?php  echo $this->_render('element', 'editStory'); ?>
				
		<label>Editor Comments</label>
		<div class="alert alert-error" id="adminNote"></div>
		
		<hr>
		<label>Save your post to edit later or submit it to be approved by our editors.</label>
			<div class="span8">
			<input type="button" value="Save to edit later" class="btn-info" onClick="saveStory()" ?>
			<input type="button" value="Submit to our editors" class="btn-success" onClick="publishStory()" ?>
			
			</div>
			<div class="span2">
			<?php if($story->_id) { ?>
				<input type="button" value="Delete" class="btn-danger" onClick="parent.location='/story/delete/<?=$story->_id ?>'" />
			<?php } ?>
			</div>
		<div class="row"></div>
		<?=$this->form->end(); // Echoes a </form> tag & unbinds the form ?>
	</div>
	<div class="span2 well black">
		<h2>Your stories</h2>
		<hr>
		<h4> Still Editing </h4>
		<div id="storyStill" ></div>
		<hr>
		<h4> Pending... </h4>
		<div id="storyPending" ></div>
		<hr>
		<h4> Accepted </h4>
		<div id="storyAccepted" ></div>
		<p><a href="/story/edit">Create a New Story</a>
	</div>
</div>



  

<div class="row">
	<div id="status" class="alert alert-info"></div>
</div>
<div class="row">
	<div id="error" class="alert alert-error"></div>
</div>

<h1>Preview of your Story</h1>

<div id="preview" class="row"> 
</div>



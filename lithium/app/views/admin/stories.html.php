<script type="text/javascript" src="/js/markitup/jquery.markitup.js"></script>
<script type="text/javascript" src="/js/markitup/sets/bbcode/set.js"></script>
<link rel="stylesheet" type="text/css" href="/js/markitup/skins/simple/style.css" />
<link rel="stylesheet" type="text/css" href="/js/markitup/sets/bbcode/style.css" />
<link rel="stylesheet" type="text/css" href="/css/jcrop/jcrop.css?1" />
<script type="text/javascript" src="/js/jcrop/jquery.jcrop.js"></script>
<script src="/js/edit.js?12" type="text/javascript"></script>
<script src="/js/crop.js?6" type="text/javascript" ></script>

<script>

$(document).ready(function(){
	
	// fetch all the stories you have written
	$.post("/admin/getPending", null , onStories , "json" );
	if('<?=$storyTitle ?>'!='') $.post("/story/getByTitle/<?=$storyTitle ?>", null , onStories , "json" );
});

function saveStory(){ approve(); }

function approve()
{
	uploadImages();
	if(edit.imagesSaving>0)
	{
		edit.userWantsSave=true;
		$('#status').text="Uploading Images first. ";
	}else
	{
		edit.userWantsSave=false;
		
		$('#status').text="Approving...";
		$('#error').text="";
		$.post("/admin/approve", $('#form1').serialize() , onServer , "json" );
	}
	
	return(false);
}


function reject()
{
	$('#status').text="Rejecting...";
	$('#error').text="";
	$.post("/admin/reject", $('#form1').serialize() , onServer , "json" );
}

function deleteStory()
{
	
	$('#status').text="Deleting...";
	$('#error').text="";
	$.post("/admin/delete", $('#form1').serialize() , onServer , "json" );
}


function onStories(data)
{
	onServer(data);
	
	if(data.story)
	{
		// change the data of the current story
		updateForm(data.story);
		updatePreview(data.story);
	}
	
	// fill out the stories you have already written
	if(data.stories)
	{
		$('#pending').empty();
		var tempStr='<div><a onclick="changeStory(\'#{_id}\')">#{title}</a></div>';	
		
		for(var index = 0, len = data.stories.length; index < len; ++index) 
		{
			var storyStr=i4_tmpl(tempStr,data.stories[index]);
			$('#pending').append( storyStr );
		}
	}
}

</script>
<h1>Pending Story Admin</h1>
<p>
<div class="row">
	<div class="span9">
		<form id="form1" class="well">
			<?php  echo $this->_render('element', 'editStory'); ?>
		
		<hr>
		<label>Tell user why you are rejecting them</label>
			<div class="span8">
				<textarea id="adminNote" class="span8" name="adminNote"></textarea>
				<input type="button" value="Approve" class="btn-success" onClick="approve()" />
				<input type="button" value="Reject" class="btn-warning" onClick="reject()" />
				<input type="button" value="Delete" class="btn-danger" onClick="deleteStory()" />
			</div>
			
		<div class="row"></div>
		</form>
	</div>
	<div class="span2 well black">
		<h2>Stories</h2>
		<div id="pending" ></div>
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




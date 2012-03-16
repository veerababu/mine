<link href="/css/fileuploader.css" rel="stylesheet" type="text/css">	
<script src="/js/fileuploader.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/markitup/jquery.markitup.js"></script>
<script type="text/javascript" src="/js/markitup/sets/bbcode/set.js"></script>
<link rel="stylesheet" type="text/css" href="/js/markitup/skins/simple/style.css" />
<link rel="stylesheet" type="text/css" href="/js/markitup/sets/bbcode/style.css" />
<script src="/js/edit.js?8" type="text/javascript"></script>

<script>


$(document).ready(function()
{
	$.post("/users/fetchSelf", null , onSelf , "json" );
	
});



function changeProfile()
{
	$('#status').text="Saving...";
	$('#error').text="";
		
	$.post("/users/save", $('#form1').serialize() , onSelf , "json" );
	
	return(false);
}

function changePassword()
{
	$('#status').text="Publishing...";
	$('#error').text="";
		
	$.post("/users/changePass", $('#form1').serialize() , onStories , "json" );
	
	return(false);
}



function onSelf(data)
{
	onServer(data);
	
	if(data.user)
	{
		// change the data of the current story
		updateForm(data.user);
		updatePreview(data.user);
	}
}


</script>
<h1>Your Profile</h1>
<p>
<div class="row">
	<div class="span9">
		<form id="form1" class="well" >
		
			<?php  echo $this->_render('element', 'editStory'); ?>
			
		
		
		
		<hr>
		<label>Save your post to edit later or submit it to be approved by our editors.</label>
			<div class="span8">
			<input type="button" value="Save Changes" class="btn-info" onClick="changeProfile()" ?>
			
			</div>
		<div class="row"></div>
		</form>
	</div>
	<div class="span2 well">
		What do we want to put here?
	</div>
</div>
<div class="row">
	<div id="status" class="alert alert-info"></div>
</div>
<div class="row">
	<div id="error" class="alert alert-error"></div>
</div>

<h1>Preview of your Profile</h1>

<div id="preview" class="row"> 
</div>




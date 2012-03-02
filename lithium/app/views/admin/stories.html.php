<link href="/css/fileuploader.css" rel="stylesheet" type="text/css">	
<script src="/js/fileuploader.js" type="text/javascript"></script>
<script src="/js/edit.js?1" type="text/javascript"></script>
<script type="text/javascript" src="/js/markitup/jquery.markitup.js"></script>
<script type="text/javascript" src="/js/markitup/sets/bbcode/set.js"></script>
<link rel="stylesheet" type="text/css" href="/js/markitup/skins/simple/style.css" />
<link rel="stylesheet" type="text/css" href="/js/markitup/sets/bbcode/style.css" />

<script>

$(document).ready(function(){
	
            var uploader = new qq.FileUploader({
                element: document.getElementById('image-uploader'),
                action: '/story/addImage',
                allowedExtensions: ['jpg','jpeg','png','gif'],
                onComplete: imageUploaded,
                template: '<div class="qq-uploader">' + 
	                '<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' +
	                '<div id="uploadButton" class="qq-upload-button">Upload an Image</div>' +              
	             	'</div>',
            
                debug: true
            });           
      
	 $("#StoryText").markItUp(mySettings);
	 
	// fetch all the stories you have written
	$.post("/admin/getPending", null , onStories , "json" );
});

function approve()
{
	$('#status').text="Approving...";
	$('#error').text="";
	$.post("/admin/approve", $('#form1').serialize() , onServer , "json" );
	
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
		<input type="hidden" name="_id" id="StoryID" value="" />
		
			<label>Story Title</label>
			<input id="StoryTitle" type="text" name="title">
			
		
		
			<label>Text of Story</label>
			<textarea id="StoryText" class="span8" name="text"></textarea>
		
		
		
			<label>Address</label>
			<textarea id="StoryAddress" name="address"></textarea>
		
		
		<label>Photos</label>
		
		<div id="image-uploader">
			
		</div>
		<div id="photoList"></div>
		
		
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
	<div class="span2 well">
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




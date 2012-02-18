<link href="/css/fileuploader.css" rel="stylesheet" type="text/css">	
<script src="/js/fileuploader.js" type="text/javascript"></script>


<script>
var currentStoryID=<?=$story->_id ?>;
var photos=[false,false,false,false,false];  // bool array of if this slot has an image in it or not

$(document).ready(function(){

	        $('#Hello').show();
	        
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
      
	
	// fetch all the stories you have written
	$.post("/stories/user", null , onStories , "json" );
});

function getFreePhotoSlot()
{
	for(n=0; n<5; n++)
	{
		if(!photos[n]) return(n);
	}
	return(-1);
}

function imageUploaded(id, fileName, data)
{
	onServer(data);
	
	var photoID=data.photoID;
	var photoIndex=getFreePhotoSlot();
	if(photoID && photoIndex>-1)
	{
		addImage(photoIndex,photoID,"");	            
		
		if(getFreePhotoSlot()==-1)
		{
			$('#uploadButton').hide();
		}
		
		updatePreview();
	}
}

function addImage(photoIndex,photoID,caption)
{
	photos[photoIndex]=true;
		
	var photoStr='<div id="div'+photoIndex+'"><img src="/image/view/'+photoID+'.jpg" />' +
	             'Caption: <input name="caption'+photoIndex+'" type="text" value="'+caption+'" /><input type="button" value="Remove this Image" class="btn-danger" onClick=deleteImage("'+photoIndex+'") />' +
	             ' <input type="hidden" name="photo'+photoIndex+'" value="'+photoID+'" /></div>';
	            
	$('#photoList').append(photoStr);
}



function saveStory()
{
	$('#status').text="Saving...";
	$('#error').text="";
		
	$.post("/story/save", $('#form1').serialize() , onStories , "json" );
	
	return(false);
}

function publishStory()
{
	$('#status').text="Publishing...";
	$('#error').text="";
		
	$.post("/story/publish", $('#form1').serialize() , onStories , "json" );
	
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
		updatePreview();
	}
	
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

function updateForm(data)
{
	// clear all the photos
	// see if we should show the phot upload button
	$('#StoryID').val(data._id);
	$('#StoryTitle').val(data.title);
	$('#StoryText').val(data.text);
	$('#StoryAddress').val(data.address);
	
	$('#photoList').empty();
	
	for(n=0; n<5; n++) 
	{
		if(data['photo'+n])
		{
			addImage(n,data['photo'+n],data['caption'+n]);
		}else photos[n]=false;
	}
	
	if(getFreePhotoSlot()==-1)
	{
		$('#uploadButton').hide();
	}else $('#uploadButton').show();
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


function updatePreview()
{
}

// remove the image div from the form
// We don't need to tell the server since all this will be set when we save
function deleteImage(slotID)
{
	$('#div'+slotID).remove();
	updatePreview();
	
	if(getFreePhotoSlot()==-1)
	{
		$('#uploadButton').show();
	}
	photos[slotID]=false;
}


</script>
<h1>Anyone can contribute. Submit your favorite spot!</h1>
<p>
<div class="row">
	<div class="span9">
		<?=$this->form->create($story,array("class" => "well","id" => "form1" )); // Echoes a <form> tag and binds the helper to $post ?>
		<input type="hidden" name="_id" id="StoryID" value="<?=$story->_id ?>'" />
		
			<label>Story Title</label>
			<?=$this->form->text('title'); // Echoes an <input /> element, pre-filled with $post's title ?>
			
		
		
			<label>Text of Story</label>
			<?=$this->form->textarea('text',array("class" => "span8")); // Echoes an <input /> element, pre-filled with $post's title ?>
		
		
		
			<label>Address</label>
			<?=$this->form->textarea('address'); // Echoes an <input /> element, pre-filled with $post's title ?>
		
		
		<label>Photos</label>
		
		<div id="image-uploader">
			
		</div>
		<div id="photoList"></div>
		
		
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
	<div class="span2 well">
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

<div id="Hello" /> 




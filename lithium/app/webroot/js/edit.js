

var photos=[false,false,false,false,false];  // bool array of if this slot has an image in it or not

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
		
	var photoStr='<div id="div'+photoIndex+'"><img src="/image/view/'+photoID+'" />' +
	             'Caption: <input id="caption'+photoIndex+'" name="caption'+photoIndex+'" type="text" value="'+caption+'" /><input type="button" value="Remove this Image" class="btn-danger" onClick=deleteImage("'+photoIndex+'") />' +
	             ' <input type="hidden" id="photo'+photoIndex+'" name="photo'+photoIndex+'" value="'+photoID+'" /></div>';
	            
	$('#photoList').append(photoStr);
}

function changeStory(storyID)
{
	// TODO: check if we have unsaved changes
	
	$('#status').text="Loading...";
	$('#error').text="";
		
	$.post("/story/get/"+storyID, null , onStories , "json" );
	
	return(false);
}


function updatePreview(story)
{
	//alert(story);
	
	if(!story)
	{
		story=[];
		story.title=$('#StoryTitle').val();
		story.author='<?=$username ?>';
		story.text= $('#StoryText').val();
		story.address=$('#StoryAddress').val();
		story.updated="today"
		
		for(n=0; n<5; n++) 
		{
			if(photos[n])
			{
				var pStr='photo'+n;
				story[pStr]=$('#photo'+n).val();
				story['caption'+n]=$('#caption'+n).val();
				//story['pos'+n]=
			}
		}
	}
	
	storyStr=createStoryStr(story);
	//alert(storyStr);
	$('#preview').empty();
	$('#preview').html(storyStr);
	
}

function updateForm(data)
{
	// clear all the photos
	// see if we should show the phot upload button
	$('#StoryID').val(data._id);
	$('#StoryTitle').val(data.title);
	$('#StoryText').val(data.text);
	$('#StoryAddress').val(data.address);
	$('#adminNote').html(data.adminNote);
	
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
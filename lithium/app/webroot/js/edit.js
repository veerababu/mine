

var photos=[false,false,false,false,false];  // bool array of if this slot has an image in it or not
var availableTags = [ ];

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
	 
	 // fetch all the tags
	$.post("/tags/get", null , onTags , "json" );
	
		
		
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}

		$( "#StoryTags" )
			// don't navigate away from the field on tab when selecting an item
			.bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
						$( this ).data( "autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			})
			.autocomplete({
				minLength: 0,
				source: function( request, response ) {
					// delegate back to autocomplete, but extract the last term
					response( $.ui.autocomplete.filter(
						availableTags, extractLast( request.term ) ) );
				},
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {
					var terms = split( this.value );
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					terms.push( "" );
					this.value = terms.join( ", " );
					return false;
				}
			});
});


function onTags(data)
{
	onServer(data);
	if(data.tags)
	{
		availableTags=data.tags;
	}
}



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
		story.phone=$('#StoryPhone').val();
		story.url=$('#StoryURL').val();
		story.city=$('#StoryCity').val();
		story.hood=$('#StoryHood').val();
		
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
	$('#StoryAuthor').val(data.author);
	$('#StoryText').val(data.text);
	$('#StoryAddress').val(data.address);
	$('#StoryCity').val(data.city);
	$('#StoryHood').val(data.hood);
	$('#StoryPhone').val(data.phone);
	$('#StoryURL').val(data.url);
	$('#adminNote').html(data.adminNote);
	
	var tagStr='';
	for(n=0; n<data.tags.length; n++) tagStr += data.tags[n]+', ';
	$('#StoryTags').val(tagStr);
	
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
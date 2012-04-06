
var edit=[];
edit.photos=[];
edit.imagesSaving=0;
edit.userWantsSave=false;
edit.userWantsPublish=false;
edit.availableTags = [ ];


$(document).ready(function(){
	
	for(var n=0; n<5; n++)
    	edit.photos[n]={ filled: false, updated: false};
      
	 $("#StoryText").bind( "keydown", function( event ) {
				if((event.keyCode === $.ui.keyCode.SPACE) ||
					(event.keyCode === $.ui.keyCode.DELETE)) updateWordCount();
			}).markItUp(mySettings);
	 
	 // fetch all the tags
	$.post("/tags/get", null , onTags , "json" );
	
		
		
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}

		$( "#StoryTags" )
			// dont navigate away from the field on tab when selecting an item
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
						edit.availableTags, extractLast( request.term ) ) );
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
		edit.availableTags=data.tags;
	}
}



function getFreePhotoSlot()
{
	for(n=0; n<5; n++)
	{
		if(!edit.photos[n].filled) return(n);
	}
	return(-1);
}




function addExistingImage(photoIndex,photoID,caption)
{
	edit.photos[photoIndex].filled=true;
		
	srcStr='src="/image/view/'+photoID+'"';
	
	var photoStr='<div class="photoEditBox" id="div'+photoIndex+'">' +
					'<input type="hidden" id="photo'+photoIndex+'" name="photo'+photoIndex+'" value="'+photoID+'"  />' +
					'<div class="row" id="thumbDiv'+photoIndex+'" >'+
						'<div class="span3">'+
							'<img '+srcStr+' class="thumbImage" />' +
						'</div>'+
						'<div class="span5"><div class="row">'+
		             			'Caption: <input id="caption'+photoIndex+'" name="caption'+photoIndex+'" type="text" />' +
		             		'</div><div class="row pebButtons">'+
			          			'<div class="span2 offset3"><input type="button" value="Remove this Image" class="btn-danger" onClick=deleteImage("'+photoIndex+'") /></div>' +
		          		'</div></div>'+
		          	'</div>' +		
	             '</div>';
	
	            
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
		story.state=$('#StoryState').val();
		story.country=$('#StoryCountry').val();
		story.tags=$('#StoryTags').val().split(",");
		if( $('#StoryLayout').is(':checked') ) story.layout=1;
		else story.layout=0;
		
		story.updated=Math.round(+new Date()/1000);
		
		for(n=0; n<5; n++) 
		{
			if(edit.photos[n].filled)
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
	$('#StoryState').val(data.state);
	$('#StoryCountry').val(data.country);
	$('#StoryHood').val(data.hood);
	$('#StoryPhone').val(data.phone);
	$('#StoryURL').val(data.url);
	$('#adminNote').html(data.adminNote);
	$('#StoryStatus').val(data.status);
	if(data.layout)	$('#StoryLayout').attr('checked', true);
	else $('#StoryLayout').attr('checked', false);

	
	var tagStr='';
	if(data.tags) for(n=0; n<data.tags.length; n++) tagStr += data.tags[n]+', ';
	$('#StoryTags').val(tagStr);
	
	$('#photoList').empty();
	
	for(n=0; n<5; n++) 
	{
		if(data['photo'+n])
		{
			addExistingImage(n,data['photo'+n],data['caption'+n]);
		}else edit.photos[n].filled=false;
	}
	
	if(getFreePhotoSlot()==-1)
	{
		$('#imageDrop').hide();
	}else $('#imageDrop').show();
	
	updateWordCount();
}


function updateWordCount()
{
	var str='';
	
	var fullStr = $('#StoryText').val() + " ";
	var initial_whitespace_rExp = /^[^A-Za-z0-9]+/gi;
	var left_trimmedStr = fullStr.replace(initial_whitespace_rExp, "");
	var non_alphanumerics_rExp = rExp = /[^A-Za-z0-9]+/gi;
	var cleanedStr = left_trimmedStr.replace(non_alphanumerics_rExp, " ");
	var splitString = cleanedStr.split(" ");
	var wordCount = splitString.length -1;
	
	if(wordCount > 500)
	{
		str='<span class="wordLimitAlert">Over 500 word limit. Words: '+wordCount+'</span>';
	}else str='words: '+wordCount;
	
	$('#WordCount').html(str);
}

// remove the image div from the form
// We don't need to tell the server since all this will be set when we save
function deleteImage(slotID)
{
	$('#div'+slotID).remove();
	updatePreview();
	
	if(getFreePhotoSlot()>=-1)
	{
		$('#imageDrop').show();
	}
	edit.photos[slotID].filled=false;
}
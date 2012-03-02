<link href="/css/fileuploader.css" rel="stylesheet" type="text/css">	
<script src="/js/fileuploader.js" type="text/javascript"></script>
<script src="/js/edit.js?1" type="text/javascript"></script>
<script type="text/javascript" src="/js/markitup/jquery.markitup.js"></script>
<script type="text/javascript" src="/js/markitup/sets/bbcode/set.js"></script>
<link rel="stylesheet" type="text/css" href="/js/markitup/skins/simple/style.css" />
<link rel="stylesheet" type="text/css" href="/js/markitup/sets/bbcode/style.css" />

<script>
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
	
	// fetch all the stories you have written
	$.post("/stories/user", null , onStories , "json" );
	
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
		updatePreview(data.story);
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
		<input type="hidden" name="_id" id="StoryID" value="<?=$story->_id ?>" />
		
			<label>Story Title</label>
			<?=$this->form->text('title'); // Echoes an <input /> element, pre-filled with $post's title ?>
			
		
		
			<label>Text of Story</label>
			<?=$this->form->textarea('text',array("class" => "span8")); // Echoes an <input /> element, pre-filled with $post's title ?>
		
		
		
			<label>Address</label>
			<div class="input-prepend">
		      <span class="add-on"><i class="icon-home"></i></span>
		      <input id="address" name="address" type="text">
		    </div>
			
			<label>City</label>
			<?=$this->form->text('city'); ?>
			
			<label>Neigborhood</label>
			<?=$this->form->text('hood'); ?>
			
			<label>Tags (food, kids, outdoors, art, etc... )</label>
			<?=$this->form->text('tags',array("class" => "span8")); ?>
		
		
		<label>Photos</label>
		
		<div id="image-uploader">
			
		</div>
		<div id="photoList"></div>
		
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

<div id="preview" class="row"> 
</div>




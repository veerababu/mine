<SCRIPT>
function approve(storyID)
{
	$('#status').text="Approving...";
	$('#error').text="";
	$.post("/admin/approve", storyID , onServer , "json" );
	
	return(false);
}


function reject(storyID)
{
	need to make a form to send back to the server
	$('#status').text="Rejecting...";
	$('#error').text="";
	$.post("/admin/reject", storyID , onServer , "json" );
}

function delete(storyID)
{
	
	$('#status').text="Deleting...";
	$('#error').text="";
	$.post("/admin/delete", storyID , onServer , "json" );
}

</script>

<h1>Stories waiting approval</h1>
<div class="row"> 	
	<?php foreach ($stories as $story): ?>
	
	
		<div class="row">
		<?=$this->html->image("/image/view/{$story->_id}.jpg", array('width'=> 100)); ?>
		<?=$this->html->link($story->title, array('Image::view', 'id' => "{$story->_id}")); ?>
		</div>
		<div class="row">
			<textarea id="r<?=$story->_id ?>" class="span8" name="reason">Tell them why they were rejected.</textarea>
			<input type="button" value="Approve" class="btn-success" onClick="approve('<?=$story->_id ?>')" />
			<input type="button" value="Reject" class="btn-warning" onClick="reject('<?=$story->_id ?>')" />
			<input type="button" value="Delete" class="btn-danger" onClick="delete('<?=$story->_id ?>')" />
			</form>
		</div>
	
	<?php endforeach ?>
	
	
</div>
<div class="row">
	<div id="status" class="alert alert-info"></div>
</div>
<div class="row">
	<div id="error" class="alert alert-error"></div>
</div>



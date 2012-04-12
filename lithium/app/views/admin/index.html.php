
<a href="/admin/stories">Pending Strories</a>
<p>
<a href="/admin/stories">Edit </a>

<h1>Number Users: <?=$userCount ?></h1>
<h1>Number Stories: <?=$storyCount ?></h1>
<?php
if(mt_rand(0,1)){ 
?>
<blink><font color="red">Alert: Going Viral!</font></blink>
<?php } ?>

<div class="row">
	<div id="status" class="alert alert-info"></div>
</div>
<div class="row">
	<div id="error" class="alert alert-error"></div>
</div>

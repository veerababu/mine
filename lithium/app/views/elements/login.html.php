<?php if(isset($username)) { ?>
	 <a class="brand" style="float: right;" href="/logout">logout</a>
	 <a class="brand" style="float: right;" href="/users/profile/<?=$username ?>"><?=$username ?></a>
<?php }else{ ?>
	<a class="brand" style="float: right;" href="/login">Login</a>
<?php } ?>
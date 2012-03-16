<?php if(isset($username)) { ?>
	<a href="/users/profile"><?=$username ?></a>
	 <a href="/logout">logout</a>
<?php }else{ ?>
	<a  href="/login">Login</a>
	<a  href="/register">Create an account</a>
<?php } ?>
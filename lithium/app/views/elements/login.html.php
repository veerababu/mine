<?php if(isset($username)) { ?>
	 <a href="/logout">logout</a>
	 <a href="/users/profile/<?=$username ?>"><?=$username ?></a>
<?php }else{ ?>
	<a  href="/login">Login</a>
<?php } ?>
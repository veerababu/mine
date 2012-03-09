<?php

?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>Bravo Your City > <?= $title ?></title>
	 <link href="/css/bootstrap.css" rel="stylesheet">
	  <link href="/css/bravo.css?4" rel="stylesheet">
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">
     <link href="/css/blitzer/jquery-ui.css" rel="stylesheet">
    
    <script src="/js/jquery.js"></script>
    <script src="/js/jquery-ui.js"></script>
    <script src="/js/bootstrap.js"></script>
    <script src="/js/bravo.js?4"></script>
    
	
	
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body data-target=".subnav" data-spy="scroll" data-rendering="true">
    
<?php
$session_flash_message = $this->session->message();
 
if($session_flash_message) 
{
  ?>
<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	var statusMsg='<?= $session_flash_message ?>';
	$('#status').html(statusMsg);
});

</script>
  <?php
}
?>     
          
<div class="navbar navbar-fixed-top">
     
        <div class="nav-menu">
        	<div class="login">
          	 <?php  echo $this->_render('element', 'login'); ?>
          	 </div>
	        <table width="100%" ><tr>
		        <td valign="bottom" style="padding-bottom: 10px;" width="40%">
		         <span class="left">
			          <a style="padding-left: 0px;" href="/stories">STORIES</a>
			          <a href="/stories/feed">FEED</a>
			          <a href="/story/edit">SUBMIT!</a>
			          
			      </span>
		        </td>
		        <td>
		        	<a href="/home"><img class="logo" src="/img/bravo_logo.png" /></a>
		        </td>
		        <td valign="bottom" style="padding-bottom: 10px;" width="40%" >
		         <span class="right">
		         	<a href="/pages/faq">FAQ</a>
			          <a href="/pages/about">ABOUT</a>
			          <a href="/pages/50">50%</a>
		          </span>
		        </td>
	        </tr></table>
	 		
        </div>
    
    </div>

    <div class="container">
	
	<?php echo $this->content(); ?>
     
	<div class="footer">
		<span><a href="/pages/contact">CONTACT</a></span> | <span>Bravo Your Life! LLC</span> | <span><a href="/pages/privacy">PRIVACY</a></span> | <span><a href="http://lesswrong.com">Think</a> about things.</span>
	</div>

    </div> 

</body>
</html>

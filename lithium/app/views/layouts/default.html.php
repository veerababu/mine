<?php

?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>Bravo Your City > <?php echo($title); ?></title>
	 <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">
     <link href="/css/blitzer/jquery-ui.css" rel="stylesheet">
    
    
    <script src="/js/jquery.js"></script>
    <script src="/js/jquery-ui.js"></script>
    <script src="/js/bootstrap.js"></script>
    
	<link href="/css/bravo.css?15" rel="stylesheet">
    <script src="/js/date.format.js?7"></script>
    <script src="/js/bravo.js?16"></script>
    
	
	
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-29896614-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>
<body data-target=".subnav" data-spy="scroll" data-rendering="true">
    
<?php
$session_flash_message = $this->session->message();
 
if($session_flash_message) 
{
  ?>
<script type="text/javascript" language="javascript">
startStatusMessage='<?= $session_flash_message ?>';
</script>
  <?php
}
?>     

<a href="/home"><img class="logo" src="/img/byc-logo.png" /></a>
<div class="row top-nav"><div class="login"><?php  echo $this->_render('element', 'login'); ?></div></div>
<div class="row nav-menu">
          <table width="100%" ><tr>
		        <td valign="bottom"  width="40%">
		         <span class="left">
			          <a style="padding-left: 0px;" href="/stories">STORIES</a>
			          <!-- <a href="/stories/feed">FEED</a> -->
			          <a href="/story/edit">SHARE</a>
			          
			      </span>
		        </td>
		        <td>
		        	
		        </td>
		        <td valign="bottom"  width="40%" >
		         <span class="right">
		         	<a href="/pages/faq">FAQ</a>
			          <a href="/pages/about">ABOUT</a>
			          <a href="/pages/50">50%</a>
		          </span>
		        </td>
	        </tr>
	    </table>
</div>

    <div class="container">
	
	<?php echo $this->content(); ?>
     
	<div class="footer">
		<span><a href="/pages/contact">CONTACT</a></span> | <span>Bravo Your Life! Inc</span> | <span><a href="/pages/privacy">PRIVACY</a></span> | <span><a href="http://lesswrong.com">Think</a> about things.</span>
	</div>

    </div> 

</body>
</html>

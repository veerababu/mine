<?php

?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>Bravo Your City > <?php echo $this->title(); ?></title>
	 <link href="/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">
    
	
	
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body data-offset="50" data-target=".subnav" data-spy="scroll" data-rendering="true">
         
          
<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
        
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          
          <a class="brand" href="/home">Bravo Your City!</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="/stories">Cities</a></li>
              <li><a href="/story/edit">Submit!</a></li>
              <li><a href="/about">About</a></li>
              <li><a href="/contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
          <a class="brand" style="float: right;" href="/login">Login</a>
        </div>
      </div>
    </div>

    <div class="container">

	<?php echo $this->content(); ?>
     

    </div> 
   	
		
	<script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.js"></script>
    <script src="/js/bravo.js"></script>
</body>
</html>

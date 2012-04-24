<script> 

var currentFilters =[ <?php echo( $tags ); ?> ];
var currentSearches=[ <?= $search ?> ];
var desiredPage=<?= $page ?>;

</script>
<script src="/js/search.js?5" type="text/javascript"></script>

<p>
<div class="row"> 
	<div class="span2 well black">
		Search For: <input id="SearchBox" class="span2 ui-autocomplete-input" type="text" name="search" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
		Near: <input id="NearBox" class="span2 ui-autocomplete-input" type="text" name="near" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
		<hr>
		<h4>Searching For:</h4>
		<div id="FilterList"></div>
		
		
		<hr>
		<h4>Refine your Search:</h4>
		<div id="CommonList" ></div>
		<hr>
		<p>
		<a onclick="clearFilters()" >New Search</a>
	</div>
	
	

	<div class="span9">
		<div id="storyList"></div>
		
		<div id="Pager" class="row">
			
		</div>
		
		
	
		<!-- <div class="row">
		Think we missed something? <?=$this->html->link('Put your City on the map!', 'Story::edit'); ?>
		</div> -->
	</div>
</div>

<div class="row">
	<div id="status" class="alert alert-info"></div>
</div>
<div class="row">
	<div id="error" class="alert alert-error"></div>
</div>



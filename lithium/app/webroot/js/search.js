var availableTags = [ ];
var searchOptions = ["hello","dog" ];
var locationOptions = [ ];

		
// 
	
$(document).ready(function(){	

	$( "#SearchBox" )
		.bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
						$( this ).data( "autocomplete" ).menu.active ) {
					event.preventDefault();
				}
				
				if((event.keyCode || event.which) == 13){ addSearch("#SearchBox"); }
				if((event.keyCode || event.which) == 9){ addSearch("#SearchBox"); }
				
			})
			.autocomplete({
				minLength: 0,
				source: searchOptions
			});
			
	$( "#NearBox" )
		.bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
						$( this ).data( "autocomplete" ).menu.active ) {
					event.preventDefault();
				}
				
				if((event.keyCode || event.which) == 13){ addSearch("#NearBox"); }
				if((event.keyCode || event.which) == 9){ addSearch("#NearBox"); }
				
			})
			.autocomplete({
				minLength: 0,
				source: locationOptions
			});	
	
	
	if(currentFilters.length)
		for(n=0; n<currentFilters.length; n++) addFilterDisplay(currentFilters[n]);
	else $('#FilterList').text("Everything!");
	
	fetchStories();
	
	// fetch all the tags
	$.post("/tags/get", null , onTags , "json" );
	$.post("/tags/getCommon", null , onCommon , "json" );
});

function addSearch(name)
{
	addFilter( $(name ).val() );
	$( name ).val('');
}

function onTags(data)
{
	onServer(data);
	if(data.tags)
	{
		availableTags=data.tags;
	}
}

function onCommon(data)
{
	onServer(data);
	if(data.tags)
	{
		var commonStr="";
		for(n = 0; n<data.tags.length;  n++) 
		{
			commonStr += '<a onclick="addFilter(\''+data.tags[n]+'\')"><i class="icon-arrow-up"></i>'+data.tags[n]+'</a><br>';
		}
		$('#CommonList').html(commonStr);
	}
}



function onStories(data)
{
	onServer(data);
	
	if(data.stories)
	{
		$('#storyList').empty();
			
		for(var index = 0, len = data.stories.length; index < len; ++index) 
		{
			addStory(data.stories[index]);
		}
		if(data.stories.length==0)
		{
			showEmptyResultsMessage();
		}
	}
	if(data.count)
	{
		//TODO: 15 pages max
		var numPages=Math.ceil(data.count/3);
		if(numPages>1)
		{
			var str='<ul class="pager"> <li id="FirstPageLI" class="previous"><a onClick="gotoPage(1)" >&larr; Start</a></li>';
			str += '<li class="next"><a id="LastPageButton" >End &rarr;</a></li></ul>';
			
			$('#Pager').html(str);
			
		
			var startPage=numPages;
			var minPage=0;
			if(numPages>15)
			{
				minPage=Math.max(data.page-7,0);
				startPage=Math.min(minPage+15,numPages);
				
				$('#FirstPageLI').after('...');
			}
			
			$('#Pager').show();
			
			for(n=startPage; n>minPage; n--)
			{
				if(n==data.page)
					pageStr='<li class="pageButton active"><a onClick="gotoPage('+n+')">'+n+'</a></li>';
				else
					pageStr='<li class="pageButton"><a onClick="gotoPage('+n+')">'+n+'</a></li>';
				$('#FirstPageLI').after(pageStr);
			}
			$('#LastPageButton').click( function(){ gotoPage(numPages); } );
		}else
			$('#Pager').hide();
		
	}else
	{
		$('#Pager').hide();
	}
}

function showEmptyResultsMessage()
{
	var msg='<p><p><p><div class="center bigText">Nothing was found because we are just starting out. <a href="/story/edit">Please share your story!</a> </div>';
	$('#storyList').html(msg);
}

function gotoPage(page)
{
	desiredPage=page;
	fetchStories();
}

function removeFilter(filter)
{
	currentFilters.splice(currentFilters.indexOf(filter), 1); 
	
	if(currentFilters.length==0) $('#FilterList').text("Everything!");
	
	fetchStories();
		
	$('#FilterList').children('div[name="'+filter+'"]').remove();
}

// the story tags 
function clickTag(filter){ addFilter(filter); }

function addFilter(filter)
{	
	// don't allow duplicates
	if(currentFilters.indexOf(filter) == -1)
	{
		addFilterDisplay(filter);
		currentFilters.push(filter);
		desiredPage=1;
		fetchStories();
	}else
	{ // flash the duplicate
		$('#FilterList').children('div[name="'+filter+'"]').addClass("highlight").removeClass("highlight", 750);
	}
}

function addFilterDisplay(filter)
{
	if(currentFilters.length==0) $('#FilterList').empty();
	var filterStr='<div class="highlight" name="'+filter+'"><a  onclick="removeFilter(\''+filter+'\')"><i class="icon-arrow-down"></i>'+filter+'</a><br></div>';	
	$('#FilterList').append(filterStr);
	$('#FilterList').children('div[name="'+filter+'"]').removeClass("highlight", 750);
}

function clearFilters()
{
	currentFilters = [];
	
	$('#FilterList').text("Everything!");
	
	$.post("/tags/getCommon", null , onCommon , "json" );
	fetchStories();
}




function fetchStories()
{
	var postData={};
	/*
	for(n = 0; n<currentFilters.length;  n++) 
	{
		postData['t'+n]=currentFilters[n];
	}*/
	
	
	postData['tags']=currentFilters;
	postData['search']=currentSearches;
	postData['page']=desiredPage;
	
	$.post("/stories/fetch", postData , onStories , "json" );
}


function addStory(story)
{	
	var storyStr=createStoryStr(story);
	$('#storyList').append(storyStr);
	
}


<script>

var availableTags = [ ];
			
	
$(document).ready(function(){
	
	
		
		$( "#tag" ).autocomplete({
			
			source: function( request, response ) {
					response( $.ui.autocomplete.filter(
						availableTags, request.term  ) );
				}
		});
	
	$.widget( "ui.combobox", {
			_create: function() {
				var self = this,
					select = this.element.hide(),
					selected = select.children( ":selected" ),
					value = selected.val() ? selected.text() : "";
				var input = this.input = $( "<input>" )
					.insertAfter( select )
					.val( value )
					.autocomplete({
						delay: 0,
						minLength: 0,
						source: function( request, response ) {
							var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
							response( select.children( "option" ).map(function() {
								var text = $( this ).text();
								if ( this.value && ( !request.term || matcher.test(text) ) )
									return {
										label: text.replace(
											new RegExp(
												"(?![^&;]+;)(?!<[^<>]*)(" +
												$.ui.autocomplete.escapeRegex(request.term) +
												")(?![^<>]*>)(?![^&;]+;)", "gi"
											), "<strong>$1</strong>" ),
										value: text,
										option: this
									};
							}) );
						},
						select: function( event, ui ) {
							ui.item.option.selected = true;
							self._trigger( "selected", event, {
								item: ui.item.option
							});
						},
						change: function( event, ui ) {
							if ( !ui.item ) {
								var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
									valid = false;
								select.children( "option" ).each(function() {
									if ( $( this ).text().match( matcher ) ) {
										this.selected = valid = true;
										return false;
									}
								});
								if ( !valid ) {
									// remove invalid value, as it didn't match anything
									$( this ).val( "" );
									select.val( "" );
									input.data( "autocomplete" ).term = "";
									return false;
								}
							}
						}
					})
					.addClass( "ui-widget ui-widget-content ui-corner-left" );

				input.data( "autocomplete" )._renderItem = function( ul, item ) {
					return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + item.label + "</a>" )
						.appendTo( ul );
				};

				this.button = $( "<button class='cityCombo' type='button'><i class='icon-chevron-down' /></button>" )
					.attr( "tabIndex", -1 )
					.attr( "title", "Show All Items" )
					.insertAfter( input )
					.button({
						icons: {
							primary: "ui-icon-triangle-1-s"
						},
						text: false
					})
					.removeClass( "ui-corner-all" )
					.addClass( "ui-corner-right ui-button-icon" )
					.click(function() {
						// close if already visible
						if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
							input.autocomplete( "close" );
							return;
						}

						// work around a bug (likely same cause as #5265)
						$( this ).blur();

						// pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
						input.focus();
					});
			},

			destroy: function() {
				this.input.remove();
				this.button.remove();
				this.element.show();
				$.Widget.prototype.destroy.call( this );
			}
		});
		
		$( "#cityCombo" ).combobox();
		$( "#hoodCombo" ).combobox();
	
	
	$.post("/stories/fetch", null , onStories , "json" );
	// fetch all the tags
	$.post("/tags/get", null , onTags , "json" );
});

function onTags(data)
{
	onServer(data);
	if(data.tags)
	{
		availableTags=data.tags;
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
	}
}

var filterList=[];
var tagChoices=[];

function clickTag(ele)
{
	var tagName=ele.innerHTML;
	//addFilter(tagName);
	
	if(removeByElement(filterList,tagName))
	{ // we were filtering this tag already
	
	}else
	{ // new tag
		filterList.push(tagName);
	}
	
	// draw filters and choices
	
	
	
	$.post("/stories/fetch", filterList , onStories , "json" );
	
}


function addStory(story)
{	
	var storyStr=createStoryStr(story);
	$('#storyList').append(storyStr);
	
}

</script>

<div class="row">
	<div class="span3" >
		<select id="cityCombo" >
			<option value="Any">Any City</option>
			<option value="San Francisco">San Francisco</option>
			<option value="Berkeley">Berkeley</option>
			<option value="Oakland">Oakland</option>
			<option value="Bay Area">Bay Area</option>
			<option value="New York">New York</option>
		</select> 
	</div>
	<div class="span3" >
		<select id="hoodCombo">
			<option value="Any">Any Neighboorhood</option>
		</select> 
	</div>
	<div class="span3 offset2" >
		Sort by:
		<select id="sortCombo">
			<option value="date">Date</option>
			<option value="rating">Quality</option>
		</select> 
	</div>
</div>
<p>
<div class="row"> 
	<div class="span2 well">
		<h4>Filtering by:</h4>
		<div id="filterList"> <a><i class="icon-arrow-down"></i>food</a> </div>
		
		Search: <input id="search" type="text" class="span2" />
		<hr>
		<h4>Select any filter below:</h4>
		<div id="tagList"> 
			<a><i class="icon-arrow-up"></i>kids</a><br>
			<a><i class="icon-arrow-up"></i>pets</a><br>
			<a><i class="icon-arrow-up"></i>cruising</a><br>
		</div>
		Tag: <input id="tag" class="span2" />
	</div>
	
	

	<div class="span7 offset1">
		<div id="storyList"></div>
		
		<div class="row">
			<ul class="pager">
  				<li class="previous">
   					 <a href="#">&larr; Start</a>
 				</li>
 				<li><a href="#">1</a></li>
 				<li><a href="#">2</a></li>
 				<li><a href="#">3</a></li>
			  <li class="next">
			    <a href="#">End &rarr;</a>
			  </li>
			</ul>
		</div>
		
		
	
		<div class="row">
		Think we missed something? <?=$this->html->link('Put your City on the map!', 'Story::edit'); ?>
		</div>
	</div>
</div>

<div class="row">
	<div id="status" class="alert alert-info"></div>
</div>
<div class="row">
	<div id="error" class="alert alert-error"></div>
</div>



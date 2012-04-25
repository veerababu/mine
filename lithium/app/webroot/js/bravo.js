/*
// NODE FIELDS:
// nid: nodeID
// pid: parents nodeID
// type: the Node type
			1 group
			2 proposal
			3 topic
			4 comment
			5 user?
			6 new nug?
			
// in: if this user is in the group this node is part of
// name:
// text:

type specific:
*/

var startStatusMessage='';
var maxImageWidth=850;
var maxImageHeight=1000;

$(document).ready(function(){
	
   $('#error').hide();
   if(startStatusMessage=='') $('#status').hide();
   else $('#status').html(startStatusMessage);
});

function addStatus(status)
{
	$('#status').html(status);
	$('#status').show();
}

function onServer(data)
{
	if(data.error && data.error!="" )
	{
		$('#error').html(data.error);
		$('#error').show();
	}else 
	{
		$('#error').text('');
		$('#error').hide();
	}
	
	if(data.status && data.status!="") 
	{
		$('#status').html(data.status);
		$('#status').show();
	}else
	{
		$('#status').text('');
		$('#status').hide();
	}
}


function removeByElement(arrayName,arrayElement)
{
	for(var i=0; i<arrayName.length; i++)
   	{ 
  		if(arrayName[i]==arrayElement)
  		arrayName.splice(i,1); 
  		return(true);
  	} 
  	return(false);
}

function i4_tmpl(tmpl, vals) 
{
	var rgxp, repr;
	
	// default to doing no harm
	tmpl = tmpl   || '';
	vals = vals || {};
	
	// regular expression for matching our placeholders; e.g., #{my-cLaSs_name77}
	rgxp = /#\{([^{}]*)}/g;
	
	// function to making replacements
	repr = function (str, match) {
		return typeof vals[match] === 'string' || typeof vals[match] === 'number' ? vals[match] : str;
	};
	
	return tmpl.replace(rgxp, repr);
}

function parseBBCode(text)
{
	text = text.replace(/\[b\]([^]*?)\[\/b\]/gim,	'<strong>$1</strong>');
	text = text.replace(/\[i\]([^]*?)\[\/i\]/gim,'<em>$1</em>');
	text = text.replace(/\[u\]([^]*?)\[\/u\]/gim,'<u>$1</u>');
	//text = text.replace(/\[img\]([^]*?)\[\/img\]/gim,'<img src="$1" alt="$1" />');
	text = text.replace(/\[email\](.*?)\[\/email\]/gim,'<a href="mailto:$1">$1</a>');
	text = text.replace(/\[url\="?(.*?)"?\]([^]*?)\[\/url\]/gim,'<a  target="_blank" href="$1">$2</a>');
	text = text.replace(/\[size\="?(.*?)"?\]([^]*?)\[\/size\]/gim,'<span style="font-size:$1%">$2</span>');
	text = text.replace(/\[color\="?(.*?)"?\]([^]*?)\[\/color\]/gim,'<span style="color:$1">$2</span>');
	text = text.replace(/\[quote\]([^]*?)\[\/quote\]/gim,'<blockquote>$1</blockquote>');
	text = text.replace(/\[list\=(.*?)\]([^]*?)\[\/list\]/gim,'<ol start="$1">$2</ol>');
	text = text.replace(/\[list\]([^]*?)\[\/list\]/gim,'<ul>$1</ul>');
	text = text.replace(/\[\*\]\s?([^]*?)\n/gim, '<li>$1</li>');
	text = text.replace(/\\n/gi, '<br>');
	text = text.replace(/\n/gi, '<br>');
		
	return(text)
}

function makeMapLink(story,innerText)
{
	var link='';
	if(story.address)
	{
		link +='<a target="_blank" href="http://maps.google.com/maps?q='+story.address;
		if(story.city) link += ' ,'+story.city;
		if(story.state) link +=' ,'+story.state;
		if(story.country) link +=' ,'+story.country;
		link += '">'+innerText+'</a>';
	}
	return(link);
}

function createStoryStr(story,innerText)
{
	var tagStr='&nbsp;';
	if(story.tags)
	{
		for(n=0; n<story.tags.length; n++)
		{
			tagStr += '<span class="label tag" onclick="clickTag(\''+story.tags[n]+'\')">'+story.tags[n]+'</span> ';
		}
	}
	
	
	var str='<div class="story">';
		str += '<div class="row storyHeader">';
			str += '<div class="span3">'; // photo column
				str += tagStr;
			str += '</div>'; // end photoColumn
			
			str += '<div class="span5 offset1">'; // text column
				str += '<div class="row">'; // title row
					if(story.title) str += '<div class="span3 storyTitle"><a href="/story/view/'+story.slug+'">'+story.title+'</a></div>';
					if(story.author && story.authorSlug) str += '<div>by <a href="/users/view/'+story.authorSlug+'">'+story.author+'</a><br></div>';  // was span1
				str += '</div>'; // end titlerow
				
				str += '<div class="row storyAddress">'; // address row
					str+= '<div class="span3">';
					
					var mapLink=makeMapLink(story,'map');
					
					if(story.address) str += story.address+' ('+mapLink+')<br>';
					if(story.hood) str += '<a onclick="clickTag(\''+story.hood+'\')">'+story.hood+'</a><br>';
					if(story.city) str += '<a onclick="clickTag(\''+story.city+'\')">'+story.city+'</a>';
					if(story.state) str += ', <a onclick="clickTag(\''+story.state+'\')">'+story.state+'</a>';
					if(story.country) str += ', <a onclick="clickTag(\''+story.country+'\')">'+story.country+'</a><br>';
					else str += '<br>';
					if(story.phone) str += story.phone+'<br>';
					if(story.url) str += '<a target="_blank" href="http://'+story.url+'">'+story.url+'</a><br>';
				str+= '</div>';
					
					str+= '<div class="span1 updated">';
					if(story.updated)
					{
						var date = new Date(story.updated*1000);
						//date = dateFormat(date, "yyyy-mm-dd'T'HH:MM:ss'Z'");
						date = dateFormat(date, "dd mmm yyyy");
						str+= date;
					}
						
					str += '</div>'; // end date span
				str += '</div>'; // end address row
			str += '</div>'; // end textColumn
		str += '</div>'; // end storyHeader
	
		
		str += '<div class="row">'; // body
		if(story.layout && story.layout==1)
		{	//big pic
			str += createBigImageLayout(story);
		}else
		{	// normal
			str += createNormalLayout(story);
		}
		str += '</div>'; // end story body
		
		
	str += '</div>'; // end story
	
	return(str);	
}

function createNormalLayout(story)
{
	var str='';
	str += '<div class="span4">'; // photo column
	if(story.photos)
	{
		for(n=0; n<story.photos.length; n++) 
		{
			
			str = str + '<div class="photo"><p><img class="leftPhoto" src="/image/view/'+story.photos[n]+'" /><p>' +
						story.captions[n]+'</div>';
			
		}
	}
	str += '</div>'; // end photoColumn
	
	str += '<div class="span5">'; // text column
		if(story.text) 
		{
			var text=parseBBCode(story.text);
			if(text.length>1000)
				str += 	'<div class="storyTextMC">'+text+'</div>';
			else str += 	'<div class="storyTextSC">'+text+'</div>';
		}
	str += '</div>'; // end textColumn
	return(str);
}

function createBigImageLayout(story)
{
	var str='';
	str += '<div class="row">';
	str += '<div class="topPhoto"><p><img class="topPhoto" src="/image/view/'+story.photos[0]+'" /><p>' +
					story.captions[0]+'</div></div>';
		
	if(story.photos[1])
	{		
		
		str += '<div class="span4">'; // photo column
		for(n=1; n<story.photos.length; n++) 
		{
			str = str + '<div class="photo"><p><img class="leftPhoto" src="/image/view/'+story.photos[n]+'" /><p>' +
						story.captions[n]+'</div>';
			
		}
		str += '</div>'; // end photoColumn
		
		str += '<div class="span5">'; // text column
			if(story.text) 
			{
				var text=parseBBCode(story.text);
				if(text.length>1000)
					str += 	'<div class="storyTextMC">'+text+'</div>';
				else str += 	'<div class="storyTextSC">'+text+'</div>';
			}
		str += '</div>'; // end textColumn
	}else
	{
		str += '<div class="span7 offset1">'; // text column
			if(story.text) 
			{
				var text=parseBBCode(story.text);
				if(text.length>1000)
					str += 	'<div class="storyTextMC">'+text+'</div>';
				else str += 	'<div class="storyTextSC">'+text+'</div>';
			}
		str += '</div>'; // end textColumn
	}
	
	return(str);
}



function createStoryThumbStr(story)
{
	if(story.text.length>160)
	{
		story.text=story.text.slice(0,160);
		story.text += "...";
	}
	var str='<div class="thumbStory">';
	if(story.photos[0])	str += '<a href="/story/view/'+story.slug+'"><img src="/image/view/'+story.thumbPhoto+'" /></a>';
	str += '<div class="thumbTitle"><a href="/story/view/'+story.slug+'">'+story.title+'</a></div>';
	str += 	'<div class="thumbText">'+parseBBCode(story.text)+'</div>';
	
	return(str);	
}

function slugify(text)
{
	text = text.toLowerCase();
	text = text.replace(/\s/gi, "-");
	return(text);
}
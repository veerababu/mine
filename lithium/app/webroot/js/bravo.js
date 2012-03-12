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
/*
story.title=$('#StoryTitle').val();
		story.author=<?=$username ?>
		story.text=$('#StoryText').val();
		story.address=$('#StoryAddress').val();
		story.updated="today"
		
		for(n=0; n<5; n++) 
		{
			if(photos[n])
			{
				var pStr='photo'+n;
				story[pStr]=$('['+pStr+']').val();
				story['caption'+n]=$('[caption'+n+']').val();
				//story['pos'+n]=
			}
		}
*/

function parseBBCode(text)
{
	text = text.replace(/\[b\]([^]*?)\[\/b\]/gim,	'<strong>$1</strong>');
	text = text.replace(/\[i\]([^]*?)\[\/i\]/gim,'<em>$1</em>');
	text = text.replace(/\[u\]([^]*?)\[\/u\]/gim,'<u>$1</u>');
	//text = text.replace(/\[img\]([^]*?)\[\/img\]/gim,'<img src="$1" alt="$1" />');
	text = text.replace(/\[email\](.*?)\[\/email\]/gim,'<a href="mailto:$1">$1</a>');
	text = text.replace(/\[url\="?(.*?)"?\]([^]*?)\[\/url\]/gim,'<a href="$1">$2</a>');
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

function createStoryStr(story)
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
			str += '<div class="span4">'; // photo column
				str += tagStr;
			str += '</div>'; // end photoColumn
			
			str += '<div class="span5">'; // text column
				str += '<div class="row">'; // title row
					str += '<div class="span2 storyTitle"><a href="/story/view/'+story.title+'">'+story.title+'</a></div>';
					str += '<div class="span2">by <a href="/users/profile/'+story.author+'">'+story.author+'</a></div>';
				str += '</div>'; // end titlerow
				
				str += '<div class="row storyAddress">'; // address row
					str+= '<div class="span2">'+story.address+'</div>';
				str += '</div>'; // end address row
			str += '</div>'; // end textColumn
		str += '</div>'; // end storyHeader
	
		str += '<div class="row">'; // body
			str += '<div class="span4">'; // photo column
				for(n=0; n<5; n++) 
				{
					var pStr='photo'+n;
					if(story[pStr])
					{
						str = str + '<div class="photo"><p><img src="/image/view/'+story[pStr]+'" /><p>' +
								story['caption'+n]+'</div>';
					}
				}
			str += '</div>'; // end photoColumn
			
			str += '<div class="span5">'; // text column
				str += 	'<div class="storyText">'+parseBBCode(story.text)+'</div>';
			str += '</div>'; // end textColumn
		str += '</div>'; // end story body
		
	str += '</div>'; // end story
	
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
	if(story['photo0'])	str += '<img src="/image/view/'+story['photo0']+'" />';
	str += '<div class="storyTitle"><a href="/story/view/'+story.title+'">'+story.title+'</a></div>';
	str += 	'<div class="thumbText">'+parseBBCode(story.text)+'</div>';
	
	return(str);	
}
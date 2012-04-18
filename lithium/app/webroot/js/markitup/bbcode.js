
function parseBBCode(text)
{
	text = text.replace(/\[b\]([^]*?)\[\/b\]/gim,	'<strong>$1</strong>');
	text = text.replace(/\[i\]([^]*?)\[\/i\]/gim,'<em>$1</em>');
	text = text.replace(/\[u\]([^]*?)\[\/u\]/gim,'<u>$1</u>');
	//text = text.replace(/\[img\]([^]*?)\[\/img\]/gim,'<img src="$1" alt="$1" />');
	text = text.replace(/\[email\](.*?)\[\/email\]/gim,'<a href="mailto:$1">$1</a>');
	text = text.replace(/\[url\="?(.*?)"?\]([^]*?)\[\/url\]/gim,'<a target="_blank" href="$1">$2</a>');
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
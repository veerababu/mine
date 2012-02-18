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

function addStatus()
{
}

function onServer(data)
{
	if(data.error) $('#error').html(data.error);
	else $('#error').text('');
	if(data.status) $('#status').html(data.status);
	else $('#status').text('');
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


function makeNodeStr(node)
{
	var expandButton='<img src="/img/expand.png" onClick="toggleExpand(this,#{nid})" />';
	var header='<div class="#{class}" >';
	var postButtons='<div class="buttons"><div onClick="showForm(\'new_topic\',#{nid})">New Topic</div><div onClick="showForm(\'new_post\',#{nid})">Reply</div></div>';
	
	var tempStr;
	
	if(node['type']==1) 
	{
		node['class']='group';
		tempStr=header+'<a onClick="showPage(\'group\',#{nid})"><table><tr><td>#{name}</td><td>#{num} members.</td><td>Spending $#{cash}</td> <td>on #{date}</td></tr></table></a></div>';

	}else if(node['type']==2) 
	{
		node['class']='prop';
		if(node['in']==1)
		{
			tempStr=header+expandButton+'#{name} #{rating} Budget: $#{cash}  #{date}'+postButtons+'</div>';
		}else tempStr=header+expandButton+'#{name} #{rating} Budget: $#{cash}  #{date}</div>';

	}else if(node['type']==3) 
	{
		node['class']='topic';
		
		if(node['in']==1)
		{
			tempStr=header+expandButton+'#{name} : #{text}'+postButtons+'</div>';
		}else tempStr=header+expandButton+'#{name} : #{text}</div>';
	}else 
	{
		node['class']='post';
		if(node['in']==1)
		{
			tempStr=header+expandButton+'#{text}'+postButtons+'</div>';
		}else tempStr=header+expandButton+'#{text}</div>';
	}
	
	var template=new Template(tempStr);
	return(template.evaluate(node));
}

function addNode(node)
{
	
	gNodeHash[node['nid']]=node;
	
	//alert("addNode"+node['type']+" "+node['pid']);
	
	
	var nodeStr=makeNodeStr(node);
	var nodeName='a'+node['nid'];
	if($(nodeName))
	{
		$(nodeName).firstChild.replace(nodeStr);
	}else
	{ // node hasn't been added yet
		
		var parentID=node['pid'];
		var parent=$('a'+parentID);
		if(parentID>0 && parent) 
		{	// if we should start it off hidden or not
			//alert('add child '+parent);
			if(parent.expanded==1)
			{
				nodeStr='<div id="'+nodeName+'" class="node" >'+nodeStr+'</div>';				
			}else
			{
				nodeStr='<div id="'+nodeName+'" class="node" style="display:none;" >'+nodeStr+'</div>';
			}
			parent.insert( {'bottom' : nodeStr });
		}else 
		{
			//alert("no parent");
			nodeStr='<div id="'+nodeName+'" class="node" >'+nodeStr+'</div>';
			$('nodes'+node['type']).insert( nodeStr ,$('nodes'+node['type']));
		}
	}
}


function toggleExpand(element,nodeID)
{
	var node=$('a'+nodeID);
	
	if(node.expanded==1)
	{
		node.expanded=0;
		element.src="/img/expand.png";
		
		var sibs=node.children;
		//alert(sibs);
		for(var index = 1, len = sibs.length; index < len; ++index) {
			  sibs[index].hide();
			}
	}else
	{
		node.expanded=1;
		element.src="/img/collapse.png";
		
		var sibs=node.children;
		for(var index = 1, len = sibs.length; index < len; ++index) {
			  sibs[index].show();
			}
		
		fetchPosts(nodeID);
	}
}


function handleResult(JSON)
{
	//alert(JSON);
	
	var nodes=JSON['nodes'];
	if(nodes)
	{
		//alert(nodes+" "+nodes.length);
		for(var index = 0, len = nodes.length; index < len; ++index) 
		{
			//alert("adding");
			addNode(nodes[index]);
		}
		if(JSON['pages']>1)
		{
			alert("more pages");
		}
	}
	
	if(JSON['status']) 
	{
		$('status').innerHTML=JSON['status'];
	}
}


function showForm(formName,parentID)
{
	//alert("showing "+formName+" id: "+parentID);
	$(formName).show();
	$(formName+"_pid").value=parentID;
	//$(formName).down('pid').value=parentID;
	
	//addForm(parentID,form);
}
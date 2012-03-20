// crop.js
// References:
// http://www.permadi.com/blog/2010/10/html5-saving-canvas-image-data-using-php-and-ajax/
// https://developer.mozilla.org/en/Canvas_tutorial/Using_images
// http://deepliquid.com/content/Jcrop_Implementation_Theory.html
//
//
/*
When a user adds a local image we make the following:
	-thumbCanvas# : This is the thumbnail canvas that is shown when the resize window isn't maximized
	-workingCanvas# : This is canvas that eventually gets sent to the server
	-pic# : This is the full source image
*/

var totalFileSize = 0;
var dropbox;

$(document).ready(function()
{    
    dropbox = document.getElementById('imageDrop');

    // Setup drag and drop handlers.
    dropbox.addEventListener('dragenter', onDragEnter, false);
    dropbox.addEventListener('dragover', onDragOver, false);
    dropbox.addEventListener('dragleave', onDragLeave, false);
    dropbox.addEventListener('drop', onDrop, false);
});

function fileSelected(ele)
{
	loadImg(ele.files[0]);
}


function loadImg(imgFile) 
{
    if (!imgFile.type.match(/image.*/))
        return;

	var photoIndex=getFreePhotoSlot();
	if(photoIndex>=0)
	{
		edit.photos[photoIndex].name=imgFile.name;
		
		var img=addLocalImage(imgFile,photoIndex);
		
	    var reader = new FileReader();
	    reader.photoIndex=photoIndex;
	    reader.onload = (function(x) { return function(e) { 
	    	x.src = e.target.result;
	     	var width=x.width;
    		var height=x.height;
    
		    if(width>maxImageWidth) 
		    {
		    	width=maxImageWidth;
		    	
		    }
		    if(height>maxImageHeight) height=maxImageHeight;
		    
		    var ratio=Math.min(width/x.width,height/x.height);
		    if(ratio*x.width < width) width=ratio*x.width;
		    else if(ratio*x.height < height) height=ratio*x.height;
    		
    		$('#workingCanvas'+this.photoIndex).height(height);
    		$('#workingCanvas'+this.photoIndex).width(width);
    		
    		var index=this.photoIndex;
    		 edit.photos[this.photoIndex].jcrop =$.Jcrop($('#pic'+this.photoIndex), { 
				//setSelect:   [ 0, 0, c.w, c.h ],
				onSelect: function(c){ return cropImage(c,index); } } );
    		
    		var c=[];
    		c.x=0; c.y=0; c.w=x.width; c.h=x.height;
    		cropImage(c,this.photoIndex);
		   closeCropPane(this.photoIndex);
    		
    		
	    };  })(img);
	    reader.readAsDataURL(imgFile);
	}
}  

function addLocalImage(imgFile,photoIndex)
{
	if(getFreePhotoSlot()==-1)
	{
		$('#imageDrop').hide();
	}
	
	edit.photos[photoIndex].filled=true;
	edit.photos[photoIndex].changed=true;
	edit.photos[photoIndex].scale=1;
	
	var photoStr='<div class="photoEditBox" id="div'+photoIndex+'">' +
					'<input type="hidden" id="photo'+photoIndex+'" name="photo'+photoIndex+'"  />' +
					'<div class="row" id="thumbDiv'+photoIndex+'" >'+
						'<div class="span3">'+
							'<canvas id="thumbCanvas'+photoIndex+'" ></canvas>'+
						'</div>'+
						'<div class="span5"><div class="row">'+
		             			'Caption: <input id="caption'+photoIndex+'" name="caption'+photoIndex+'" type="text" />' +
		             		'</div><div class="row pebButtons">'+
			          			'<div class="span2"><input type="button" id="cropButton'+photoIndex+'" value="Resize and Crop Image" class="btn-info" onclick="openCropPane('+photoIndex+')" /></div>' +
			          			'<div class="span2 offset1"><input type="button" value="Remove this Image" class="btn-danger" onClick=deleteImage("'+photoIndex+'") /></div>' +
		          		'</div></div>'+
		          	'</div>' +		             
		             
		             '<div id="resize'+photoIndex+'" >'+
		             	'<div class="row"> <div class="span2">Scale Image:</div> <div class="span4" id="sizeSlider'+photoIndex+'"></div>' +
		             	'<div class="span1"><input type="button" id="closeButton'+photoIndex+'" value="Close Resize" class="btn-info"  onclick="closeCropPane('+photoIndex+')"/></div></div>' +
 						'<div class="row">'+
 							'<div class="span8"><canvas id="workingCanvas'+photoIndex+'" ></canvas></div>'+
 							'<div class="span8"><img id="pic'+photoIndex+'" ></img></div>'+
 							'<div class="span8">Source Image. Select a portion to crop.</img></div>'+
 						'</div>'+
		             '</div>' +
	             '</div>';
	            
	
	$('#photoList').append(photoStr);
	$('#sizeSlider'+photoIndex).slider({ value: 100, slide: function(event, ui) { return onSlide(event,ui,photoIndex); } });
	
	var img = document.getElementById('pic'+photoIndex);
    img.file = imgFile;
    
	return(img);
}

function onSlide(event, ui, photoIndex) 
{
	edit.photos[photoIndex].scale=ui.value/100;
	$('#status').text(edit.photos[photoIndex].scale);
	var c=edit.photos[photoIndex].jcrop.tellSelect();
	if(c.w==0 && c.h==0)
	{
		c.w=$('#pic'+photoIndex).width();
		c.h=$('#pic'+photoIndex).height();
	}
	cropImage(c,photoIndex);  
}

/*
function maximizePhoto(photoIndex)
{
	var photo=document.getElementById('pic'+photoIndex);
	var width=photo.naturalWidth;
	var height=photo.naturalHeight;
	
	if(width>maxImageWidth) 
	{
		width=maxImageWidth;
		
	}
	if(height>maxImageHeight) height=maxImageHeight;
	
	var ratio=Math.min(width/photo.naturalWidth,height/photo.naturalHeight);
	if(ratio*photo.naturalWidth < width) width=ratio*photo.naturalWidth;
	else if(ratio*photo.naturalHeight < height) height=ratio*photo.naturalHeight;
		 
	photo=$('#pic'+photoIndex);
	photo.width(width);
	photo.height(height);
}*/   



function openCropPane(photoIndex)
{
	$('#resize'+photoIndex).show();
	$('#thumbDiv'+photoIndex).hide();	
}

function closeCropPane(photoIndex)
{
	var thumbCanvas=document.getElementById('thumbCanvas'+photoIndex);
	var workingCanvas=document.getElementById('workingCanvas'+photoIndex);
	
	if(workingCanvas.width>200) thumbCanvas.width=200;
	else thumbCanvas.width=workingCanvas.width;
	
	if(workingCanvas.height>200) thumbCanvas.height=200;
	else thumbCanvas.height=workingCanvas.height;
	
	var ratio=Math.min(thumbCanvas.width/workingCanvas.width,thumbCanvas.height/workingCanvas.height);
	if(ratio*workingCanvas.width < thumbCanvas.width) thumbCanvas.width=ratio*workingCanvas.width;
	if(ratio*workingCanvas.height < thumbCanvas.height) thumbCanvas.height=ratio*workingCanvas.height;
	
    var ctx2 = thumbCanvas.getContext('2d');
    ctx2.clearRect(0,0,thumbCanvas.width,thumbCanvas.height);
    
    ctx2.drawImage(workingCanvas, 0, 0, workingCanvas.width, workingCanvas.height, 0, 0, thumbCanvas.width, thumbCanvas.height);
   
	
	//var photo=$('#thumbCanvas'+photoIndex);
	//photo.show();
	
	$('#resize'+photoIndex).hide();
	$('#thumbDiv'+photoIndex).show();
	
	//$('#cropButton'+photoIndex).show();
	//$('#closeButton'+photoIndex).hide();
	//$('#closeButton'+photoIndex).hide();
}


// Crop selection in Canvas 1 into Canvas 2
//
function cropImage(c,photoIndex) 
{	
	edit.photos[photoIndex].changed=true;
	
	var source=document.getElementById('pic'+photoIndex);
	var canv2=document.getElementById('workingCanvas'+photoIndex);
	
	var width=c.w;
	var height=c.h;
	
	if(width>maxImageWidth) width=maxImageWidth;
	if(height>maxImageHeight) height=maxImageHeight;
	
	var ratio=Math.min(width/c.w,height/c.h);
	if(ratio*c.w < width) width=ratio*c.w;
	else if(ratio*c.h < height) height=ratio*c.h;
	
	width=width*edit.photos[photoIndex].scale;
	height=height*edit.photos[photoIndex].scale;
	
	$('#workingCanvas'+photoIndex).height(height).width(width);
	canv2.height=height;
	canv2.width=width;
	
    var ctx2 = canv2.getContext('2d');
    ctx2.clearRect(0,0,width,height);
    
    ctx2.drawImage(source, c.x, c.y, c.w, c.h, 0, 0, width, height);
}

// Package the contents of canvas 2 and dispatch to server. Also need to catch the error return.
//
function uploadSelection() {
    var imgData = document.getElementById('canv2').toDataURL("image/png");
    var postStr = "i=" + encodeURIComponent(imgData);
    $.post('saveUpload.php', postStr, function(resp) {alert('Success - ' + resp);});
}

// go through all the images that have been edited and send them to the server
function uploadImages()
{
	for(var n=0; n<5; n++)
	{
		if(edit.photos[n].changed)
		{
			edit.photos[n].changed=false;
			
			var imgData = document.getElementById('workingCanvas'+n).toDataURL("image/jpeg");
		    var postStr = "index="+n+"&name="+edit.photos[n].name+"&i=" + encodeURIComponent(imgData);
		    edit.imagesSaving++;
		    $.post('/story/saveImage', postStr, onSaveImage, "json" );
		    
    	}
    }   
}

function onSaveImage(data)
{
	onServer(data);
	
	var photoID=data.photoID;
	var photoIndex=data.photoIndex;
	
	$('#photo'+photoIndex).val(photoID);
	
	edit.imagesSaving--;
	
	if(edit.imagesSaving==0)
	{
		if( edit.userWantsSave )
		{
			saveStory();
		}else if(edit.userWantsPublish)
		{
			publishStory();
		}else updatePreview();
	}
}

/*

    Element.prototype.hasClassName = function(name) {
      return new RegExp("(?:^|\\s+)" + name + "(?:\\s+|$)").test(this.className);
    };

    Element.prototype.addClassName = function(name) {
      if (!this.hasClassName(name)) {
        var c = this.className;
        this.className = c ? [c, name].join(' ') : name;
      }
    };

    Element.prototype.removeClassName = function(name) {
      if (this.hasClassName(name)) {
        var c = this.className;
        this.className = c.replace(
            new RegExp("(?:^|\\s+)" + name + "(?:\\s+|$)", "g"), "");
      }
    };
    
    
    // insertAdjacentHTML(), insertAdjacentText() and insertAdjacentElement 
    // for Netscape 6/Mozilla by Thor Larholm me@jscript.dk 
    if (typeof HTMLElement != "undefined" && !HTMLElement.prototype.insertAdjacentElement) {
        HTMLElement.prototype.insertAdjacentElement = function (where, parsedNode) {
            switch (where) {
            case 'beforeBegin':
                this.parentNode.insertBefore(parsedNode, this)
                break;
            case 'afterBegin':
                this.insertBefore(parsedNode, this.firstChild);
                break;
            case 'beforeEnd':
                this.appendChild(parsedNode);
                break;
            case 'afterEnd':
                if (this.nextSibling) this.parentNode.insertBefore(parsedNode, this.nextSibling);
                else this.parentNode.appendChild(parsedNode);
                break;
            }
        }

        HTMLElement.prototype.insertAdjacentHTML = function (where, htmlStr) {
            var r = this.ownerDocument.createRange();
            r.setStartBefore(this);
            var parsedHTML = r.createContextualFragment(htmlStr);
            this.insertAdjacentElement(where, parsedHTML)
        }


        HTMLElement.prototype.insertAdjacentText = function (where, txtStr) {
            var parsedText = document.createTextNode(txtStr)
            this.insertAdjacentElement(where, parsedText)
        }
    }
    */


function onDragEnter(e) {
  e.stopPropagation();
  e.preventDefault();
}

function onDragOver(e) {
  e.stopPropagation();
  e.preventDefault();
  dropbox.addClassName('rounded');
}

function onDragLeave(e) {
  e.stopPropagation();
  e.preventDefault();
  dropbox.removeClassName('rounded');
}

function onDrop(e) 
{
	e.stopPropagation();
	e.preventDefault();
	
	dropbox.removeClassName('rounded');
	
	var readFileSize = 0;
	var files = e.dataTransfer.files;
	
	// Loop through list of files user dropped.
	for (var i = 0, file; file = files[i]; i++) 
	{
		// Only process image files.
	    var imageType = /image.*/;
	    if (!file.type.match(imageType)) {
	        continue;
	        }
	    
	    	loadImg(file);
	}
	
	return false;
}

    
<div id="StoryForm">
	<input type="hidden" name="_id" id="StoryID"  />
	<input type="hidden" name="author" id="StoryAuthor"  />
	<input type="hidden" name="status" id="StoryStatus"  />
	
	<label>Story Title</label>
	<input id="StoryTitle" type="text" name="title">
	
	
	
	<div id="WordCount"></div>
	<textarea id="StoryText" class="span8" name="text"></textarea>
	
	<div class="row">
	<fieldset class="span5">
	<legend>Optional Information</legend>
	<table>
	<tr><td class="span2" >Street Address</td><td>
	  <input id="StoryAddress" name="address" type="text">
	</div> 
	</td></tr>
	<tr><td class="span2" >City</td><td><input id="StoryCity" type="text" name="city">
	</td></tr>
	<tr><td class="span2" >Neigborhood</td><td><input id="StoryHood" type="text" name="hood">
	</td></tr>
	<tr><td class="span2" >State or Province</td><td><input id="StoryState" type="text" name="state">
	</td></tr>
	<tr><td class="span2" >Country</td><td><input id="StoryCountry" type="text" name="country">
	</td></tr>
	<tr><td class="span2" >Phone</td><td><input id="StoryPhone" name="phone" type="text">
	</td></tr>
	<tr><td class="span2" >Webpage</td><td>
	  <input id="StoryURL" name="url" type="text">
	</td></tr>
	</table>
	</fieldset>
	</div>
	
	<label>Tags (food, kids, outdoors, art, etc... )</label>
	<input id="StoryTags" class="span8 ui-autocomplete-input" type="text" name="tags" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">
</div> 

<label>Photos</label>
<div id="thumbnailEdit" class="photoEditBox" >
	<input type="hidden" id="StoryThumbPhoto" name="thumbPhoto"  />
	<label>Thumbnail</label>
	<canvas id="thumbCanvas" width="230" height="160"></canvas>
	<input type="button" value="Change Thumbnail Image" class="btn-info" onclick="changeThumbSource()" />
	<!-- <input type="button" value="Resize and Crop Thumbnail" class="btn-info" onclick="toggleThumbResizePane()" /> -->
		
	<div id="resizeThumb" >
 		<div class="row">
 			<div class="span8"><img id="picThumb" ></img></div>
 			<div class="span8">Source Image. Select a portion to crop.</img></div>
 		</div>
	</div>
</div>
	             
	             
	             
	             

<div id="photoList"></div>

<div id="imageDrop" class="center linear">
	<h2>Drop image files here.</h2>
	<span>or <input type="file" onChange="fileSelected(this)" multiple="multiple" name="file" ></span>
</div>


<hr>
	
	<div class="row"> 
		<div class="span2"><input type="button" value="Upload all images" class="btn-info" onClick="uploadImages()" /></div>
		<div id="UploadStatus" class="span1"></div>
		<div class="span2">
		    <div class="progress progress-striped active"> <div id="UploadProgress" class="bar" style="width: 0%;"></div></div>
		</div>
		<div class="span3">Big First Image: <input id="StoryLayout" name="layout" type="checkbox"></div>
	</div>
<hr>



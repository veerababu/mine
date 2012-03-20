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
<tr><td class="span2" >Street Address</td><td><div class="input-prepend">
   <span class="add-on"><i class="icon-home"></i></span>
  <input id="StoryAddress" name="address" type="text">
</div> 
</td></tr>
<tr><td class="span2" >City</td><td><input id="StoryCity" type="text" name="city">
</td></tr>
<tr><td class="span2" >Neigborhood</td><td><input id="StoryHood" type="text" name="hood">
</td></tr>
<tr><td class="span2" >Sate or Province</td><td><input id="StoryState" type="text" name="state">
</td></tr>
<tr><td class="span2" >Country</td><td><input id="StoryCountry" type="text" name="country">
</td></tr>
<tr><td class="span2" >Phone</td><td><div class="input-prepend">
  <span class="add-on"><i class="icon-home"></i></span>
  <input id="StoryPhone" name="phone" type="text">
</div> 
</td></tr>
<tr><td class="span2" >Webpage</td><td><div class="input-prepend">
  <span class="add-on"><i class="icon-home"></i></span>
  <input id="StoryURL" name="url" type="text">
</div> 
</td></tr>
</table>
</fieldset>
</div>

<label>Tags (food, kids, outdoors, art, etc... )</label>
<input id="StoryTags" class="span8 ui-autocomplete-input" type="text" name="tags" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">


<label>Photos</label>
<!--
<div id="image-uploader"></div>
<div id="photoList"></div>
-->

<input type="hidden" name="_id" id="StoryID"  />
<input type="hidden" name="author" id="StoryAuthor"  />

<label>Story Title</label>
<input id="StoryTitle" type="text" name="title">



<label>Text of Story</label>
<textarea id="StoryText" class="span8" name="text"></textarea>

<label>Address</label>
<div class="input-prepend">
  <span class="add-on"><i class="icon-home"></i></span>
  <input id="address" name="address" type="text">
</div>

<label>City</label>
<input id="StoryCity" type="text" name="city">

<label>Neigborhood</label>
<input id="StoryHood" type="text" name="hood">

<label>Tags (food, kids, outdoors, art, etc... )</label>
<input id="StoryTags" class="span8 ui-autocomplete-input" type="text" name="tags" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true">


<label>Photos</label>

<div id="image-uploader">

</div>
<div id="photoList"></div>

<h1>Log in</h1>

<div class="row">
	<div class="span9">
<?=$this->form->create(null,array("class" => "well")); ?>
    <?=$this->form->field('username'); ?>
    <?=$this->form->field('password', array('type' => 'password')); ?>
    <div class="input-prepend">
      <span class="add-on"><i class="icon-envelope"></i></span>
      <input class="span2" id="email" name="email" type="text">
    </div>
    <?=$this->form->submit('Log in'); ?> <a href="/forgot">Forgot your password?</a>
<?=$this->form->end(); ?>
<a href="/register">Create an account</a>
	</div>
</div>
<div class="row">
	<div id="status" class="alert alert-info"></div>
</div>
<div class="row">
	<div id="error" class="alert alert-error"></div>
</div>

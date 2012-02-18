<h2>Add user</h2>
    <?=$this->form->create($user); ?>
        <?=$this->form->field('username'); ?>
        <?=$this->form->field('email'); ?>
        <?=$this->form->field('password', array('type' => 'password')); ?>
        <?=$this->form->submit('Create me'); ?>
    <?=$this->form->end(); ?>
<div class="row">
	<div id="status" class="alert alert-info"></div>
</div>
<div class="row">
	<div id="error" class="alert alert-error"></div>
</div>

<?php

use app\models\Users;
use lithium\security\Password;
use lithium\util\String;


// TODO: http://lithify.me/docs/manual/auth/simple-auth-user.wiki 
//    says to use a different hashing function than is used by lithium\security\auth\adapter\Form::_filters
// not sure which is best?

Users::applyFilter('save', function($self, $params, $chain) 
{
    if($params['data']) 
    {
        $params['entity']->set($params['data']);
        $params['data'] = array();
    }
    
    if(!$params['entity']->exists()) 
    {
        //TODO  see above $params['entity']->password = Password::hash($params['entity']->password);
        $params['entity']->password = String::hash($params['entity']->password);
    }
    
    return $chain->next($self, $params, $chain);
});

?>

<?php

class Controller_Home extends Controller{
    public function action_index()
    {
        $my_id= Session::get('user_id');
        if($my_id==null){
            Response::redirect('auth/login');
        }
        else{
            return View::forge('ranking/home');
        }

    }
}

?>
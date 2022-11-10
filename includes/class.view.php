<?php 

class View{

    public function get($view)
    {
        require_once EPIN__PLUGIN_DIR . "views/".$view.".php";
    }

}
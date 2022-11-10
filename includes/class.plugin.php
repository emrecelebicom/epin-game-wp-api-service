<?php

class Plugin{

    public function save()
    {

        $message = new Message();

        update_option('epin_active', (isset($_POST['epin_active']) ? $_POST['epin_active'] : "false"));
        update_option('epin_items_count', $_POST['epin_items_count']);
        update_option('epin_items_time', $_POST['epin_items_time']);
        update_option('epin_items_time_name', $_POST['epin_items_time_name']);
        update_option('epin_items_category', $_POST['epin_items_category']);
        update_option('epin_base_url', $_POST['epin_base_url']);
        update_option('epin_authorization', $_POST['epin_authorization']);
        update_option('epin_api_name', $_POST['epin_api_name']);
        update_option('epin_api_key', $_POST['epin_api_key']);

        $message->show("Ayarlar Kaydedildi.");
    }

}
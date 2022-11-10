<?php

$epin_active = get_option('epin_active', 'none');
$epin_items_count = get_option('epin_items_count', '1');
$epin_items_time = get_option('epin_items_time', '12');
$epin_items_time_name = get_option('epin_items_time_name', 'minute');
$epin_items_category = get_option('epin_items_category', '');
$epin_base_url = get_option('epin_base_url', 'none');
$epin_authorization = get_option('epin_authorization', 'none');
$epin_api_name = get_option('epin_api_name', 'none');
$epin_api_key = get_option('epin_api_key', 'none');
$epin_last_update = get_option('epin_last_update', 'none');


$args = array(
    "hide_empty" => 0,
    "type"      => "post",      
    "orderby"   => "name",
    "order"     => "ASC" 
);

$categories = get_categories($args);


$content = "";
$content .= '<form action="" method="post">';
$content .= '<button name="update_datas" id="allItems">Yeni İçerikleri Çek</button>';
$content .= '</form>';
$content .= '<form action="" method="post">';
$content .= '<h4>EPIN ITEM ÇEKME TANIMLAMALARI</h4>';
$content .= '<input type="checkbox" id="epin_active" name="epin_active" value="true" '.($epin_active == "true" ? "checked" : "").' class="tgl tgl-light" /><label for="epin_active">Epin Games otomatik çekme servislerini aktif et.</label>';
$content .= '<br><br>';
$content .= '<label for="epin_items_count">Her işlemde çekilecek maksimum kayıt sayısı</label><input type="text" id="epin_items_count" name="epin_items_count" value="'.$epin_items_count.'" placeholder="Varsayılan:1" />';
$content .= '<br><br>';
$content .= '<label for="epin_items_time">Otomatik çekme aralığı</label><input type="text" id="epin_items_time" name="epin_items_time" value="'.$epin_items_time.'" placeholder="Varsayılan:12saat" />';
$content .= '<select name="epin_items_time_name" class="selectbox"><option value="minute" '.($epin_items_time_name == "minute" ? "selected" : "").'>Saat</option><option value="day" '.($epin_items_time_name == "day" ? "selected" : "").'>Gün</option></select>';
$content .= '<br><br>';
$content .= '<label for="epin_items_category">Varsayılan Kategori</label>';
$content .= '<select name="epin_items_category">';
$content .= '<option value="" selected>-Kategori Tanımsız-</option>';
foreach($categories as $category){
    $content .= '<option value="'.$category->slug.'" '.($epin_items_category == $category->slug ? "selected" : "").'>'.$category->name.'</option>';
}
$content .= '</select>';
$content .= '<h4>EPIN SERVİS BAĞLANTILARI</h4>';
$content .= '<label for="epin_base_url">Base URL</label><input type="text" id="epin_base_url" name="epin_base_url" value="'.$epin_base_url.'" />';
$content .= '<br><br>';
$content .= '<label for="epin_authorization">Authorization</label><input type="text" id="epin_authorization" name="epin_authorization" value="'.$epin_authorization.'" />';
$content .= '<br><br>';
$content .= '<label for="epin_api_name">API Name</label><input type="text" id="epin_api_name" name="epin_api_name" value="'.$epin_api_name.'" />';
$content .= '<br><br>';
$content .= '<label for="epin_api_key">API Key</label><input type="text" id="epin_api_key" name="epin_api_key" value="'.$epin_api_key.'" />';
$content .= '<br><br>';
$content .= '<button type="submit" id="save" name="submit_scripts_update">Kaydet</button>';
$content .= '<br><br>';
$content .= '<span>Son Güncelleme: '.date("d.m.Y H:i", $epin_last_update).'</span>';
$content .= '<br><br>';
$content .= "</form>";

echo $content;
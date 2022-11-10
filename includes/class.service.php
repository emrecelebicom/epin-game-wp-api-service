<?php

class Service {

    public $epin_base_url;
    public $epin_authorization;
    public $epin_api_name;
    public $epin_api_key;

    public $headers;

    public function __construct() {
        $this->epin_base_url = get_option('epin_base_url');
        $this->epin_authorization = get_option('epin_authorization');
        $this->epin_api_name = get_option('epin_api_name');
        $this->epin_api_key = get_option('epin_api_key');

        $this->headers = array(
            'Authorization: ' . $this->epin_authorization,
            'ApiName: ' . $this->epin_api_name,
            'ApiKey: ' . $this->epin_api_key
        );
    }


    public function get($uri)
    {

        try {
            $message = new Message();
            
            $client = curl_init();
            //curl_setopt($client, CURLOPT_PORT, 80);
            curl_setopt($client, CURLOPT_URL, $this->epin_base_url . $uri);
            curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($client, CURLOPT_FAILONERROR, true);
            curl_setopt($client, CURLOPT_HTTPHEADER, $this->headers);

            $data = curl_exec($client);
    
            if($data == false){

                echo "<p><span class=\"dashicons dashicons-no\"></span> Bağlantı hatası...</p>";
                echo '<p><span class=\"dashicons dashicons-no\"></span> Curl Bağlantı Hatası: ' . curl_error($client) . "</p>";

            }else{
                update_option('epin_last_update', time());
                $message->show("Bağlantı Sağlandı.");
            }
    
            curl_close($client);
            return json_decode($data);


        } catch (\Throwable $th) {
            return $th;
        }

    }

    public function save()
    {
        $message = new Message();

        $epin_items_count = get_option('epin_items_count', '1');

        $result = $this->get("/GetGameList");

        $inItem = 0;

        foreach($result->GameDto->GameViewModel as $items){

            foreach($items->GameItemsViewModel as $item){

            $args = array("post_type" => "product", "s" => $item->Name);
            $query = get_posts( $args );

            if(count($query) < 1){
                $inItem++;
                $image = $this->insert($item, $items->ImageUrl);
            }
            
            if($inItem >= $epin_items_count) break;
            }
            if($inItem >= $epin_items_count) break;
        }

        update_option('epin_last_update', time());
        $message->show($inItem." yeni ürün çekildi ve eklendi.");
    }


    public function insert($item, $image)
    {
        try {

            $post = array(
                'post_author' => get_current_user_id(),
                'post_content' => $item->Description,
                'post_excerpt' => $item->Description,
                'post_status' => "publish",
                'post_title' => $item->Name,
                'post_parent' => '',
                'post_type' => "product",
            );
            
            //Create post
            $post_id = wp_insert_post( $post, $wp_error );

            update_post_meta( $post_id, '_visibility', 'visible' );
            update_post_meta( $post_id, '_stock_status', 'instock');
            update_post_meta( $post_id, '_regular_price', $item->Price );
            update_post_meta( $post_id, '_sku', $item->StockCode);
            update_post_meta( $post_id, '_manage_stock', "yes" ); //Stok seviyesini yönetin. yes/no
            update_post_meta( $post_id, '_stock', $item->Stock );

            return $this->image($post_id, $image);


        } catch (\Throwable $th) {
            return $th;
        }
    }


    public function image($post_id, $image)
    {
        try {
            
            // Dosya tipi kontrol ediliyor.
            $filetype = wp_check_filetype( basename( $image ), null );

            // Yükleme dizini.
            $wp_upload_dir = wp_upload_dir();

            // Fiziksel yükleme
            $upload_file = $wp_upload_dir['path'] . '/' . basename( $image );
            $contents = file_get_contents($image);
            $savefile = fopen($upload_file, 'w');
            fwrite($savefile, $contents);
            fclose($savefile);

            // Yükleme dizisi
            $attachment = array(
                'guid'           => $wp_upload_dir['url'] . '/' . basename( $image ), 
                'post_mime_type' => $filetype['type'],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $image ) ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            // Görsel yükleme.
            $attach_id = wp_insert_attachment( $attachment, $wp_upload_dir['path'] . '/' . basename( $image ), $post_id );

            // wp_generate_attachment_metadata() kullanımı için
            require_once( ABSPATH . 'wp-admin/includes/image.php' );

            // Meta verileri ve veritabanı kaydı oluşturuluyor.
            $attach_data = wp_generate_attachment_metadata( $attach_id, $image );
            wp_update_attachment_metadata( $attach_id, $attach_data );

            set_post_thumbnail( $post_id, $attach_id );


        } catch (\Throwable $th) {
            return $th;
        }
    }

}
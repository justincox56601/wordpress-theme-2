<?php 
class Terms{
    function __construct(){

        if(get_page_by_title('Terms and Privacy Statement') === NULL){
            $this->terms_and_privacy();
        }
    }

    public function terms_and_privacy(){
        $content = file_get_contents(get_template_directory() . '/terms.txt');
        $content = explode("\n", $content);
        $txt = '<!-- wp:paragraph  --><p>';
        foreach($content as $c){
            $txt .= $c . "</p><!-- /wp:paragraph --><!-- wp:paragraph  --><p>";
        }
        $txt .= "</p><!-- /wp:paragraph -->";
        
        
        $args = array(
            'post_author' => 'Admin',
            'post_content' => $txt,
            'post_title' => "Terms and Privacy Statement",
            'post_status' => 'draft',
            'post_type' => 'page',

        );
        wp_insert_post($args);
    }
}

<?php
/*
Plugin Name: FooterLinks
Plugin URI: http://alien.in.ua/
Description: Widget for showing links in footer
Version: 1.0
Author: Misha Burda
*/

add_action("widgets_init", function () {
    register_widget("FooterLinks");
});

class FooterLinks extends WP_Widget
{
    public function __construct() {
        parent::__construct("footerlinks_widget", "Simple Footer Links",
            array("description" => "A simple widget to show all posts by chosen category [special for Digital Logic]"));
    }
    public function form($instance) {
        $title = "";
        $slug = "";

        if (!empty($instance)) {
            $title = $instance["title"];
            $slug = $instance["slug"];
        }

        $tableId = $this->get_field_id("title");
        $tableName = $this->get_field_name("title");

        echo '<label for="' . $tableId . '">Title</label><br>';
        echo '<input id="' . $tableId . '" type="text" name="' .    $tableName . '" value="' . $title . '"><br>';

        $slugId = $this->get_field_id("slug");
        $slugName = $this->get_field_name("slug");

        // echo '<label for="' . $slugId . '">Category Slug</label><br>';
        //echo '<textarea id="' . $slugId . '" name="' . $slugName .    '">' . $slug . '</textarea>';

        $args=array(
            'orderby' => 'name',
            'order' => 'ASC'
        );
        $categories=get_categories($args);
        echo '<label for="' . $slugId . '">Category Slug</label><br>';
        echo '<select name="' . $slugName . '">';
        foreach($categories as $category) {

            if ($category->slug == $slug)
                echo '<option selected>';
            else echo '<option>';
            echo $category->slug;
            echo '</option>';
        }
        echo '</select>';
    }
    public function update($newInstance, $oldInstance) {
        $values = array();
        $values["title"] = htmlentities($newInstance["title"]);
        $values["slug"] = htmlentities($newInstance["slug"]);
        return $values;
    }
    public function widget($args, $instance) {
        $title = $instance["title"];
        $slug = $instance["slug"];

        // The Query
        query_posts(  array ( 'category_name' => $slug, 'posts_per_page' => -1 ) );

        // The Loop
        echo '<div id="nav_menu-3" class="fwidget et_pb_widget widget_nav_menu">';
        echo '<h4 class="title">'.$title.'</h4>';
        echo '<div class="menu-footer-links-container"><ul id="menu-footer-links" class="menu">';
        while ( have_posts() ) : the_post();
            echo '<li class="menu-item menu-item-type-post_type menu-item-object-page">';
            echo '<a href="'.get_post_permalink().'">'.get_the_title().'</a>';
            echo '</li>';
        endwhile;
        echo '</ul></div></div>';

        // Reset Query
        wp_reset_query();
    }
}
?>

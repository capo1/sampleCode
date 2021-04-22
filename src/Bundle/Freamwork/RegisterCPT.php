<?php

namespace App\Bundle\Framework;

use App\Bundle\RegisterInterface;


class RegisterCPT implements RegisterInterface
{
    public function register(): void
    {
        add_action('init', [$this, 'registerObjectType']);
    }

    public function registerObjectType()
    {

        $labels = array(
            'name' => _x('Obiekt', 'post type general name'),
            'singular_name' => _x('Obiekt', 'post type singular name'),
            'add_new' => _x('Dodaj element', 'add'),
            'add_new_item' => _x('Dodaj', 'add'),
            'edit_item' => _x('Edytuj', 'edit'),
            'new_item' => _x('Nowy', 'new'),
            'all_items' => _x('Wszystkie', 'all'),
            'view_item' => _x('Zobacz', 'see'),
            'search_items' => _x('Szukaj', 'search'),
            'not_found' => _x('No found', ''),
            'not_found_in_trash' => _x('No found in the Trash', ''),
            'parent_item_colon' => ',',
            'menu_name' => 'Obiekt - sale/ biura/ lokale'
        );

        $args = array(
            'labels' => $labels,
            'description' => '',
            'public' => true,
            'menu_position' => 5,
            'supports' => array('title', 'thumbnail'),
            'has_archive' => true,
            'show_in_nav_menus' => true,
            'with_front' => false,
            'rewrite' => array('slug' => '%category%'),
        );

        add_theme_support('post-thumbnails');
        register_post_type('object', $args);

        add_filter('post_type_link', [$this, 'object_permalinks'], 10, 2);

        $this->addObjectCategory();
    }

    function object_permalinks($permalink, $post)
    {
        if ($post->post_type !== 'object')
            return $permalink;

        $terms = get_the_terms($post->ID, 'object_category');

        if (!$terms)
            return str_replace('%category%/', '', $permalink);

        //$post_terms = array();
        foreach ($terms as $term)
            $post_terms = $term->slug;


        return str_replace('%category%',  $post_terms, $permalink);
    }
}

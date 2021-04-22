<?php

namespace App\Objects;

use Timber\Post;
use Timber\Timber;

class ObjectPosts extends Post
{
    function __construct()
    {
    }
    var $_category;
    var $taxonomy = 'object_category';

    var $default =  [
        'post_type' => 'object',
        'posts_per_page' => -1
    ];

    /**
     * Get list of all Posts by custom taxonomy 
     * 
     * Add array of taxonomy to object and return
     * 
     * @return this
     */
    public function getObjectList_by_taxonomy_slug($args = [])
    {
        $args = array_merge($this->default, $args);

        if (isset($args['tax_query'])) {
            $args['tax_query'][0]['taxonomy'] = $this->taxonomy;
        }

        $posts = Timber::get_posts($args);

        return $posts;
    }

    public function setCustomCategory($cat)
    {

        $this->_category = $cat;

        return $this;
    }

    public function getCustomCategory()
    {
        return $this->_category;
    }
}

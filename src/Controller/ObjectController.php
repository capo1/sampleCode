<?php


namespace App\Controller;

use App\Bundle\Framework\AbstractController;
use App\Objects\ObjectPosts;
use Timber;

class ObjectController extends AbstractController
{
    private $titles;
    function __construct()
    {

    }

    var $taxonomy = 'object_category';

    public function index($args = []): string
    {

        $context = $this->context();

        $posts = new ObjectPosts();


        $queried_posts =  $posts->getObjectList_by_taxonomy_slug(
            [
                'post-type' => 'object',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'field' => 'slug',
                        'terms' => $args['name'],
                    )
                )
            ]
        );
        
        $markers = [];
  
        foreach ($queried_posts as $key => $item) :
            $item->meta = get_fields($item->ID);
            $queried_posts[$key] = $item;
            $item->investitionName = $item->title;
            $markers[$key]['markers']= $item->meta['lokalizacja']['markers'];
            $markers[$key]['investitionName']= $item->title;
        endforeach;

        $context['contact'] = $this->get_taxonomy_acf_field($args['name']);

        $terms_object = get_term_by( 'slug' , $args['name'],$this->taxonomy);

        $computed_map = $this->computeMap($markers);

        $context['markers'] = $computed_map[0];

        $context['center_map'] = $computed_map[1];

        $context['object_posts'] = $queried_posts;
        $context['page_title'] = $terms_object->name ?? 'Default';
        $context['plugin_url']= WP_PLUGIN_URL;

        return $this->render('object/index.twig', $context);
    }

    public function single(): string
    {
        global $args;

        $context = $this->context();
        $context['post'] = Timber::get_post($args);

        $context = $this->context();

        $terms_object = get_the_terms( $context['post']->ID , $this->taxonomy );

        $context['contact'] = [];
        $kontakt = get_field('kontakt');
        
        foreach ($kontakt as $item) :
            $context['contact'][] = get_fields($item);
        endforeach;

        $context['archive_post_link'] = $terms_object[0]->slug;
        $context['page_title'] = $terms_object[0]->name ?? 'Default';
        $context['plugin_url']= WP_PLUGIN_URL;

        return $this->render('object/single.twig', $context);
    }

    public function get_taxonomy_acf_field($name)
    {

        $term = get_term_by('slug', $name, $this->taxonomy);

        if (!empty($term)) :

            return get_field('category_contact_persons', $this->taxonomy . '_' . $term->term_id);
        endif;

        return [];
    }

    public function computeMap($p)
    {
        $polygon = [];
        $map = [];
        foreach ($p as $item) :
            if (is_array($item) && !isset($item['lat'])) :
                foreach ($item['markers'] as  $it) :
                    $polygon[] = [$it['lat'], $it['lng']];
                    $it['label'] = '<strong>'.$item['investitionName']. '</strong><br/>'.$it['label'];
                    $map[]= $it;
                endforeach;
            endif;
        endforeach;     

        return [$map, $this->getCenter($polygon)];
    }
    function getCenter($polygon)
    {   
        if(count($polygon) == 0 ) return [null,null];
        $lat = 0.0;
        $lon = 0.0;
        foreach ($polygon as $marker) {
            $lat += $marker[0]; 
            $lon += $marker[1]; 
        }

        $centerlat = $lat / count($polygon);
        $centerlon = $lon / count($polygon);
        return [$centerlat, $centerlon];
    }
}

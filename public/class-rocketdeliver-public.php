<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://rocketdeliver.in
 * @since      1.0.0
 *
 * @package    RocketDeliver
 * @subpackage RocketDeliver/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    RocketDeliver
 * @subpackage RocketDeliver/public
 * @author     RocketDeliver <hello@rocketdeliver.in>
 */


class RocketDeliver_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * RocketDeliver domain
     *
     * @since    1.0.0
     * @access   private
     * @var      string $rocketdeliver_domain The domain enter in the admin
     */
    private $rocketdeliver_domain;

    public function __construct($plugin_name, $version, $is_dev)
    {
        $this->is_dev = $is_dev;
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->rocketdeliver_options = get_option($this->plugin_name);

        //$this->rocketdeliver_domain = $this->rocketdeliver_options['rocketdeliver_domain'];
        $this->rocketdeliver_domain = "rocketdeliver.in/images/source=";
    }


    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        return null;
    }


    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @version  1.0.0
     * @since    1.0.0
     *
     */
    public function enqueue_scripts()
    {
        $rocketdeliver_domain = $this->rocketdeliver_domain;
    }

    /*
     *
     * Added RocketDeliver url rewriter for css files
    *
     * @version  1.0.0
     * @since    1.0.0
     *
     */
    public function filter_rocket_style($html, $handle, $href, $media)
    {
        //$href = 'https://rocketdeliver.in/scripts/source=' + $href;
	$html = str_replace("href='", "href='https://rocketdeliver.in/styles/source=", $html);
	//$tag= preg_replace("href='", "href='https://rocketdeliver.in/scripts/source=", $tag);
        return $html;
    }

    /*
     *
     * Added RocketDeliver url rewriter for js files
     * @version  1.0.0
     * @since    1.0.0
     */
    public function filter_rocket_script($tag, $handle, $src)
    {
        //$href = 'https://rocketdeliver.in/scripts/source=' + $href;
	$tag = str_replace("src='", "src='https://rocketdeliver.in/scripts/source=", $tag);
	//$tag= preg_replace("href='", "href='https://rocketdeliver.in/scripts/source=", $tag);
        return $tag;
    }

    /*
     *
     * Modified RocketDeliver url rewriter for image files
     * @version  1.0.0
     * @since    1.0.0
     */
    public function filter_rocket_image($url, $post_id)
    {
        $url = 'https://rocketdeliver.in/images/source=' + $url;
	//$tag = str_replace("src='", "src='https://rocketdeliver.in/scripts/source=", $tag);
	//$tag= preg_replace("href='", "href='https://rocketdeliver.in/scripts/source=", $tag);
        return $url;
    }

    public function filter_rocketdeliver_wp_get_attachment_url($url, $post_id)
    {
        if ($this->is_dev || !$this->rocketdeliver_domain) {
            return $url;
        }

        $res_url = $this->rocketdeliver_get_url($post_id, false, $url);

        if (!$res_url) {
            return $url;
        }

        return $res_url;
    }


    /**
     * Filters the image srcset urls and convert them to rocketdeliver.
     * apply on filter wp_calculate_image_srcset
     * Forked from Cloudimage WP Plugin
     * (https://core.trac.wordpress.org/browser/tags/5.2/src/wp-includes/media.php#L1045)
     *
     * @param string $url
     * @param int $post_id
     *
     * @return array
     *
     * @since    1.0.0
     */
    public function filter_rocketdeliver_wp_calculate_image_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id)
    {
        if ($this->is_dev || !$this->rocketdeliver_domain) {

            return $sources;
        }


        foreach ($sources as $img_width => &$source) {
            $img_url = wp_get_attachment_image_src($attachment_id, 'full');
            $source['url'] = $this->rocketdeliver_build_url($img_url[0], null, ['w' => $img_width]);
        }

        return $sources;
    }


    /**
     * Filters whether to preempt the output of image_downsize().
     * (https://core.trac.wordpress.org/browser/tags/5.2/src/wp-includes/media.php#L182)
     * Forked from Cloudimage WP Plugin
     *
     * @param $downsize Whether to short-circuit the image downsize. Default false.
     * @param $id Attachment ID for image.
     * @param $size Size of image. Image size or array of width and height values (in that order).
     *                Default 'medium'.
     *
     * @return array|bool
     */
    public function filter_rocketdeliver_image_downsize($short_cut, $id, $size)
    {
        if ($short_cut || $this->is_dev || !$this->rocketdeliver_domain) {
            return false;
        }

        return $this->rocketdeliver_get_url($id, $size);
    }


    /**
     * Filters whether to modify the whole HTML return.
     * (https://core.trac.wordpress.org/browser/tags/5.2/src/wp-includes/media.php#L182)
     * Forked from Cloudimage WP Plugin
     *
     * @param $content the whole HTML of the page
     *
     * @return string
     *
     * @version  1.0.0
     * @since    1.0.0
     */
    public function filter_rocketdeliver_the_content($content)
    {
        return $content;
    }

    /**
     *
     * Public function that can be used in templates / by other developers
     *
     */


    /**
     * Return the Javascript script to init the lazysize
     * Forked from Cloudimage WP Plugin
     *
     * @param integer $id - Can be post_id or attachement_id
     * @param string|array $size Worpress size format
     * @param string|bool $url an simple url to transform to rocketdeliver URL
     *
     * @return string|array {
     * @type string $url - url of content with rocketdeliver format
     * @type int $width - width of image
     * @type int $height - height of the image
     * @type bool $intermediate - true if image is consider as intermediate
     * }
     * @since    1.0.0
     *
     */
    public function rocketdeliver_get_url($id, $size, $url = false)
    {

        if ($url) {
            // In this case $id -> $post_id
            if (wp_attachment_is_image($id)) {
                return $this->rocketdeliver_build_url($url);
            } else {
                return $this->rocketdeliver_build_asset_url($url);
                //return $this->rocketdeliver_build_url($url, 'proxy');
            }

        }

        // In this case $id -> $attachement_id

        $img_url = wp_get_attachment_url($id);
        $meta = wp_get_attachment_metadata($id);

        $rocketdeliver_parameters = $this->rocketdeliver_parse_parameters($size, $meta);

        $img_func = $rocketdeliver_parameters['func'];
        $img_size = $rocketdeliver_parameters['size'];
        $img_filters = $rocketdeliver_parameters['filters'];
        $size_meta = $rocketdeliver_parameters['size_meta'];


        $img_filters = apply_filters('rocketdeliver_filter_parameters', $img_filters, $id, $size, $meta);


        $width = isset($size_meta['width']) ? $size_meta['width'] : 0;
        $height = isset($size_meta['height']) ? $size_meta['height'] : 0;

        //Calculate blurhash only if we have thumb, checkbox is switched on and we dont't have already calculated value
        if (isset($meta['sizes']['thumbnail']['file']) && $this->rocketdeliver_use_blurhash && !isset($meta['image_meta']['blurhash'])) {
            //Get file path including upload dir
            $pathinfo = pathinfo($meta['file']);

            //Get main upload dir
            $wp_upload_dir = wp_upload_dir();

            //Get basedir
            $upload_dir = $wp_upload_dir['basedir'];

            //Clear of the path if organizing by year and month is not turned on
            $dir_name = ($pathinfo['dirname'] === ".") ? '/' : '/' . $pathinfo['dirname'] . '/';

            //Return 0 if the WordPress uploads directory does not exist or attachment is not image
            if (!is_dir($upload_dir) || !wp_attachment_is_image($id)) {
                return 0;
            }

            //Construct full path to file
            $full_file_path = $upload_dir . $dir_name . $meta['sizes']['thumbnail']['file'];

        }

        return [
            $this->rocketdeliver_build_url($img_url, $img_func, $img_size, $img_filters),
            $width,
            $height,
            true,
        ];
    }


    /**
     * Builds an RocketDeliver URL for a dynamically sized image.
     * Forked from Cloudimage WP Plugin
     *
     * @param string $img_url
     * @param string $img_func
     * @param array $img_size {
     * @type int|null $w
     * @type int|null $h
     * }
     * @param array $img_filters {
     * @type array|null $filter_name {
     * @type string $filter_value
     *  }
     * }
     *
     * @return string
     */
    public function rocketdeliver_build_asset_url($asset_url, $img_func = false,
                                         $img_size = false, $img_filters = false)
    {
        $domain = $this->rocketdeliver_domain;
        $url = $asset_url;

        if (substr($asset_url, 0, strlen('https://' . $domain )) !== 'https://' . $domain ) {
            $url = 'https://' . $domain . $asset_url;
        }

        if (strpos($url, '?') === false) {
            $url .= '?';
        }

        $url = str_replace('?&', '?', $url);

        $url = trim($url, '?');

        return $url;
    }

    public function rocketdeliver_build_url($img_url, $img_func = false,
                                         $img_size = false, $img_filters = false)
    {
        $domain = $this->rocketdeliver_domain;
        $url = $img_url;

        //Only make URLs rewriting if we dont't want to use JavaScript responsive plugin. Otherwise the JS should handle all the responsive optimization.

        if (substr($img_url, 0, strlen('https://' . $domain )) !== 'https://' . $domain ) {
            $url = 'https://' . $domain . $img_url;
        }

        if (strpos($url, '?') === false) {
            $url .= '?';
        }

        if ($img_func) {
            $url .= '&func=' . $img_func;
        }

        if ($img_size) {
            if (isset($img_size['w']) && $img_size['w'] > 0) {
                $url .= '&w=' . $img_size['w'];
            }

            if (isset($img_size['h']) && $img_size['h'] > 0) {
                $url .= '&h=' . $img_size['h'];
            }
	}

        if ($img_filters) {
            foreach ($img_filters as $filter_name => $filter_value) {
                $url .= '&' . $filter_name ($filter_value ? '=' . $filter_value : '');
            }
        }

        $url = str_replace('?&', '?', $url);

        $url = trim($url, '?');


        return $url;
    }




    /**
     *
     * Private function used by previous functions
     *
     */


    /**
     * Parse wordpress size and meta to get all RocketDeliver parameters
     * Forked from Cloudimage WP Plugin
     *
     * @param string|array $size
     * @param array $meta
     *
     * @return array
     */
    private function rocketdeliver_parse_parameters($size, $meta)
    {

        if (is_array($size)) {
            $size_meta = [
                "width" => $size[0],
                "height" => $size[1],
                "crop" => isset($size[2]) ? $size[2] : null
            ];
        } else {
            $size_meta = $this->rocketdeliver_image_sizes($size);
        }

        $filters = [];

        // Update $filters in the function if we need to set gravity
        $func = $this->rocketdeliver_define_function($size_meta, $meta, $filters);

        // Update $size_meta in the function if we sizes asked are bigger than original
        $size = $this->rocketdeliver_get_size($size_meta, $meta);

        return [
            'func' => $func,
            'size' => $size,
            'filters' => $filters,
            'size_meta' => $size_meta
        ];
    }


    /**
     * Define RocketDeliver function regarding the wordpress size asked
     * Forked from Cloudimage WP Plugin
     *
     * @param string|array $size
     * @param array $meta
     *
     * @return array
     */
    private function rocketdeliver_define_function($size_array, &$filters)
    {
        if ($size_array['crop']) {
            if ($size_array['width'] > 0 && $size_array['height'] > 0) {

                // if crop is array we need to define gravity center
                if (is_array($size_array['crop'])) {
                    $filters = array_merge(
                        $filters,
                        $this->rocketdeliver_convert_wordpress_crop_array_to_gravity_filters($size_array['crop'])
                    );
                }

                return 'crop';
            }
        }

        if ($size_array['width'] > 0 && $size_array['height'] > 0) {
            return 'fit';
        }

        return null;
    }


    /**
     * Define RocketDeliver function regarding the wordpress size asked
     * (https://havecamerawilltravel.com/photographer/wordpress-thumbnail-crop)
     * Forked from Cloudimage WP Plugin
     *
     * @param array $crop_array - Should be a crop array from Worpress specification
     *
     * @return array
     */
    private function rocketdeliver_convert_wordpress_crop_array_to_gravity_filters($crop_array)
    {
        if (count($crop_array) != 2) {
            return [];
        }

        $gravity = 'center';


        if (in_array('left', $crop_array) && in_array('top', $crop_array)) {
            $gravity = 'northwest';
        } elseif (in_array('center', $crop_array) && in_array('top', $crop_array)) {
            $gravity = 'north';
        } elseif (in_array('right', $crop_array) && in_array('top', $crop_array)) {
            $gravity = 'northeast';
        } elseif (in_array('center', $crop_array) && in_array('left', $crop_array)) {
            $gravity = 'west';
        } elseif (in_array('center', $crop_array) && in_array('right', $crop_array)) {
            $gravity = 'east';
        } elseif (in_array('bottom', $crop_array) && in_array('left', $crop_array)) {
            $gravity = 'southwest';
        } elseif (in_array('center', $crop_array) && in_array('bottom', $crop_array)) {
            $gravity = 'south';
        } elseif (in_array('bottom', $crop_array) && in_array('right', $crop_array)) {
            $gravity = 'southeast';
        }

        return ['gravity' => $gravity];
    }


    /**
     * Get RocketDeliver function regarding the wordpress size asked
     * Forked from Cloudimage WP Plugin
     *
     * @param array $size_array
     * @param array $meta
     *
     * @return array
     */
    private function rocketdeliver_get_size(&$size_array, $meta)
    {
        //Check if we have not set width and height
        if (!isset($meta['width']) && !isset($meta['height']))
        {
            return [
                'w' => 0,
                'h' => 0,
            ];
        }

        // use min not to resize the images to bigger size than original one
        $size_array['width'] = min($size_array['width'], $meta['width']);
        $size_array['height'] = min($size_array['height'], $meta['height']);

        return [
            'w' => $size_array['width'],
            'h' => $size_array['height'],
        ];
    }


    /**
     * Get all Wordpress declared image Sizes or only one specific size
     * Forked from Cloudimage WP Plugin
     *
     * @param string $size - value of one size to return the exact object and not an array
     *
     * @return array
     */
    private function rocketdeliver_image_sizes($size = null)
    {
        global $_wp_additional_image_sizes;


        $sizes = [];

        // Retrieve all possible image sizes generated by Wordpress
        $get_intermediate_image_sizes = get_intermediate_image_sizes();

        foreach ($get_intermediate_image_sizes as $_size) {
            // If the size parameter is a default Worpress size
            if (in_array($_size, ['thumbnail', 'medium', 'medium_large', 'large'])) {
                $array_size_construct = [
                    'width' => get_option($_size . '_size_w'),
                    'height' => get_option($_size . '_size_h'),
                    'crop' => get_option($_size . '_crop'),
                ];
            } else if (isset($_wp_additional_image_sizes[$_size])) {
                $array_size_construct = [
                    'width' => $_wp_additional_image_sizes[$_size]['width'],
                    'height' => $_wp_additional_image_sizes[$_size]['height'],
                    'crop' => $_wp_additional_image_sizes[$_size]['crop'],
                ];
            }

            if ($size != null && $size == $_size) {
                return $array_size_construct;
            }

            $sizes[$_size] = $array_size_construct;
        }

        if ($size != null) {
            return null;
        }

        return $sizes;
    }

}

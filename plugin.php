<?php
/*
Plugin Name: MD Yahoo Weather
Plugin URI: http://wp.meridadesign.com/widgets/md-yahoo-weather
Description: Simple widget to show the weather from selected country/city
Version: 0.1
Author: Iván Villamil
Author URI: ivillamil.meridadesign.com
Author Email: ivillamil@meridadesign.com
License:

  Copyright 2013

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class MD_Yahoo_Weather extends WP_Widget
{
    function __construct()
    {
        $this->init_plugin_constants();

        $widget_opts = array(
            'classname'     => PLUGIN_SLUG,
            'description'   => __('Yahoo simple weather plugin', PLUGIN_LOCALE)
        );

        $this->WP_Widget(PLUGIN_SLUG, __(PLUGIN_NAME,PLUGIN_LOCALE), $widget_opts);
        load_plugin_textdomain(PLUGIN_LOCALE, false, dirname(plugin_basename(__FILE__)) . '/lang/');

        $this->register_scripts_and_styles();
    }

    /**
     * Outputs the content of the widget.
     *
     * @args            The array of form elements
     * @instance        The Widget class instance
     */
    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);
        echo $before_widget;

        $title = empty( $instance['title'] ) ? '' : $instance['title'];
        $woeid = empty( $instance['woeid'] ) ? '' : $instance['woeid'];
        $unit  = empty( $instance['unit'] ) ? 'C' : $instance['unit'];

        $url   = 'http://weather.yahooapis.com/forecastrss';
        $params = array(
            'w' => (strlen( $woeid ) == 7 ? $woeid : '2346294'),
            'u' => (strlen( $unit ) > 0 ? $unit : 'f')
        );

        $fullurl = $url . '?w=' . $params['w'] . '&u=f';

        $resp = wp_remote_get( $fullurl );

        $body = $resp['body'];
        preg_match('/chill="(\d+)"/', $body, $arr);
        $chill = $arr[1];
        $f = $chill;
        $c = ceil((((float)$chill - 32)/1.8));
        $weather = '';

        switch($unit) {
            case 'f':
                $weather = $f . '° F';
                break;
            case 'c':
                $weather = $c . '° C';
                break;
            case 'b':
                $weather = $c . '° C / ' . $f . '° F';
                break;
        }

        include(WP_PLUGIN_DIR . '/' . PLUGIN_SLUG . '/views/widget.php');

        echo $after_widget;

    }


    /**
     * Processes the widget's options to be saved.
     *
     * @new_instance    The previous instance of values before the update.
     * @old_instance    The new instance of values to be generated via the update.
     */
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title']      = strip_tags(stripslashes( $new_instance['title'] ));
        $instance['woeid']      = strip_tags(stripslashes( $new_instance['woeid'] ));
        $instance['unit']       = strip_tags(stripslashes( $new_instance['unit'] ));

        return $instance;
    }


    /**
     * Generates the administration form for the widget.
     *
     * @instance    The array of keys and values for the widget.
     */
    function form($instance)
    {
        $instance = wp_parse_args(
            (array)$instance,
            array(
                'title' => __( '', PLUGIN_LOCALE ),
                'ccode' => '',
                'unit'  => 'F'
            )
        );

        $title = strip_tags( stripslashes($instance['title']) );
        $woeid = strip_tags( stripslashes($instance['woeid']) );
        $unit  = strip_tags( stripslashes($instance['unit']) );

        include(WP_PLUGIN_DIR . '/' . PLUGIN_SLUG . '/views/admin.php');
    }


    /* ---------------------------------------------------------------------- *
     * PRIVATE FUNCTIONS
     * ---------------------------------------------------------------------- */

    /**
     * Initializes constants used for convenience throughout
     * the plugin.
     */
    private function init_plugin_constants()
    {

        if(!defined('PLUGIN_LOCALE')) {
            define('PLUGIN_LOCALE', 'md-yahoo-weather');
        }

        if(!defined('PLUGIN_NAME')) {
            define('PLUGIN_NAME', 'MD Yahoo Weather');
        }

        if(!defined('PLUGIN_SLUG')) {
            define('PLUGIN_SLUG', 'MDYahooWeather');
        }

    }

    /**
     * Registers and enqueues stylesheets for the administration panel and the
     * public facing site.
     */
    private function register_scripts_and_styles()
    {
        if(is_admin()) {
            $this->load_file(PLUGIN_NAME, '/' . PLUGIN_SLUG . '/js/admin.js', true);
            $this->load_file(PLUGIN_NAME, '/' . PLUGIN_SLUG . '/css/admin.css');
        } else {
            $this->load_file( PLUGIN_NAME, '/' . PLUGIN_SLUG . '/js/widget.js', true );
            $this->load_file( PLUGIN_NAME, '/' . PLUGIN_SLUG . '/css/widget.css' );
        }
    }

    /**
     * Helper function for registering and enqueueing scripts and styles.
     *
     * @name    The     ID to register with WordPress
     * @file_path       The path to the actual file
     * @is_script       Optional argument for if the incoming file_path is a JavaScript source file.
     */
    private function load_file($name, $file_path, $is_script = false)
    {

        $url    = WP_PLUGIN_URL . $file_path;
        $file   = WP_PLUGIN_DIR . $file_path;

        if(file_exists($file)) {
            if($is_script) {
                wp_register_script($name, $url, 'jquery', null, true);
                wp_enqueue_script($name);
            } else {
                wp_register_style($name, $url, null, '0.0.3');
                wp_enqueue_style($name);
            }
        }
    }
}
add_action('widgets_init', create_function('','register_widget("MD_Yahoo_Weather");'));
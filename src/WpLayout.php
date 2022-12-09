<?php

namespace DoatKolom\Ui;

use WpCommander\Application;

class WpLayout extends Layout
{
    private static $instance = null;
    private $application;
    protected $data = [];

	/**
	 * @param Application $application
	 * @return static
	 */
    public static function instance( Application $application )
    {
        if ( is_null( static::$instance ) ) {
            static::$instance = new static( $application );
        }
        return static::$instance;
    }

    public function __construct( Application $application )
    {
        $this->application = $application;
    }

    public function enqueue_script()
    {
        wp_enqueue_style( 'doatkolom-ui-' . $this->application::$config['namespace'], $this->application->get_root_url() . 'vendor/doatkolom/ui/assets/css/app.css', [], $this->application::$config['version'] );
    }
}

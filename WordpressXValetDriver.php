<?php

use Valet\Drivers\ValetDriver;

class WordpressXValetDriver extends ValetDriver
{
    /**
     * Determine if the driver serves the request.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return bool
     */
    public function serves($sitePath, $siteName, $uri)
    {
        if (file_exists($sitePath.'/artisan')) {
            return false;
        } //This is laravel

        return true;
    }

    /**
     * Determine if the incoming request is for a static file.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string|false
     */
    public function isStaticFile($sitePath, $siteName, $uri)
    {
		
		$options = [
			'/app/wp-' => [
				'/public/',
				'/public_html/',
			],
			'/wp-' => [
				'',
			],
		];
		
		foreach($options as $key => $value) {
	        if(strpos($uri, $key) !== false) {
				foreach($value as $folder) {
		            if (is_file($staticFilePath = $sitePath.$folder.$uri)) {
		                return $staticFilePath;
		            }
				}
	            return false;
	        }
		}
		
        $options = [
            '',
            '/public/',
            '/public_html/',
            '/content/',
            '/app/',
            '/wp-admin/',
        ];

        foreach($options as $key) {
            if (file_exists($staticFilePath = $sitePath.$key.$uri)) {
                return $staticFilePath;
            }            
        }

        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    public function frontControllerPath($sitePath, $siteName, $uri)
    {
        $prefix = '';
        if(file_exists($sitePath.'/public_html')) {
             $prefix = '/public_html';
        }

		$options = [
			'/app/wp-',
			'/wp-',
		];

		foreach($options as $key) {
	        if(strpos($uri, $key) !== false) {
	            if(!is_file($sitePath.$prefix.$uri)) {
	                return $sitePath.$prefix.$uri.'/index.php';
	            }
	            return $sitePath.$prefix.$uri;
	        }
		}
		
        return $sitePath.$prefix.'/index.php';
    }
    //IMPORTANT: Wordpress usually lives in public_html instead of public.

}

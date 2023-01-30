<?php

use Valet\Drivers\ValetDriver;

class SampleValetDriver extends ValetDriver
{
	
	private $folders = [
		'/public/',
		'/public_html/',
		'/app/',
		'/public_html/app/',
		'',
	];
	
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
        if(!file_exists($sitePath.'/craft') && !file_exists($sitePath . '/vendor/themosis/framework') && !file_exists($sitePath.'/artisan')) {
            return true;
        }

        return false;
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
		foreach($this->folders as $key) {
            if (file_exists($staticFilePath = $sitePath.$key.$uri) && !is_dir($staticFilePath)) {
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

		if(!strpos($uri, '.php')) {
			if(!strpos($uri, 'wp-admin')) {
				$uri = '';
			}
			$uri .= '/index.php';
		}
		
        foreach($this->folders as $key) {
            if(file_exists($indexPath = $sitePath.$key.$uri)) {
                return $indexPath;
            }
        }
        return false;
    }
}

<?php

use Valet\Drivers\BasicValetDriver;

class ThemosisValetDriver extends BasicValetDriver
{
    /**
    * The public directory
    */
    const PUBLIC_DIR = 'htdocs';

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
        if(file_exists($sitePath . '/vendor/themosis/framework')) {
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
        if (file_exists($staticFilePath = $sitePath . '/' . self::PUBLIC_DIR . $uri)) {
            return $staticFilePath;
        } elseif ($this->isActualFile($staticFilePath = $sitePath . $uri)) {
            return $staticFilePath;
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
        if (strpos($uri, '/cms/') !== false) {
            $dynamicCandidates = [
                $this->asActualFile($sitePath, $uri),
                $this->asPhpIndexFileInDirectory($sitePath, $uri),
                $this->asHtmlIndexFileInDirectory($sitePath, $uri),
            ];
            foreach ($dynamicCandidates as $id => $candidate) {
                if ($this->isActualFile($candidate)) {
                    $_SERVER['PHP_SELF'] = $uri;
                    $_SERVER['SCRIPT_FILENAME'] = $candidate;
                    $_SERVER['SCRIPT_NAME'] = str_replace($sitePath, '', $candidate);
                    $_SERVER['DOCUMENT_ROOT'] = $sitePath;
			
                    return $candidate;
                }
            }
 	   
        }
        return $sitePath . '/'. self::PUBLIC_DIR .'/index.php';
    }

    /**
     * Redirect to uri with trailing slash.
     *
     * @param  string $uri
     * @return string
     */
    private function forceTrailingSlash($uri)
    {

        return $uri;
    }

    /**
     * Mutate the incoming URI.
     *
     * @param  string  $uri
     * @return string
     */
    public function mutateUri($uri)
    {
        return rtrim('/'. self::PUBLIC_DIR . $uri, '/');
    }
}
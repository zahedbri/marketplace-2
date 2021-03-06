<?php

namespace App\Tests;

use Faker\Provider\Base as FakerProviderBase;
use GuzzleHttp\Client;


/**
 * Faker provider that provides Unsplash images
 */
class UnsplashImageFakerProvider extends FakerProviderBase
{

    /**
     * Array of possible image IDs
     *
     * @var array|null
     */
    protected $ids;

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * Returns a random Unsplash image id
     *
     * @return int
     */
    public function unsplashId()
    {
        $ids = $this->fetchIds();

        return (int)$ids[array_rand($ids)]['id'];
    }

    /**
     * Creates an Unsplash image URL
     *
     * @param int      $width
     * @param int      $height
     * @param int|null $id Random if null
     *
     * @return string
     */
    public function unsplashUrl($width, $height, $id = null)
    {
        if ($id === null) {
            $id = $this->unsplashId();
        }

        return "https://picsum.photos/$width/$height/?image=$id";
    }

    /**
     * Downloads an Unsplash image. Returns the path to file.
     *
     * @param int      $width
     * @param int      $height
     * @param int|null $id Random if null
     * @param string   $dir
     *
     * @return string
     */
    public function unsplashImage($width, $height, $id = null, $dir = null)
    {
        if ($id === null) {
            $id = $this->unsplashId();
        }

        if ($dir == null) {
            $dir = sys_get_temp_dir();
        }

        $client = $this->getHttpClient();
        $name   = "faker_{$id}_{$width}_{$height}.jpg";
        $path   = join(DIRECTORY_SEPARATOR, [$dir, $name]);

        if (file_exists($path)) {
            return $path;
        }

        $client->get($this->unsplashUrl($width, $height, $id), [
            'sink' => $path,
        ]);

        return $path;
    }

    /**
     * Downloads an array of possible image IDs
     *
     * @return array
     */
    protected function fetchIds()
    {
        if ( ! $this->ids) {
            $client    = $this->getHttpClient();
            $response  = $client->get('https://picsum.photos/list');
            $this->ids = json_decode($response->getBody(), true);
        }

        return $this->ids;
    }

    /**
     * @return Client
     */
    protected function getHttpClient()
    {
        if ( ! $this->httpClient) {
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }
}
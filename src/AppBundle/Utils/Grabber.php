<?php

namespace AppBundle\Utils;

/**
 * Class Grabber
 * Gets files from the Internet
 * @author Igor Cherkashin aka JiSoft <jisoft.dn@gmail.com>
 */
class Grabber
{
    /** @const target site with images */
    const SITE_IMAGES = 'https://www.pexels.com/search';
    /** @const pause duration between copying image actions */
    const INTERVAL_SECONDS = 0;

    /** @var array   paths to the grabbed images  */
    private $images = [];
    /** @var array   queue of topics for looking images */
    private $topics = [];
    /** @var  int    target quantity of images for grabbing */
    private $targetCount;
    /** @var string  target photo's landscape */
    private $landscape = 'meduim';

    /**
     * Uploads to the specified $destFolder images by the specified topic
     * @param string $topic  your favorite images topic, for example: "music", "dogs", etc.
     * @param int $count     how many it will grab
     * @param string $destFolder the destination local folder to save uploaded images
     * @param string $orientation  needed photo's landscape medium|portrait|both
     * @return array   array of file names of grabbed images or an empty array
     */
    public function getImages($topic, $count, $destFolder, $orientation='medium')
    {
        if (empty($topic) || empty($count) || empty($destFolder))
            return [];
        if (empty($this->topics)) {
            $this->topics[] = $topic;
            $this->targetCount = $count;
        }

        if (in_array($orientation,['medium', 'portrait']))
            $this->landscape = $orientation;
        if ($orientation==='both')
            $this->landscape = '(medium|portrait)';

        list($urls, $related) = $this->parseTopic($topic);
        $this->topics = array_merge($this->topics, $related);

        $foundCount = count($urls);
        $toGet = $foundCount>=$count ? $count : $foundCount;
        $this->images = array_merge($this->images, $this->grab(array_slice($urls,0,$toGet), $destFolder));
        $totalCount = count($this->images);
        if ($totalCount>=$this->targetCount){
            $result = array_slice($this->images, 0, $this->targetCount);
            $this->topics = $this->images = [];
            $this->targetCount = 0;
            return $result;
        } else {
            return $this->getImages(next($this->topics), $this->targetCount-$totalCount, $destFolder);
        }
    }

    /**
     * Returns found items links at the web page by the specified topic
     * @param string $topic
     * @return array   result array [ urls => array, related_topic => array  ]
     */
    private function parseTopic($topic)
    {
        $targetUrl = self::SITE_IMAGES . '/' . $topic . '/';
        $response = substr(get_headers($targetUrl)[0], 9, 3);
        if ($response == '200') {
            $dump = []; $topics = [];
            $content = file_get_contents($targetUrl);
            if (preg_match('/(Sorry, no pictures found)+/',$content))
                return [[],[]];
            if (preg_match_all('/https:\/\/static\.pexels\.com\/photos\/\d+\/\S+'.$this->landscape.'\.(jpg|png|jpeg)/',$content,$urls)) {
                if (isset($urls[0]) && isset($urls[1])) {
                    $dump = $urls[0];
                }
            }
            if (preg_match('/<p class="title__more">(.|\n)?Related searches(.|\n)*?<\/p>/',$content,$related) && isset($related[0])) {
                if (preg_match_all('/\/search\/([a-z]+)\//',$related[0],$related) && isset($related[1])) {
                    $topics = $related[1];
                }
            }
            if ($dump && $topics)
                return [$dump, $topics];
        }
        return [[],[]];
    }

    /**
     * Copy images to the local folder by URLs
     * @param array $urls
     * @param string $destFolder
     * @return array  paths of grabbed files
     */
    private function grab(array $urls, $destFolder)
    {
        $context = stream_context_create([
            "http" => [
                "header" =>
                    "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36"
            ]
        ]);

        $paths = [];
        foreach ($urls as $url) {
            $ext = pathinfo($url ,PATHINFO_EXTENSION);
            if ($ext && strpos($url,'http://schema.org')==false) {
                if (copy($url, ($file=rtrim($destFolder,'/').'/'.uniqid('img').'.'.$ext), $context)) {
                    $paths[] = $file;
                    sleep(self::INTERVAL_SECONDS);
                }
            }
        }
        return $paths;
    }


}
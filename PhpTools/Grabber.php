<?php
namespace PhpTools;

use PhpTools\HttpClient;

class Grabber
{

    static $instance;
    private $links;

    /**
     * Singleton
     */
    private function __construct()
    {
    }

    /**
     * get single instance
     * @return Grabber
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * @param [type] $links
     * @return Grabber
     */
    public static function init($links)
    {
        $grabber = self::instance();
        if (\is_array($links)) {
            $grabber->setLinks($links);
        } elseif (\is_string($links)) {
            $grabber->setLink($links);
        } else {
            throw new \Exception('Bad input parameter; require array or string');
        }
        return $grabber;
    }

    /**
     * @param array $links
     * @return void
     */
    public function setLinks(array $links)
    {
        $this->links = $links;
    }

    /**
     * @param string $link
     * @return void
     */
    public function setLink(string $link)
    {
        $this->links = [$link];
    }

    
    /**
     * @return HttpResponse[]
     */
    public function grab()
    {
        if (!$this->links) {
            throw new \Exception('Not link to grab');
        }
        $out = [];
        foreach ($this->links as $_link) {
            if (\is_string($_link)) {
                $out[] = HttpClient::request($_link);
            } elseif (isset($_link['url']) && is_string($_link['url'])) {
                $out[] = HttpClient::request(
                    $_link['url'],
                    isset($_link['body']) ?? $_link['body'],
                    (isset($_link['method']) && \in_array($_link['method'], ['GET','POST','PUT'])) ?? $_link['method'],
                    isset($_link['timeout']) ? $_link['timeout'] : 30,
                    isset($_link['agent']) ? $_link['agent'] : 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:47.0) Gecko/20100101 Firefox/47.0'
                );
            } else {
                throw new \Exception('Not found links to grab');
            }
        }
        return $out;
    }
}

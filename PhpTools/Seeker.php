<?php

namespace PhpTools;

class Seeker
{
    static $instance;

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
     * @param [type] $patterns
     * @return Seeker
     */
    public static function init(string $content, $patterns)
    {
        $seeker = self::instance();
        if (\is_array($patterns)) {
            $seeker->setPatterns($patterns);
        } elseif (\is_string($patterns)) {
            $seeker->setPattern($patterns);
        } else {
            throw new \Exception('Bad input parameter; require array or string');
        }
        return $seeker;
    }

    private $content;
    private $patterns;

    /**
     * @param array $patterns
     * @return void
     */
    public function setPatterns(array $patterns)
    {
        $this->patterns = $patterns;
    }

    /**
     * @param string $pattern
     * @return void
     */
    public function setPattern(string $pattern)
    {
        $this->patterns[] = $pattern;
    }

    /**
     * @param [type] $content
     * @return void
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * @return HttpResponse[]
     */
    public function seek()
    {
        if (!$this->patterns) {
            throw new \Exception('No patterns to seek');
        }
        if (!$this->content) {
            throw new \Exception('No content where seek');
        }
        $out = [];
        foreach ($this->patterns as $_pattern) {
            if (!preg_match_all($_pattern, $content, $matches)) {
                $out[] = null;
            } else {
                $out[] = $matches;
            }
        }
        return $out;
    }
}

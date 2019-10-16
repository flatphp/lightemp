<?php namespace Lightemp;


class View
{
    public $path;
    public $cache = true;
    public $vars = [];

    public function __construct($path, array $conf = [])
    {
        $this->path = rtrim($path,'/') . '/';
        if (isset($conf['cache'])) {
            $this->cache = (bool)$conf['cache'];
        }
        if (isset($conf['vars'])) {
            $this->vars = $conf['vars'];
        }
    }

    /**
     * get template
     * @param string $file
     * @return string
     */
    public function template($file, array $vars = [])
    {
        $file = ltrim($file, '/');
        $this->vars = array_merge($this->vars, $vars);
        $cache_file = $this->getCachePath() . $file;

        if (!is_file($cache_file) || !$this->cache) {
            $compiler = new Compiler($this);
            $compiler->compile($file);
        }

        return $cache_file;
    }

    public function getCachePath()
    {
        return $this->path . '_cache/';
    }

    public function getVar($key, $default = null)
    {
        return isset($this->vars[$key]) ? $this->vars[$key] : $default;
    }
}
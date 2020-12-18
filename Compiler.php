<?php namespace Lightemp;


class Compiler
{
    protected $view;

    protected $rules = array(
        '#\{\$([a-zA-Z_].+?)\}#' => 'var',
        '#\{include\s+(.+?)\}#' => 'include',
        '#\{content\s+(.+?)\}#' => 'content',
    );

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function compile($template)
    {
        $cache_file = $this->view->getCachePath() . $template;
        $cache_path = dirname($cache_file);
        if (!is_dir($cache_path) && !mkdir($cache_path, 0755, true)) {
            throw new \RuntimeException('Unable to create view cache directory:'. $cache_path);
        }
        file_put_contents($cache_file, $this->parse($template));
    }

    public function parse($template)
    {
        $view_file = $this->view->path . $template;
        if (!is_file($view_file)) {
            throw new \RuntimeException('view file is not exists:'. $template);
        }
        $content = file_get_contents($view_file);
        foreach ($this->rules as $pattern => $handler) {
            $content = preg_replace_callback($pattern, function($matches) use($handler){
                return $this->handle($handler, $matches);
            }, $content);
        }
        return $content;
    }

    /**
     * @param string $handler
     * @param array $matches
     * @return string
     */
    protected function handle($handler, $matches)
    {
        $method = 'parse'.ucfirst($handler);
        return $this->$method($matches);
    }

    /**
     * #\{\$([a-zA-Z_].+?)\}#
     * e.g. {$test}, {$user['name']}, {$this->hello('aa', 'bb')}
     */
    protected function parseVar($matches)
    {
        return $this->view->getVar($matches[1]);
    }

    /**
     * #\{include\s+(.+?)\}#
     * e.g. {include part/head.php}
     */
    protected function parseInclude($matches)
    {
        return $this->parse($matches[1]);
    }

    /**
     * #\{content\s+(.+?)\}#
     * e.g. {content part/head.php}
     */
    protected function parseContent($matches)
    {
        ob_start();
        include $this->view->path . $matches[1];
        return ob_get_clean();
    }
}
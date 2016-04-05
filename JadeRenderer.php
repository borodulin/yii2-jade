<?php
namespace conquer\jade;

use Yii;
use Tale\Jade\Renderer;
use Tale\Jade\Parser\Node;
use Tale\Jade\Parser;

class JadeRenderer extends \yii\base\ViewRenderer
{
    /**
     * @var Renderer
     */
    protected $jade;
    
    public $cachePath = '@runtime/Jade/cache';
    public $cacheDuration = 0;
    
    /**
     * Jade options
     * @var array
     */
    public $options = [
        'pretty' => true,
    ];
    
    /**
     * @var bool
     */
    public $debug = false;
    
    /**
     * Associative array of $name => $callback
     * The callback should have the following signature:
     * (\Tale\Jade\Parser\Node $node, $indent, $newLine)
     * @var array
     */
    public $filters;
    
    /**
     * Search paths
     * @var array
     */
    public $paths;
    
    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\Object::init()
     */
    public function init()
    {
        parent::init();
        $this->cachePath = \Yii::getAlias(rtrim($this->cachePath, '\\/'));
        if (!file_exists($this->cachePath)) {
            mkdir($this->cachePath, 0777, true);
        }
        $this->jade = new Renderer($this->options);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \yii\base\ViewRenderer::render()
     */
    public function render($view, $file, $params)
    {
        $params['this'] = $view;        
        
        $filename = $this->cachePath . '/' . md5($file);
        if ($this->debug || !file_exists($filename) || (time() - filemtime($filename) >= $this->cacheDuration)) {
            $this->jade->addPath(dirname($file));
            if (is_array($this->paths)) {
                foreach ($this->paths as $path) {
                    $this->jade->addPath(\Yii::getAlias($path));
                }
            }
            if (is_array($this->filters)) {
                foreach ($this->filters as $name => $callback) {
                    $this->jade->addFilter($name, $callback);
                }
            }
            $data = $this->jade->compileFile($file);
            file_put_contents($filename, $data);
            if ($this->debug) {
                $parser = new Parser();
                \Yii::trace($parser->parse(file_get_contents($file)));
            }
        }
        return $view->renderPhpFile($filename, $params);
    }
}

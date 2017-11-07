<?php
/**
 * @link https://github.com/borodulin/yii2-jade
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-jade/blob/master/LICENSE
 */
namespace conquer\jade;

use Tale\Jade\Renderer;
use Yii;
use yii\base\ViewRenderer;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * @link http://jade.talesoft.codes/
 * @link https://github.com/Talesoft/tale-jade
 * @link http://sandbox.jade.talesoft.codes/
 * @link http://jade-lang.com/reference/
 *
 * Class JadeRenderer
 * @package conquer\jade
 */
class JadeRenderer extends ViewRenderer
{
    public $cachePath = '@runtime/Jade/cache';
    public $cacheDuration = 0;

    /**
     * Jade options
     * @var array
     */
    public $options;

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
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        $this->cachePath = Yii::getAlias(rtrim($this->cachePath, '\\/'));
        FileHelper::createDirectory($this->cachePath);
        $this->options = ArrayHelper::merge([
            'pretty' => $this->debug,
        ], (array)$this->options);
    }

    /**
     * @param \yii\base\View $view
     * @param string $file
     * @param array $params
     * @return string
     */
    public function render($view, $file, $params)
    {
        $filename = $this->cachePath . '/' . md5($file) . '.php';
        if ($this->debug || !file_exists($filename) || (time() - filemtime($filename) >= $this->cacheDuration)) {
            $jade = new Renderer($this->options);
            $jade->addPath(dirname($file));
            if (is_array($this->paths)) {
                foreach ($this->paths as $path) {
                    $jade->addPath(Yii::getAlias($path));
                }
            }
            if (is_array($this->filters)) {
                foreach ($this->filters as $name => $callback) {
                    $jade->addFilter($name, $callback);
                }
            }
            $data = $jade->compileFile($file);
            file_put_contents($filename, $data);
        }
        return $view->renderPhpFile($filename, $params);
    }
}

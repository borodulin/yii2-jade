Yii2 Jade
=========================

The Tale Jade Template Engine brings the popular and powerful Templating-Language Jade for Node.js to PHP!

http://jade.talesoft.io/


## Requirements

* YII 2.0
* PHP 5.4+

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/). 

To install, either run

```
$ php composer.phar require conquer/jade "*"
```
or add

```
"conquer/jade": "*"
```

to the ```require``` section of your `composer.json` file.



## Configuration
~~~php
return [
    //....
    'components' => [
        'view' => [       
            'defaultExtension' => 'jade', // Set jade as default to use base view file names without extension.
            'renderers' => [
            'jade' => [
                'class' => 'conquer\jade\JadeRenderer',
                'cacheDuration' => 0, // seconds. 0 - compile every time
            ],
        ],
    ],
];
~~~

## Usage
~~~php
class SiteController extends Controller
{
    // if you do not specify a defaultExtension, you should specify it here
    $layout = 'main.jade';
    ...
    public function actionIndex()
    {
        return $this->render('index', []);
        // or
        // return $this->render('index.jade', []);
    }
}
~~~

## Examples

### Main layout

~~~jade

-
    use yii\bootstrap\Html;
    use assets\AppAsset;
    /* @var $this \yii\web\View */
    /* @var $content string */
    AppAsset::register($this);
-$this->beginPage()
doctype html
html(lang=Yii::$app->language)
    // BEGIN HEAD
    head
        meta(charset="utf-8")
        title
            != Html::encode($this->title)
        meta(http-equiv="X-UA-Compatible", content="IE=edge")
        meta(content="width=device-width initial-scale=1", name="viewport")
        != Html::csrfMetaTags()
        meta(content="", name="description")
        meta(content="", name="author")
        link(rel="shortcut icon", href="/favicon.ico")
        - $this->head()
    // END HEAD
    body(class=$this->params['body-class'])
        - $this->beginBody()
        != $content
        - $this->endBody()
    - $this->endPage()

~~~
    
### Sub layout

~~~jade

-
    use yii\web\YiiAsset;
    use yii\base\Widget;
    use yii\bootstrap\Html;
    use yii\helpers\Url;
    /* @var $this \yii\web\View */
    /* @var $content string */
    $this->params['body-class'] = 'page-header-fixed page-sidebar-closed-hide-logo';
    YiiAsset::register($this);
    
- $this->beginContent('@views/layouts/main.jade');

// BEGIN HEADER & CONTENT DIVIDER
include header

.clearfix
// END HEADER & CONTENT DIVIDER
// BEGIN CONTAINER
.page-container
    // sidebar.jade is placed at @views/layouts/partials/sidebar.jade
    // jade engine just puts rendered content here
    include partials/sidebar
    != $content
// END CONTAINER
include partials/footer

- $this->endContent();
~~~

### Login

~~~jade

-
    use yii\helpers\Html
    use yii\bootstrap\ActiveForm
    $this->title = 'Login'
    $this->params['breadcrumbs'][] = $this->title;
    
.site-login
    h1 #{$view->title}
    p Please fill out the following fields to login:
    .row
        .col-lg-5
            - $form = ActiveForm::begin(['id' => 'login-form'])
            != $form->field($model, 'username')
            != $form->field($model, 'password')->passwordInput()
            != $form->field($model, 'rememberMe')->checkbox()
            .form-group
                !=Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button'])
            - ActiveForm::end()
~~~

## Links
* [Tale Jade for PHP](http://jade.talesoft.io/)

## License

Jade extension for Yii2 Framework is released under the MIT license.

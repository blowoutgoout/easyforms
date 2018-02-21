<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\helpers\UrlHelper;

/* @var $this yii\web\View */
/* @var $formModel app\models\Form */
/* @var $formDataModel app\models\FormData */
/* @var $showTheme boolean */
/* @var $showBox boolean */
/* @var $customJS boolean */

// Home URL
$homeUrl = Url::home(true);

// Base URL without schema
$baseUrl = UrlHelper::removeScheme($homeUrl);

$this->title = $formModel->name;

// Add body background to show box design
if ($showBox) {
    $this->registerCss("body { background-color: #EFF3F6; }");
} else {
    // Add theme
    if ($showTheme && isset($formModel->theme, $formModel->theme->css) && !empty($formModel->theme->css)) {
        $this->registerCss($formModel->theme->css);
    }
}

?>
<div class="container">
    <div class="row">
    <?php if ($showBox) : ?>
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
            <div class="form-view">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <?= Html::a(
                                Html::tag("span", Yii::$app->settings->get("app.name"), ["class" => "app-name"]),
                                $homeUrl,
                                [
                                    "title" => Yii::$app->settings->get("app.description"),
                                    "style" => 'text-decoration:none',
                                ]
                            ) ?>
                        </h3>
                    </div>
                    <div class="panel-body" style="padding: 20px">
                        <!-- Form App Code -->
                        <div id="c<?= $formModel->id ?>">
                            <?= Yii::t('app', 'Fill out my') ?> <a href="<?= Url::to(['app/form', 'id' => $formModel->id], true) ?>"><?= Yii::t('app', 'online form') ?></a>.
                        </div>
                        <script type="text/javascript">
                            (function(d, t) {
                                var s = d.createElement(t), options = {
                                    'id': <?= $formModel->id ?>,
                                    'theme': <?= $showTheme ?>,
                                    'customJS': <?= $customJS ?>,
                                    'container': 'c<?= $formModel->id ?>',
                                    'height': '<?= $formDataModel->height ?>px',
                                    'form': '<?= UrlHelper::removeScheme(Url::to(['/app/embed'], true)) ?>'
                                };
                                s.type= 'text/javascript';
                                s.src = '<?= Url::to('@web/static_files/js/form.widget.js', true) ?>';
                                s.onload = s.onreadystatechange = function() {
                                    var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return;
                                    try { (new EasyForms()).initialize(options).display() } catch (e) { }
                                };
                                var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr);
                            })(document, 'script');
                        </script>
                        <!-- End Form App Code -->
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="col-xm-12">
            <!-- Form App Code -->
            <div id="c<?= $formModel->id ?>">
                Fill out my <a href="#">online form</a>.
            </div>
            <script type="text/javascript">
                (function(d, t) {
                    var s = d.createElement(t), options = {
                        'id': <?= $formModel->id ?>,
                        'theme': <?= $showTheme ?>,
                        'customJS': <?= $customJS ?>,
                        'container': 'c<?= $formModel->id ?>',
                        'height': '<?= $formDataModel->height ?>px',
                        'form': '<?= UrlHelper::removeScheme(Url::to(['/app/embed'], true)) ?>'
                    };
                    s.type= 'text/javascript';
                    s.src = '<?= Url::to('@web/static_files/js/form.widget.js', true) ?>';
                    s.onload = s.onreadystatechange = function() {
                        var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return;
                        try { (new EasyForms()).initialize(options).display() } catch (e) { }
                    };
                    var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr);
                })(document, 'script');
            </script>
            <!-- End Form App Code -->
        </div>
    <?php endif; ?>
    </div>
</div>

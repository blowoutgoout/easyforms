<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\bundles\AppBundle;
use app\components\widgets\Alert;
use app\helpers\Language;

/* @var $this \yii\web\View */
/* @var $content string */

AppBundle::register($this);

$moduleID = $this->context->module->id;
$controllerID = $this->context->id;
$actionID = $this->context->action->id;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" dir="<?php echo Language::dir(); ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="generator" content="<?= Yii::$app->name ?> <?= Yii::$app->version ?>" />
    <link rel="shortcut icon" href="<?= Yii::$app->getHomeUrl() ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= Yii::$app->getHomeUrl() ?>favicon_32.png" sizes="32x32">
    <link rel="icon" href="<?= Yii::$app->getHomeUrl() ?>favicon_48.png" sizes="48x48">
    <link rel="icon" href="<?= Yii::$app->getHomeUrl() ?>favicon_96.png" sizes="96x96">
    <link rel="icon" href="<?= Yii::$app->getHomeUrl() ?>favicon_144.png" sizes="144x144">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) . ' | ' . Yii::$app->settings->get('app.name') ?></title>
    <?php $this->head() ?>

</head>
<body class="main <?= $controllerID ?> <?= $controllerID ?>-<?= $actionID ?>">

<?php $this->beginBody() ?>
<div class="wrap">

    <?php if (!Yii::$app->user->isGuest) : ?>

        <?php NavBar::begin([
            'brandLabel' => Html::tag("span", Yii::$app->settings->get("app.name"), ["class" => "app-name"]),
            'brandOptions' => [
                'title' => Yii::$app->settings->get("app.description"),
            ],
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]); ?>

        <?php echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'encodeLabels' => false,
            'items' => [
                ['label' => Yii::t("app", "Dashboard"), 'url' => ['/dashboard'],
                    'active' => 'app' === $moduleID && 'dashboard' === $controllerID],
                ['label' => Yii::t("app", "Forms"), 'url' => ['/form'],
                    'active' => 'app' === $moduleID && 'form' === $controllerID],
                ['label' => Yii::t("app", "Themes"), 'url' => ['/theme'],
                    'active' => 'app' === $moduleID && 'theme' === $controllerID,
                    'visible' => Yii::$app->user->can("edit_own_content")],
                ['label' => Yii::t("app", "Add-ons"), 'url' => ['/addons'],
                    'active' => 'app' !== $moduleID && 'user' !== $moduleID,
                    'visible' => Yii::$app->user->can("admin")],
                ['label' => Yii::t("app", "Users"), 'url' => ['/user/admin/index'],
                    'active' => 'user' === $moduleID && 'admin' === $controllerID,
                    'visible' => Yii::$app->user->can("admin")],
                ['label' => Html::img(Yii::$app->user->identity->profile->getAvatarUrl(), ['class' => 'avatar']) .
                    ' ' . Yii::$app->user->displayName,
                    'url' => ['/user'],
                    'options'=>['class'=>'dropdown hasAvatar'],
                    'template' => '<a href="{url}" class="href_class">{label}</a>',
                    'items' => [
                        ['label' => Yii::t("app", "Manage account"), 'url' => ['/user/profile'],
                            'active' => 'user' === $moduleID && 'default' === $controllerID, ],
                        ['label' => Yii::t("app", "Settings"), 'url' => ['/settings/site'],
                            'active' => 'app' === $moduleID && 'settings' === $controllerID,
                            'visible' => Yii::$app->user->can("admin")],
                        '<li class="divider"></li>',
                        ['label' => Yii::t("app", "Logout"), 'url' => ['/user/logout'],
                            'linkOptions' => ['data-method' => 'post', 'class' => 'highlighted']],
                    ]
                ],
            ],
        ]); ?>

        <?php NavBar::end(); ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'options' => ['class' => 'breadcrumb breadcrumb-arrow'],
                'itemTemplate' => "<li>{link}</li>\n", // template for all links
                'activeItemTemplate' => "<li class='active'><span>{link}</span></li>\n",
                'homeLink' => [
                    'label' => Yii::t('app', 'Dashboard'),
                    'url' => ['/dashboard'],
                ],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-right">&copy; <?= Yii::$app->settings->get("app.name") ?> <?= date('Y') ?></p>
            </div>
        </footer>

    <?php endif; ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

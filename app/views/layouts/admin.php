<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use kartik\sidenav\SideNav;
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
<body class="admin-page <?= $this->context->action->id ?>">

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php if (!Yii::$app->user->isGuest) : ?>
            <?php NavBar::begin([
                'brandLabel' => Html::tag("span", Yii::$app->settings->get("app.name"), ["class" => "app-name"]),
                'brandOptions' => ['title' => Yii::$app->settings->get("app.description")],
                'brandUrl' => Yii::$app->homeUrl,
                'options' => ['class' => 'navbar-inverse navbar-fixed-top']
            ]); ?>

            <?php echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'encodeLabels' => false,
                'items' => [
                    ['label' => Yii::t("app", "Dashboard"), 'url' => ['/dashboard'],
                        'active' => 'dashboard' === $controllerID],
                    ['label' => Yii::t("app", "Forms"), 'url' => ['/form'], 'active' => 'form' === $controllerID],
                    ['label' => Yii::t("app", "Themes"), 'url' => ['/theme'],
                        'active' => 'theme' === $controllerID, 'visible' => Yii::$app->user->can("edit_own_content")],
                    ['label' => Yii::t("app", "Add-ons"), 'url' => ['/addons'],
                        'active' => 'app' !== $moduleID && 'user' !== $moduleID,
                        'visible' => Yii::$app->user->can("admin")],
                    ['label' => Yii::t("app", "Users"), 'url' => ['/user/admin/index'],
                        'active' => 'admin' === $controllerID,
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
                <div class="row">
                    <div class="col-sm-3">
                        <?php echo SideNav::widget([
                            'type' => SideNav::TYPE_DEFAULT,
                            'heading' => '<i class="glyphicon glyphicon-cogwheel"></i> '.
                                Yii::t('app', 'Manage account'),
                            'iconPrefix' => 'glyphicon glyphicon-',
                            'items' => [
                                [
                                    'url' => Url::to(['/user/profile']),
                                    'label' => Yii::t("app", "Profile settings"),
                                    'icon' => 'user',
                                    'active' => ($actionID == 'profile'),
                                ],
                                [
                                    'url' => Url::to(['/user/change-username']),
                                    'label' => Yii::t("app", "Change username"),
                                    'icon' => 'user-key',
                                    'active' => ($actionID == 'change-username'),
                                ],
                                [
                                    'url' => Url::to(['/user/change-email']),
                                    'label' => Yii::t("app", "Change e-mail address"),
                                    'icon' => 'envelope',
                                    'active' => ($actionID == 'change-email'),
                                ],
                                [
                                    'url' => Url::to(['/user/change-password']),
                                    'label' => Yii::t("app", "Change password"),
                                    'icon' => 'keys',
                                    'active' => ($actionID == 'change-password'),
                                ],
                            ],
                        ]);
                        ?>
                        <?php if (Yii::$app->user->can("admin")) : ?>
                            <?php echo SideNav::widget([
                                'type' => SideNav::TYPE_DEFAULT,
                                'heading' => '<i class="glyphicon glyphicon-cogwheel"></i> '.Yii::t('app', 'Settings'),
                                'iconPrefix' => 'glyphicon glyphicon-',
                                'items' => [
                                    [
                                        'url' => Url::to(['/settings/site']),
                                        'label' => Yii::t("app", "Site settings"),
                                        'icon' => 'cogwheels',
                                        'active' => ($actionID == 'site'),
                                    ],
                                    [
                                        'url' => Url::to(['/settings/mail']),
                                        'label' => Yii::t("app", "Mail Server"),
                                        'icon' => 'inbox-out',
                                        'active' => ($actionID == 'mail'),
                                        'visible' => isset(Yii::$app->params['App.Mailer.transport']) && Yii::$app->params['App.Mailer.transport'] === 'smtp',
                                    ],
                                    [
                                        'url' => Url::to(['/settings/performance']),
                                        'label' => Yii::t("app", "Performance"),
                                        'icon' => 'settings',
                                        'active' => ($actionID == 'performance'),
                                    ],
                                ],
                            ]); ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-9">
                        <?= $content ?>
                    </div>
                </div>
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
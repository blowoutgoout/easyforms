<?php

use yii\helpers\Url;

/**
 * @var string $subject
 * @var \app\modules\user\models\User $user
 * @var \app\modules\user\models\UserToken $userToken
 */
?>

<h3><?= $subject ?></h3>

<p><?= Url::toRoute(["/user/login-callback", "token" => $userToken->token], true); ?></p>
<?php

use yii\helpers\Url;

/**
 * @var string $subject
 * @var \app\modules\user\models\User $user
 * @var \app\modules\user\models\Profile $profile
 * @var \app\modules\user\models\UserToken $userToken
 */
?>

<h3><?= $subject ?></h3>

<p><?= Yii::t("user", "Please confirm your email address by clicking the link below:") ?></p>

<p><?= Url::toRoute(["/user/confirm", "token" => $userToken->token], true); ?></p>
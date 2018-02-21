<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var array $actions
 */

$this->title = Yii::t('app', 'User');
?>
<div class="user-default-index">

    <table class="table table-bordered">
        <tr>
            <th>URL</th>
            <th>Description</th>
        </tr>

        <?php foreach ($actions as $url => $description) : ?>

            <tr>
                <td>
                    <strong><?= Html::a($url, [$url]) ?></strong>
                </td>
                <td>
                    <?= $description ?>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>

</div>
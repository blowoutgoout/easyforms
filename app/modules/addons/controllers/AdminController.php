<?php
/**
 * Copyright (C) Baluart.COM - All Rights Reserved
 *
 * @since 1.0
 * @author Balu
 * @copyright Copyright (c) 2015 - 2016 Baluart.COM
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 * @link http://easyforms.baluart.com/ Easy Forms
 */

namespace app\modules\addons\controllers;

use Yii;
use Exception;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\helpers\FileHelper;
use app\components\console\Console;
use app\modules\addons\models\Addon;
use app\modules\addons\models\AddonSearch;
use app\modules\addons\helpers\SetupHelper;

/**
 * DefaultController implements the CRUD actions for Addon model.
 */
class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * List of all Add-ons.
     *
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionIndex()
    {

        $this->refreshAddOnsList();

        $searchModel = new AddonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Reload index action
     */
    public function actionRefresh()
    {
        // Show success alert
        Yii::$app->getSession()->setFlash('success', Yii::t(
            'addon',
            'The Add-ons list has been refreshed successfully.'
        ));

        return $this->redirect(['index']);
    }

    /**
     * Add / Remove Add-Ons automatically.
     *
     * @throws InvalidConfigException
     */
    protected function refreshAddOnsList()
    {

        // Absolute path to addOns directory
        $addOnsDirectory = Yii::getAlias('@addons');

        // Each sub-directory name is an addOn id
        $addOns = FileHelper::scandir($addOnsDirectory);

        $installedAddOns = ArrayHelper::map(Addon::find()->select(['id','id'])->asArray()->all(), 'id', 'id');
        $newAddOns = array_diff($addOns, $installedAddOns);
        $removedAddOns = array_diff($installedAddOns, $addOns);

        // Install new addOns
        SetupHelper::install($newAddOns);

        // Update addOns versions
        SetupHelper::update($installedAddOns);

        // Uninstall removed addOns
        SetupHelper::uninstall($removedAddOns);

    }

    /**
     * Enable / Disable multiple Add-ons
     *
     * @param $status
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionUpdateStatus($status)
    {

        $addOns = Addon::findAll(['id' => Yii::$app->getRequest()->post('ids')]);

        if (empty($addOns)) {
            throw new NotFoundHttpException(Yii::t('addon', 'Page not found.'));
        } else {
            foreach ($addOns as $addOn) {
                $addOn->status = $status;
                $addOn->update();
            }
            Yii::$app->getSession()->setFlash(
                'success',
                Yii::t('addon', 'The selected items have been successfully updated.')
            );
            return $this->redirect(['index']);
        }
    }

    /**
     * Run DB Migration Up
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionInstall()
    {
        $addOns = Addon::findAll(['id' => Yii::$app->getRequest()->post('ids')]);

        if (empty($addOns)) {
            throw new NotFoundHttpException(Yii::t('addon', 'Page not found.'));
        } else {

            // Flag
            $success = true;

            foreach ($addOns as $addOn) {
                try {
                    $migrationPath = Yii::getAlias('@addons') . DIRECTORY_SEPARATOR . $addOn->id . DIRECTORY_SEPARATOR .
                        'migrations';
                    $migrationTable = 'migration_' . $addOn->id;

                    if (is_dir($migrationPath) && $success) { // Stop next migrations
                        if (!Console::isWindows() && Console::validPhpCliVersion()) {
                            // Run DB Migration on Background
                            $r = Console::migrate($migrationPath, 'migration_' . $addOn->id);
                            $result = $r === -1 ? false : true;
                        } else {
                            // Run DB Migration
                            $result = Console::runAction('migrate/up', [
                                'migrationPath' => $migrationPath,
                                'migrationTable' => $migrationTable,
                                'interactive' => false, // Force migrate db without confirmation
                            ]);
                        }
                        // Verify response
                        if (empty($result)) {
                            $success = false;
                        }
                    } else {
                        $success = false;
                    }

                } catch (Exception $e) {
                    $success = false;
                }

                if ($success) {
                    $addOn->status = $addOn::STATUS_ACTIVE;
                    $addOn->installed = $addOn::INSTALLED_ON;
                    $addOn->update();
                }

            }

            if ($success) {
                // Display success message
                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('addon', 'The selected items have been installed successfully.')
                );
            } else {
                // Display error message
                Yii::$app->getSession()->setFlash(
                    'danger',
                    Yii::t('addon', 'An error has occurred while installing your add-ons.')
                );
            }

            return $this->redirect(['index']);
        }
    }

    /**
     * Run DB Migration Down
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionUninstall()
    {
        $addOns = Addon::findAll(['id' => Yii::$app->getRequest()->post('ids')]);

        if (empty($addOns)) {
            throw new NotFoundHttpException(Yii::t('addon', 'Page not found.'));
        } else {
            // Flag
            $success = true;
            foreach ($addOns as $addOn) {
                try {
                    $migrationPath = Yii::getAlias('@addons') . DIRECTORY_SEPARATOR . $addOn->id . DIRECTORY_SEPARATOR .
                        'migrations';
                    $migrationTable = 'migration_' . $addOn->id;

                    if (is_dir($migrationPath) && $success) { // Stop next migration
                        // Response is always an empty string
                        if (!Console::isWindows() && Console::validPhpCliVersion()) {
                            // Run DB Migration on Background
                            $r = Console::migrateDown($migrationPath, 'migration_' . $addOn->id);
                            $result = $r === -1 ? false : true;
                        } else {
                            // Run DB Migration
                            $result = Console::runAction('migrate/down', [
                                'migrationPath' => $migrationPath,
                                'migrationTable' => $migrationTable,
                                'interactive' => false, // Force migrate db without confirmation
                            ]);
                        }
                        // Detect if an exception has been thrown
                        if (strpos($result, 'Exception') !== false) {
                            $success = false;
                        }
                    } else {
                        $success = false;
                    }

                } catch (Exception $e) {
                    $success = false;
                }

                if ($success) {
                    $addOn->status = $addOn::STATUS_INACTIVE;
                    $addOn->installed = $addOn::INSTALLED_OFF;
                    $addOn->update();
                }

            }
            if ($success) {
                // Display success message
                Yii::$app->getSession()->setFlash(
                    'success',
                    Yii::t('addon', 'The selected items have been uninstalled successfully.')
                );
            } else {
                // Display error message
                Yii::$app->getSession()->setFlash(
                    'danger',
                    Yii::t('addon', 'An error has occurred while uninstalling your add-ons.')
                );
            }
            return $this->redirect(['index']);
        }
    }
}

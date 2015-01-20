<?php

namespace common\components\nestsort;

use yii\web\AssetBundle;


class NestableAssets extends AssetBundle
{
    public $sourcePath = '@common/components/nestsort/assets';

    public $css = [
        'nest.css'
    ];
    public $js = [
        'jquery.nestable.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset'
    ];
}

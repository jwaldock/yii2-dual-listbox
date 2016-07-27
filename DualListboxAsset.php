<?php

namespace jwaldock\duallistbox;

use yii\web\AssetBundle;

/**
 * Asset bundle for Dual Listbox.
 *
 * @author Joel Waldock <joel.c.waldock@gmail.com>
 */
class DualListboxAsset extends AssetBundle
{
    public $sourcePath = '@bower/dual-listbox';
    
    public $js = [
        'js/dual-listbox.js',
    ];
    
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}

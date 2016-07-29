<?php
/**
 * @link https://github.com/jwaldock/yii2-dual-listbox/
 * @copyright Copyright (c) 2016 Joel Waldock
 */

namespace jwaldock\duallistbox;

use yii\web\AssetBundle;

/**
 * Asset bundle for Dual Listbox bootstrap style.
 *
 * @author Joel Waldock <joel.c.waldock@gmail.com>
 */
class DualListboxStyleAsset extends AssetBundle
{
    public $sourcePath = '@jwaldock/duallistbox/assets';

    public $css = [
        'dual-listbox.css'
    ];

    public $depends = [
        'jwaldock\duallistbox\DualListboxAsset'
    ];
}

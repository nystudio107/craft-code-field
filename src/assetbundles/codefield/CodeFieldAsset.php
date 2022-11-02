<?php
/**
 * Code Field plugin for Craft CMS
 *
 * Provides a Code Field that has a full-featured code editor with syntax highlighting & autocomplete
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2022 nystudio107
 */

namespace nystudio107\codefield\assetbundles\codefield;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    nystudio107
 * @package   CodeField
 * @since     3.0.0
 */
class CodeFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@nystudio107/codefield/assetbundles/codefield/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/CodeField.js',
        ];

        $this->css = [
            'css/CodeField.css',
        ];

        parent::init();
    }
}

<?php
/**
 * Code Field plugin for Craft CMS
 *
 * Provides a Code Field that has a full-featured code editor with syntax highlighting & autocomplete
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2022 nystudio107
 */

namespace nystudio107\codefield\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use craft\helpers\Json;
use nystudio107\codefield\assetbundles\codefield\CodeFieldAsset;
use yii\db\Schema;

/**
 * @author    nystudio107
 * @package   CodeField
 * @since     1.0.0
 */
class Code extends Field implements PreviewableFieldInterface
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The theme to use for the Code Editor field.
     */
    public $theme = 'vs';

    /**
     * @var string The language to use for the Code Editor field.
     */
    public $language = 'javascript';

    /**
     * @var bool Whether the Code Editor field display as a single line
     */
    public $singleLineField = false;

    /**
     * @var string The text that will be shown if the code field is empty.
     */
    public $placeholder = '';

    /**
     * @var bool Whether the language selector dropdown menu should be displayed.
     */
    public $showLanguageDropdown = true;

    /**
     * @var array The languages that should be listed in the language selector dropdown menu.
     */
    public $availableLanguages = [];

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('codefield', 'Code');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['theme', 'string'],
            ['theme', 'default', 'value' => 'vs'],
            ['language', 'string'],
            ['language', 'default', 'value' => 'javascript'],
            ['singleLineField', 'boolean'],
            ['placeholder', 'string'],
            ['placeholder', 'default', 'value' => ''],
            ['showLanguageDropdown', 'boolean'],
        ]);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        $monacoLanguages = require(__DIR__ . '/MonacoLanguages.php');
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'codefield/_components/fields/Code_settings',
            [
                'field' => $this,
                'monacoLanguages' => $monacoLanguages,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Register our asset bundle
        Craft::$app->getView()->registerAssetBundle(CodeFieldAsset::class);

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // Variables to pass down to our field JavaScript to let it namespace properly
        $jsonVars = [
            'id' => $id,
            'name' => $this->handle,
            'namespace' => $namespacedId,
            'prefix' => Craft::$app->getView()->namespaceInputId(''),
        ];
        $jsonVars = Json::encode($jsonVars);
        Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').CodeFieldCode(" . $jsonVars . ");");

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'codefield/_components/fields/Code_input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'orientation' => $this->getOrientation($element),
                'id' => $id,
                'namespacedId' => $namespacedId,
            ]
        );
    }
}

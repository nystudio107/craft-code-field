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
use craft\validators\ArrayValidator;
use nystudio107\codefield\validators\JsonValidator;
use yii\db\Schema;

/**
 * @author    nystudio107
 * @package   CodeField
 * @since     3.0.0
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
    public $singleLineEditor = false;

    /**
     * @var int The font size to use for the Code Editor field
     */
    public $fontSize = 14;

    /**
     * @var bool Whether line numbers should be displayed in the Code Editor field
     */
    public $lineNumbers = false;

    /**
     * @var bool Whether code folding should be used in the Code Editor field
     */
    public $codeFolding = false;

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
    public $availableLanguages = [
        'css',
        'graphql',
        'html',
        'javascript',
        'json',
        'markdown',
        'mysql',
        'php',
        'shell',
        'twig',
        'typescript',
        'yaml',
    ];

    /**
     * @var string JSON blob of Monaco [EditorOptions](https://microsoft.github.io/monaco-editor/api/interfaces/monaco.editor.IEditorOptions.html) that will override the default settings
     */
    public $monacoEditorOptions = '';

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
        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // Extract just the languages that have been selected for display
        $displayLanguages = [];
        if ($this->showLanguageDropdown) {
            $monacoLanguages = require(__DIR__ . '/MonacoLanguages.php');
            $decomposedLanguages = array_column($monacoLanguages, 'label', 'value');
            $displayLanguages = array_intersect_key($decomposedLanguages, array_flip($this->availableLanguages));
            $displayLanguages = array_map(function ($k, $v) {
                return ['value' => $k, 'label' => $v];
            }, array_keys($displayLanguages), array_values($displayLanguages));
        }
        $monacoOptionsOverride = Json::decodeIfJson($this->monacoEditorOptions);
        if ($monacoOptionsOverride === null || is_string($monacoOptionsOverride)) {
            $monacoOptionsOverride = [];
        }
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
                'displayLanguages' => $displayLanguages,
                'monacoOptionsOverride' => $monacoOptionsOverride,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['theme', 'in', 'range' => ['vs', 'vs-dark', 'hc-black']],
            ['theme', 'default', 'value' => 'vs'],
            ['language', 'string'],
            ['language', 'default', 'value' => 'javascript'],
            [['singleLineEditor', 'showLanguageDropdown', 'lineNumbers', 'codeFolding'], 'boolean'],
            ['placeholder', 'string'],
            ['placeholder', 'default', 'value' => ''],
            ['fontSize', 'integer'],
            ['fontSize', 'default', 'value' => 14],
            ['availableLanguages', ArrayValidator::class],
            ['monacoEditorOptions', JsonValidator::class],
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
}

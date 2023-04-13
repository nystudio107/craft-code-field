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
use craft\helpers\Html;
use craft\helpers\Json;
use craft\validators\ArrayValidator;
use GraphQL\Type\Definition\Type;
use nystudio107\codefield\gql\types\generators\CodeDataGenerator;
use nystudio107\codefield\models\CodeData;
use nystudio107\codefield\validators\JsonValidator;
use yii\db\Schema;

/**
 * @author    nystudio107
 * @package   CodeField
 * @since     4.0.0
 */
class Code extends Field implements PreviewableFieldInterface
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The theme to use for the Code Editor field.
     */
    public string $theme = 'auto';

    /**
     * @var string The language to use for the Code Editor field.
     */
    public string $language = 'javascript';

    /**
     * @var bool Whether the Code Editor field display as a single line
     */
    public bool $singleLineEditor = false;

    /**
     * @var int The font size to use for the Code Editor field
     */
    public int $fontSize = 14;

    /**
     * @var bool Whether line numbers should be displayed in the Code Editor field
     */
    public bool $lineNumbers = false;

    /**
     * @var bool Whether code folding should be used in the Code Editor field
     */
    public bool $codeFolding = false;

    /**
     * @var string The text that will be shown if the code field is empty.
     */
    public string $placeholder = '';

    /**
     * @var bool Whether the language selector dropdown menu should be displayed.
     */
    public bool $showLanguageDropdown = true;

    /**
     * @var string The default value the Code Field will be populated with
     */
    public string $defaultValue = '';

    /**
     * @var array The languages that should be listed in the language selector dropdown menu.
     */
    public array $availableLanguages = [
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
     * @var string|null The type of database column the field should have in the content table
     */
    public ?string $columnType = Schema::TYPE_TEXT;

    /**
     * @var string JSON blob of Monaco [EditorOptions](https://microsoft.github.io/monaco-editor/api/interfaces/monaco.editor.IEditorOptions.html) that will override the default settings
     */
    public string $monacoEditorOptions = '';

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
    public function normalizeValue($value, ElementInterface $element = null): mixed
    {
        if ($value instanceof CodeData) {
            return $value;
        }
        // Default config
        $config = [
            'value' => $this->defaultValue,
            'language' => $this->language,
        ];
        // Handle incoming values potentially being JSON or an array
        if (!empty($value)) {
            // Handle JSON-encoded values coming in
            if (\is_string($value)) {
                $jsonValue = Json::decodeIfJson($value);
                // If this is still a string (meaning it's not valid JSON), treat it as the value
                if (\is_string($jsonValue)) {
                    $config['value'] = $jsonValue;
                } else {
                    $value = $jsonValue;
                }
            }
            if (\is_array($value)) {
                $config = array_merge($config, array_filter($value));
            }
        }
        // Create and validate the model
        $codeData = new CodeData($config);
        if (!$codeData->validate()) {
            Craft::error(
                Craft::t('codefield', 'CodeData failed validation: ')
                . print_r($codeData->getErrors(), true),
                __METHOD__
            );
        }

        return $codeData;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        $monacoLanguages = require(__DIR__ . '/MonacoLanguages.php');
        $schemaFilePath = Craft::getAlias('@nystudio107/codefield/resources/IEditorOptionsSchema.json');
        $optionsSchema = @file_get_contents($schemaFilePath) ?: '';
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'codefield/_components/fields/Code_settings',
            [
                'field' => $this,
                'monacoLanguages' => $monacoLanguages,
                'optionsSchema' => $optionsSchema,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Get our id and namespace
        $id = Html::id($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // Extract just the languages that have been selected for display
        $displayLanguages = [];
        if ($this->showLanguageDropdown) {
            $monacoLanguages = require(__DIR__ . '/MonacoLanguages.php');
            $decomposedLanguages = array_column($monacoLanguages, 'label', 'value');
            $displayLanguages = array_intersect_key($decomposedLanguages, array_flip($this->availableLanguages));
            $displayLanguages = array_map(static function ($k, $v) {
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
    public function getContentGqlType(): Type|array
    {
        $typeArray = CodeDataGenerator::generateTypes($this);

        return [
            'name' => $this->handle,
            'description' => 'Code Editor field',
            'type' => array_shift($typeArray),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        return array_merge($rules, [
            ['theme', 'in', 'range' => ['auto', 'vs', 'vs-dark', 'hc-black']],
            ['theme', 'default', 'value' => 'auto'],
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
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        if ($this->columnType) {
            return $this->columnType;
        }

        return Schema::TYPE_TEXT;
    }
}

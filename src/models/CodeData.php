<?php
/**
 * Code Field plugin for Craft CMS
 *
 * Provides a Code Field that has a full-featured code editor with syntax highlighting & autocomplete
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2022 nystudio107
 */

namespace nystudio107\codefield\models;

use craft\base\Model;

/**
 * @author    nystudio107
 * @package   Code Field
 * @since     4.0.0
 */
class CodeData extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The value of the Code Editor field
     */
    public string $value = '';

    /**
     * @var string The language of the Code Editor field
     */
    public string $language = 'javascript';

    // Public Methods
    // =========================================================================

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['value', 'string'],
            ['language', 'string'],
        ]);

        return $rules;
    }
}

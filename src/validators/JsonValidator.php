<?php
/**
 * Code Field plugin for Craft CMS
 *
 * Provides a Code Field that has a full-featured code editor with syntax highlighting & autocomplete
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2022 nystudio107
 */

namespace nystudio107\codefield\validators;

use Craft;
use craft\helpers\Json;
use yii\validators\Validator;
use function is_string;

/**
 * @author    nystudio107
 * @package   Code Field
 * @since     3.0.0
 */
class JsonValidator extends Validator
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute): void
    {
        $value = $model->$attribute;
        $error = null;
        if (!empty($value) && is_string($value)) {
            $json = Json::decodeIfJson($value);
            if (!is_array($json)) {
                $error = Craft::t(
                    'codefield',
                    'This is not valid JSON',
                );
            }
        } else {
            $error = Craft::t('codefield', 'Is not a string.');
        }
        // If there's an error, add it to the model, and log it
        if ($error) {
            $model->addError($attribute, $error);
            Craft::error($error, __METHOD__);
        }
    }
}

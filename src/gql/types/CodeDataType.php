<?php
/**
 * Code Field plugin for Craft CMS
 *
 * Provides a Code Field that has a full-featured code editor with syntax highlighting & autocomplete
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2022 nystudio107
 */

namespace nystudio107\codefield\gql\types;

use craft\gql\base\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use nystudio107\codefield\models\CodeData;

/**
 * @author    nystudio107
 * @package   CodeField
 * @since     3.0.3
 */
class CodeDataType extends ObjectType
{
    /**
     * @inheritdoc
     */
    protected function resolve($source, $arguments, $context, ResolveInfo $resolveInfo)
    {
        /** @var CodeData $source */
        $fieldName = $resolveInfo->fieldName;
        return $source->$fieldName;
    }
}

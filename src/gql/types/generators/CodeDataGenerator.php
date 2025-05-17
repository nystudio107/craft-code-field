<?php
/**
 * Code Field plugin for Craft CMS
 *
 * Provides a Code Field that has a full-featured code editor with syntax highlighting & autocomplete
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2022 nystudio107
 */

namespace nystudio107\codefield\gql\types\generators;

use craft\gql\base\GeneratorInterface;
use craft\gql\GqlEntityRegistry;
use craft\gql\TypeLoader;
use GraphQL\Type\Definition\Type;
use nystudio107\codefield\fields\Code;
use nystudio107\codefield\gql\types\CodeDataType;

/**
 * @author    nystudio107
 * @package   CodeField
 * @since     3.0.3
 */
class CodeDataGenerator implements GeneratorInterface
{
    // Public Static methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function generateTypes($context = null): array
    {
        /** @var Code $context */
        $typeName = self::getName($context);

        $codeDataFields = [
            // static fields
            'language' => [
                'name' => 'language',
                'description' => 'The language of the Code Field',
                'type' => Type::string(),
            ],
            'value' => [
                'name' => 'value',
                'description' => 'The data entered into the Code Field',
                'type' => Type::string(),
            ],
        ];
        $codeDataType = GqlEntityRegistry::getEntity($typeName)
            ?: GqlEntityRegistry::createEntity($typeName, new CodeDataType([
                'name' => $typeName,
                'description' => 'This entity has all the CodeData properties',
                'fields' => function() use ($codeDataFields) {
                    return $codeDataFields;
                },
            ]));

        TypeLoader::registerType($typeName, function() use ($codeDataType) {
            return $codeDataType;
        });

        return [$codeDataType];
    }

    /**
     * @inheritdoc
     */
    public static function getName($context = null): string
    {
        /** @var Code $context */
        return $context->handle . '_CodeData';
    }
}

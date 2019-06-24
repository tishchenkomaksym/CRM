<?php


namespace App\Service\QaAgent;


abstract class SkillTestType
{
    public const TYPE_TECHNICAL = 'technical';
    public const TYPE_THEORETICAL = 'theoretical';

    /** @var array user friendly named type */
    protected static $typeName = [
        self::TYPE_TECHNICAL => 'Technical skill test',
        self::TYPE_THEORETICAL => 'Theoretical knowledge test',
    ];

    /**
     * @param string $typeShortName
     * @return string
     */
    public static function getTypeName($typeShortName): string
    {
        if (!isset(static::$typeName[$typeShortName])) {
            return "Unknown type ($typeShortName)";
        }

        return static::$typeName[$typeShortName];
    }

    /**
     * @return array<string>
     */
    public static function getAvailableTypes(): array
    {
        return [
            self::TYPE_TECHNICAL,
            self::TYPE_THEORETICAL,
        ];
    }
}
<?php

declare(strict_types=1);

namespace Odinns\CodingStyle;

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use Rector\ValueObject\PhpVersion;
use RectorLaravel\Rector\Class_\ModelCastsPropertyToCastsMethodRector;
use RectorLaravel\Rector\ClassMethod\AddGenericReturnTypeToRelationsRector;
use RectorLaravel\Rector\MethodCall\ValidationRuleArrayStringValueToArrayRector;

final class OdinnsRectorConfig
{
    public static function setup(RectorConfig $rectorConfig): void
    {
        $projectRoot = getcwd();

        if (is_string($projectRoot)) {
            self::loadProjectPhpstanConfig($rectorConfig, $projectRoot);
            self::loadLarastanBootstrap($rectorConfig, $projectRoot);
        }

        $rectorConfig->sets([
            SetList::DEAD_CODE,
            SetList::CODE_QUALITY,
            SetList::TYPE_DECLARATION,
            SetList::PRIVATIZATION,
            SetList::EARLY_RETURN,
            SetList::PHP_80,
            SetList::PHP_81,
            SetList::PHP_82,
            SetList::PHP_83,
            SetList::PHP_84,
            SetList::PHP_POLYFILLS,
        ]);

        $rectorConfig->rules([
            DeclareStrictTypesRector::class,
            AddGenericReturnTypeToRelationsRector::class,
            ModelCastsPropertyToCastsMethodRector::class,
            ValidationRuleArrayStringValueToArrayRector::class,
        ]);

        $rectorConfig->skip([
            AddOverrideAttributeToOverriddenMethodsRector::class,
        ]);

        $rectorConfig->importNames();
        $rectorConfig->phpVersion(PhpVersion::PHP_84);
    }

    private static function loadProjectPhpstanConfig(RectorConfig $rectorConfig, string $projectRoot): void
    {
        $phpstanConfig = match (true) {
            is_file($projectRoot . '/phpstan.neon') => $projectRoot . '/phpstan.neon',
            is_file($projectRoot . '/phpstan.neon.dist') => $projectRoot . '/phpstan.neon.dist',
            default => null,
        };

        if ($phpstanConfig !== null) {
            $rectorConfig->phpstanConfig($phpstanConfig);
        }
    }

    private static function loadLarastanBootstrap(RectorConfig $rectorConfig, string $projectRoot): void
    {
        $larastanBootstrap = $projectRoot . '/vendor/larastan/larastan/bootstrap.php';

        if (is_file($larastanBootstrap)) {
            $rectorConfig->bootstrapFiles([$larastanBootstrap]);
        }
    }
}

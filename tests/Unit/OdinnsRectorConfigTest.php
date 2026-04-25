<?php

declare(strict_types=1);

use Odinns\CodingStyle\OdinnsRectorConfig;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

it('can be applied to a Rector config', function (): void {
    $rectorConfig = new RectorConfig();

    OdinnsRectorConfig::setup($rectorConfig);

    expect($rectorConfig)->toBeInstanceOf(RectorConfig::class);
});

it('registers strict types as a shared Rector rule', function (): void {
    OdinnsRectorConfig::setup(new RectorConfig());

    expect(SimpleParameterProvider::provideArrayParameter(Option::REGISTERED_RECTOR_RULES))
        ->toContain(DeclareStrictTypesRector::class);
});

it('ships copy-ready consumer stubs', function (): void {
    expect(__DIR__ . '/../../stubs/rector.php.stub')->toBeFile()
        ->and(__DIR__ . '/../../stubs/phpstan.neon.stub')->toBeFile()
        ->and(file_get_contents(__DIR__ . '/../../stubs/rector.php.stub'))->toContain('OdinnsRectorConfig::setup')
        ->and(file_get_contents(__DIR__ . '/../../stubs/phpstan.neon.stub'))->toContain('vendor/odinns/coding-style/config/larastan.neon');
});

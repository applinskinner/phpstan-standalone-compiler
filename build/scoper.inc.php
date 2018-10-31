<?php declare(strict_types=1);

return [
    'prefix' => null,
    'finders' => [],
    'patchers' => [
        function (string $filePath, string $prefix, string $content): string {
            if (strpos($filePath, '.neon') !== strlen($filePath) - 5) {
                return $content;
            }
            return str_replace('PhpParser\\', sprintf('%s\\PhpParser\\', $prefix), $content);
        },
        function (string $filePath, string $prefix, string $content): string {
            if ($filePath !== 'bin/phpstan') {
                return $content;
            }
            return str_replace('__DIR__ . \'/..', '\'phar://phpstan.phar', $content);
        },
        function (string $filePath, string $prefix, string $content): string {
            if ($filePath !== 'vendor/nette/di/src/DI/Compiler.php') {
                return $content;
            }
            return str_replace('|Nette\\\\DI\\\\Statement', sprintf('|\\\\%s\\\\Nette\\\\DI\\\\Statement', $prefix), $content);
        },
        function (string $filePath, string $prefix, string $content): string {
            if ($filePath !== 'src/Testing/TestCase.php') {
                return $content;
            }
            return str_replace(sprintf('\\%s\\PHPUnit\\Framework\\TestCase', $prefix), '\\PHPUnit\\Framework\\TestCase', $content);
        },
        function (string $filePath, string $prefix, string $content): string {
            if ($filePath !== 'src/Testing/LevelsTestCase.php') {
                return $content;
            }
            return str_replace(
                [sprintf('\\%s\\PHPUnit\\Framework\\AssertionFailedError', $prefix), sprintf('\\%s\\PHPUnit\\Framework\\TestCase', $prefix)],
                ['\\PHPUnit\\Framework\\AssertionFailedError', '\\PHPUnit\\Framework\\TestCase'],
                $content
            );
        },
    ],
    'whitelist' => [
        'PHPStan\*',
    ],
];

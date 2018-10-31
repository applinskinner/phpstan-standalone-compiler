# PHAR Compiler for PHPStan (standalone)

A fork of the [phpstan-compiler](https://github.com/phpstan/phpstan-compiler) to re-scope the PhpParser namespace.

This allows PHPStan to be used for projects depending on PhpParser 2.x/3.x, but with the caveat that it will not work with PHPStan extensions.

## Installation

```bash
git clone https://github.com/applinskinner/phpstan-standalone-compiler
cd phpstan-standalone-compiler
composer install
```

## Compile the PHAR

```bash
php bin/compile [version] [repository]
```

Default `version` is `master`, and default `repository` is `https://github.com/phpstan/phpstan.git`.

The compiled PHAR will be in `tmp/phpstan.phar`.

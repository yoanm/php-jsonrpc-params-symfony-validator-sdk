# JSON-RPC params symfony validator
[![License](https://img.shields.io/github/license/yoanm/php-jsonrpc-params-symfony-validator-sdk.svg)](https://github.com/yoanm/php-jsonrpc-params-symfony-validator-sdk) [![Code size](https://img.shields.io/github/languages/code-size/yoanm/php-jsonrpc-params-symfony-validator-sdk.svg)](https://github.com/yoanm/php-jsonrpc-params-symfony-validator-sdk) [![Dependabot Status](https://api.dependabot.com/badges/status?host=github&repo=yoanm/php-jsonrpc-params-symfony-validator-sdk)](https://dependabot.com)


[![Scrutinizer Build Status](https://img.shields.io/scrutinizer/build/g/yoanm/php-jsonrpc-params-symfony-validator-sdk.svg?label=Scrutinizer&logo=scrutinizer)](https://scrutinizer-ci.com/g/yoanm/php-jsonrpc-params-symfony-validator-sdk/build-status/master) [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/yoanm/php-jsonrpc-params-symfony-validator-sdk/master.svg?logo=scrutinizer)](https://scrutinizer-ci.com/g/yoanm/php-jsonrpc-params-symfony-validator-sdk/?branch=master) [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/yoanm/php-jsonrpc-params-symfony-validator-sdk/master.svg?logo=scrutinizer)](https://scrutinizer-ci.com/g/yoanm/php-jsonrpc-params-symfony-validator-sdk/?branch=master)

[![Travis Build Status](https://img.shields.io/travis/com/yoanm/php-jsonrpc-params-symfony-validator-sdk/master.svg?label=Travis&logo=travis)](https://travis-ci.com/yoanm/php-jsonrpc-params-symfony-validator-sdk) <!-- NOT WORKING WITH travis-ci.com [![Travis PHP versions](https://img.shields.io/travis/php-v/yoanm/php-jsonrpc-params-symfony-validator-sdk.svg?logo=travis)](https://php.net/) --> [![Travis Symfony Versions](https://img.shields.io/badge/Symfony-v3%20%2F%20v4-8892BF.svg?logo=travis)](https://php.net/)

[![Latest Stable Version](https://img.shields.io/packagist/v/yoanm/jsonrpc-params-symfony-validator-sdk.svg)](https://packagist.org/packages/yoanm/jsonrpc-params-symfony-validator-sdk) [![Packagist PHP version](https://img.shields.io/packagist/php-v/yoanm/jsonrpc-params-symfony-validator-sdk.svg)](https://packagist.org/packages/yoanm/jsonrpc-params-symfony-validator-sdk)

Simple JSON-RPC params validator that use Symfony validator component

See [yoanm/symfony-jsonrpc-params-validator](https://github.com/yoanm/symfony-jsonrpc-params-validator) for automatic dependency injection.

See [yoanm/jsonrpc-params-symfony-constraint-doc-sdk](https://github.com/yoanm/php-jsonrpc-params-symfony-constraint-doc-sdk) for documentation generation.

## Versions

- Symfony v3/4 - PHP >=7.0 : `^v1.0` 
- Symfony v4/5 - PHP >=7.1 : `^v2.0`

⚠️⚠️ `v0.2.0` is replaced by `v1.0.0` ! ⚠️⚠️
  
⚠️⚠️ `v0.3.0` was badly taggued, used `v2.0.0` instead ! ⚠️⚠️

## How to use

In order to be validated, a JSON-RPC method must : 
 - Implements `JsonRpcMethodInterface` from [`yoanm/jsonrpc-server-sdk`](https://github.com/yoanm/php-jsonrpc-server-sdk)
 - Implements [`MethodWithValidatedParamsInterface`](./src/Infra/JsonRpcParamsValidator.php)

### With [`yoanm/jsonrpc-server-sdk`](https://github.com/yoanm/php-jsonrpc-server-sdk)
Create the validator and inject it into request handler : 
```php
$requestHandler->setMethodParamsValidator(
  new JsonRpcParamsValidator(
    (new ValidatorBuilder())->getValidator()
  )
);
```

Then you can send JSON-RPC request string to the server and any method wich implements `MethodWithValidatedParamsInterface` will be validated.

### Standalone 
```php
use Symfony\Component\Validator\ValidatorBuilder;
use Yoanm\JsonRpcParamsSymfonyValidator\Infra\JsonRpcParamsValidator;

// Create the validator
$paramsValidator = new JsonRpcParamsValidator(
  (new ValidatorBuilder())->getValidator()
);

// Validate a given JSON-RPC method instance against a JSON-RPC request
$violationList = $paramsValidator->validate($jsonRpcRequest, $jsonRpcMethod);
```

### Params validation example
```php
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Yoanm\JsonRpcParamsSymfonyValidator\Domain\MethodWithValidatedParamsInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;

class MethodExample implements JsonRpcMethodInterface, MethodWithValidatedParamsInterface
{
  /**
   * {@inheritdoc}
   */
  public function apply(array $paramList = null)
  {
    return 'result';
  }

  public function getParamsConstraint(): Constraint
  {
    return new Collection(
      [
        'fields' => [
          'fieldA' => new NotNull(),
          'fieldB' => new NotBlank(),
        ],
      ]
    );
  }
}
```

### Violations format
Each violations will have the following format :
```php
[
  'path' => 'property_path',
  'message' => 'violation message',
  'code' => 'violation_code'
]
```

## Contributing
See [contributing note](./CONTRIBUTING.md)

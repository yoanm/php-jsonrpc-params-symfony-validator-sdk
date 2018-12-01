# JSON-RPC params symfony validator
[![License](https://img.shields.io/github/license/yoanm/php-jsonrpc-params-symfony-validator-sdk.svg)](https://github.com/yoanm/php-jsonrpc-params-symfony-validator-sdk) [![Code size](https://img.shields.io/github/languages/code-size/yoanm/php-jsonrpc-params-symfony-validator-sdk.svg)](https://github.com/yoanm/php-jsonrpc-params-symfony-validator-sdk) [![Dependencies](https://img.shields.io/librariesio/github/yoanm/php-jsonrpc-params-symfony-validator-sdk.svg)](https://libraries.io/packagist/yoanm%2Fjsonrpc-params-symfony-validator-sdk)

[![Scrutinizer Build Status](https://img.shields.io/scrutinizer/build/g/yoanm/php-jsonrpc-params-symfony-validator-sdk.svg?label=Scrutinizer&logo=scrutinizer)](https://scrutinizer-ci.com/g/yoanm/php-jsonrpc-params-symfony-validator-sdk/build-status/master) [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/yoanm/php-jsonrpc-params-symfony-validator-sdk/master.svg?logo=scrutinizer)](https://scrutinizer-ci.com/g/yoanm/php-jsonrpc-params-symfony-validator-sdk/?branch=master) [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/yoanm/php-jsonrpc-params-symfony-validator-sdk/master.svg?logo=scrutinizer)](https://scrutinizer-ci.com/g/yoanm/php-jsonrpc-params-symfony-validator-sdk/?branch=master)

[![Travis Build Status](https://img.shields.io/travis/com/yoanm/php-jsonrpc-params-symfony-validator-sdk/master.svg?label=Travis&logo=travis)](https://travis-ci.com/yoanm/php-jsonrpc-params-symfony-validator-sdk) [![Travis PHP versions](https://img.shields.io/travis/php-v/yoanm/php-jsonrpc-params-symfony-validator-sdk.svg?logo=travis)](https://travis-ci.com/yoanm/php-jsonrpc-params-symfony-validator-sdk) [![Travis Symfony Versions](https://img.shields.io/badge/Symfony-v3%20%2F%20v4-8892BF.svg?logo=travis)](https://php.net/)

[![Latest Stable Version](https://img.shields.io/packagist/v/yoanm/jsonrpc-params-symfony-validator-sdk.svg)](https://packagist.org/packages/yoanm/jsonrpc-params-symfony-validator-sdk) [![Packagist PHP version](https://img.shields.io/packagist/php-v/yoanm/jsonrpc-params-symfony-validator-sdk.svg)](https://packagist.org/packages/yoanm/jsonrpc-params-symfony-validator-sdk)

Simple JSON-RPC params validator that use Symfony validator component

## How to use

In order to be validated, a JSON-RPC method must : 
 - Implements `JsonRpcMethodInterface` from [`yoanm/jsonrpc-server-sdk`](https://github.com/yoanm/php-jsonrpc-server-sdk)
 - Implements [`MethodWithValidatedParamsInterface`](./src/Infra/JsonRpcParamsValidator.php)
 
Then use it as following : 
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

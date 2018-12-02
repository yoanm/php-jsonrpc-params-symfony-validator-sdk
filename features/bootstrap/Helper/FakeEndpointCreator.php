<?php
namespace Tests\Functional\BehatContext\Helper;

use DemoApp\Method\BasicMethod;
use DemoApp\Method\BasicMethodWithRequiredParams;
use DemoApp\Resolver\JsonRpcMethodResolver;
use Symfony\Component\Validator\ValidatorBuilder;
use Tests\Functional\BehatContext\App\Method\AbstractMethod;
use Yoanm\JsonRpcParamsSymfonyValidator\Infra\JsonRpcParamsValidator;
use Yoanm\JsonRpcServer\App\Creator\ResponseCreator;
use Yoanm\JsonRpcServer\App\Handler\ExceptionHandler;
use Yoanm\JsonRpcServer\App\Handler\JsonRpcRequestHandler;
use Yoanm\JsonRpcServer\App\Serialization\JsonRpcCallDenormalizer;
use Yoanm\JsonRpcServer\App\Serialization\JsonRpcCallResponseNormalizer;
use Yoanm\JsonRpcServer\App\Serialization\JsonRpcCallSerializer;
use Yoanm\JsonRpcServer\App\Serialization\JsonRpcRequestDenormalizer;
use Yoanm\JsonRpcServer\App\Serialization\JsonRpcResponseNormalizer;
use Yoanm\JsonRpcServer\Infra\Endpoint\JsonRpcEndpoint;

class FakeEndpointCreator
{
    /**
     * @return JsonRpcEndpoint
     */
    public function create() : JsonRpcEndpoint
    {
        /** @var AbstractMethod[] $methodList */
        $methodList = [
            'basic-method' => new BasicMethod(),
            'basic-method-with-params' => new BasicMethodWithRequiredParams(),
        ];

        $methodResolver = new JsonRpcMethodResolver();

        foreach ($methodList as $methodName => $method) {
            $methodResolver->addJsonRpcMethod($methodName, $method);
        }

        $jsonRpcSerializer = new JsonRpcCallSerializer(
            new JsonRpcCallDenormalizer(
                new JsonRpcRequestDenormalizer()
            ),
            new JsonRpcCallResponseNormalizer(
                new JsonRpcResponseNormalizer()
            )
        );
        $responseCreator = new ResponseCreator();
        $requestHandler = new JsonRpcRequestHandler($methodResolver, $responseCreator);
        $exceptionHandler = new ExceptionHandler($responseCreator);
        $endpoint = new JsonRpcEndpoint($jsonRpcSerializer, $requestHandler, $exceptionHandler);
        $requestHandler->setMethodParamsValidator(
            new JsonRpcParamsValidator(
                (new ValidatorBuilder())->getValidator()
            )
        );

        return $endpoint;
    }
}

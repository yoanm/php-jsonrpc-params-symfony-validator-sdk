<?php
namespace Yoanm\JsonRpcParamsSymfonyValidator\Infra;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Yoanm\JsonRpcParamsSymfonyValidator\Domain\MethodWithValidatedParamsInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodParamsValidatorInterface;
use Yoanm\JsonRpcServer\Domain\Model\JsonRpcRequest;

/**
 * Class JsonRpcParamsValidator
 */
class JsonRpcParamsValidator implements JsonRpcMethodParamsValidatorInterface
{
    /** @var ValidatorInterface */
    private $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(JsonRpcRequest $jsonRpcRequest, JsonRpcMethodInterface $method) : array
    {
        $violationList = [];
        if (!$method instanceof MethodWithValidatedParamsInterface) {
            return $violationList;
        }
        $sfViolationList = $this->validator->validate(
            $jsonRpcRequest->getParamList(),
            $method->getParamsConstraint()
        );

        foreach ($sfViolationList as $violation) {
            /** @var ConstraintViolationInterface $violation */
            $violationList[] = [
                'path' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
                'code' => $violation->getCode(),
            ];
        }

        return $violationList;
    }
}

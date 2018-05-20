<?php
namespace Yoanm\JsonRpcParamsSymfonyValidator\Infra;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Yoanm\JsonRpcParamsSymfonyValidator\Model\MethodWithValidatedParams;
use Yoanm\JsonRpcServer\Domain\Event\Action\ValidateParamsEvent;

/**
 * Class JsonRpcParamsValidator
 */
class JsonRpcParamsValidator
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

    /**
     * @param ValidateParamsEvent $event
     */
    public function validate(ValidateParamsEvent $event)
    {
        $method = $event->getMethod();
        if (!$method instanceof MethodWithValidatedParams) {
            return;
        }
        foreach ($this->validator->validate($event->getParamList(), $method->getParamsConstraint()) as $violation) {
            $event->addViolation([
                'path' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
                'code' => $violation->getCode(),
            ]);
        }
    }
}

<?php
namespace DemoApp\Method;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Yoanm\JsonRpcParamsSymfonyValidator\Domain\MethodWithValidatedParamsInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;

class BasicMethodWithRequiredParams implements JsonRpcMethodInterface, MethodWithValidatedParamsInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(array $paramList = null)
    {
        return 'basic-method-with-params-result';
    }

    public function getParamsConstraint(): Constraint
    {
        return new Collection(
            [
                'fields' => [
                    'fieldA' => new NotNull(),
                    'fieldB' => new NotBlank(),
                ],
                'allowExtraFields' => false,
                'allowMissingFields' => false,
            ]
        );
    }
}

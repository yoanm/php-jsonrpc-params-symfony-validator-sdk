<?php
namespace Yoanm\JsonRpcParamsSymfonyValidator\Domain;

use Symfony\Component\Validator\Constraint;

/**
 * Interface MethodWithValidatedParamsInterface
 */
interface MethodWithValidatedParamsInterface
{
    /**
     * @return Constraint Usually a Collection constraint
     */
    public function getParamsConstraint() : Constraint;
}

<?php
namespace Yoanm\JsonRpcParamsSymfonyValidator\Domain\Model;

use Symfony\Component\Validator\Constraint;

/**
 * Interface MethodWithValidatedParams
 */
interface MethodWithValidatedParams
{
    /**
     * @return Constraint Usually a Collection constraint
     */
    public function getParamsConstraint() : Constraint;
}

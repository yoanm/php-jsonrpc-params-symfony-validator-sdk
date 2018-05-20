<?php
namespace Yoanm\JsonRpcParamsSymfonyValidator\Domain\Model;

use Symfony\Component\Validator\Constraint;

/**
 * Interface MethodWithValidatedParams
 */
interface MethodWithValidatedParams
{
    /**
     * Fields existence and type
     *
     * @return Constraint Usually a Collection constraint
     */
    public function getParamsConstraint();
}

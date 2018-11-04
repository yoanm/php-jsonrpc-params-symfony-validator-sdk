<?php
namespace Tests\Functional\BehatContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\IsIdentical;
use Symfony\Component\Validator\ValidatorBuilder;
use Yoanm\JsonRpcParamsSymfonyValidator\Infra\JsonRpcParamsValidator;
use Yoanm\JsonRpcServer\Domain\Model\JsonRpcRequest;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /** @var array */
    private $lastViolationList = [];

    /**
     * @When I validate method :methodClass with:
     */
    public function whenIValidateMethodWith($methodClass, PyStringNode $payload)
    {
        $jsonRpcRequest = new JsonRpcRequest('2.0', $methodClass);
        $jsonRpcRequest->setParamList(json_decode($payload->getRaw(), true));

        $this->lastViolationList = $this->getValidator()->validate($jsonRpcRequest, new $methodClass);
    }

    /**
     * @Then I should have no violation
     */
    public function thenIShouldHaveNoViolation()
    {
        Assert::assertEmpty($this->lastViolationList);
    }

    /**
     * @Then I should have 1 violation
     * @Then I should have :count violations
     */
    public function thenIShouldHaveXViolation($count = 1)
    {
        Assert::assertCount((int) $count, $this->lastViolationList);
    }

    /**
     * @Then I should have the following validation error:
     */
    public function thenIShouldHaveTheFollowingViolation(PyStringNode $node)
    {
        $found = false;
        $decoded = json_decode($node->getRaw(), true);
        $constraint = new IsIdentical($decoded);
        foreach ($this->lastViolationList as $violation) {
            if (true === $constraint->evaluate($violation, '', true)) {
                $found = true;
                break;
            }
        }

        if (true !== $found) {
            throw new \Exception(
                sprintf(
                    'Violation "%s" not found in violation list : %s',
                    json_encode($decoded),
                    json_encode($this->lastViolationList)
                )
            );
        }
    }
    /**
     * @return JsonRpcParamsValidator
     */
    private function getValidator() : JsonRpcParamsValidator
    {
        return new JsonRpcParamsValidator(
            (new ValidatorBuilder())->getValidator()
        );
    }
}

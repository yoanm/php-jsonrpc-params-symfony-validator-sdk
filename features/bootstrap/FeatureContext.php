<?php
namespace Tests\Functional\BehatContext;

use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;
use Tests\Functional\BehatContext\Helper\FakeEndpointCreator;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends AbstractContext
{
    const KEY_JSON_RPC = 'jsonrpc';
    const KEY_ID = 'id';
    const KEY_ERROR = 'error';

    const SUB_KEY_ERROR_CODE = 'code';
    const SUB_KEY_ERROR_MESSAGE = 'message';

    /** @var string|null */
    private $lastResponse = null;

    /**
     * @When I send following payload:
     */
    public function whenISendTheFollowingPayload(PyStringNode $payload)
    {
        $endpoint = (new FakeEndpointCreator())->create();

        $this->lastResponse = $endpoint->index($payload->getRaw());
    }

    /**
     * @Then I should have the following response:
     */
    public function thenIShouldHaveTheFollowingResponse(PyStringNode $expectedResult)
    {
        // Decode content to get rid of any indentation/spacing/... issues
        Assert::assertEquals(
            $this->jsonDecode($expectedResult->getRaw()),
            $this->getLastResponseDecoded()
        );
    }

    /**
     * @Then I should have an empty response
     */
    public function thenIShouldHaveAnEmptyResponse()
    {
        // Decode content to get rid of any indentation/spacing/... issues
        Assert::assertEmpty($this->getLastResponseDecoded());
    }

    private function getLastResponseDecoded()
    {
        return $this->jsonDecode($this->lastResponse);
    }
}

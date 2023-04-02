<?php
namespace Tests\Functional\Infra;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Yoanm\JsonRpcParamsSymfonyValidator\Domain\MethodWithValidatedParamsInterface;
use Yoanm\JsonRpcParamsSymfonyValidator\Infra\JsonRpcParamsValidator;
use Yoanm\JsonRpcServer\Domain\Event\Action\ValidateParamsEvent;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;
use Yoanm\JsonRpcServer\Domain\Model\JsonRpcRequest;

/**
 * @covers \Yoanm\JsonRpcParamsSymfonyValidator\Infra\JsonRpcParamsValidator
 *
 * @group Validator
 */
class JsonRpcParamsValidatorTest extends TestCase
{
    use ProphecyTrait;

    /** @var JsonRpcParamsValidator */
    private $validator;
    /** @var ValidatorInterface|ObjectProphecy */
    private $sfValidator;

    public function setUp(): void
    {
        $this->sfValidator = $this->prophesize(ValidatorInterface::class);

        $this->validator = new JsonRpcParamsValidator(
            $this->sfValidator->reveal()
        );
    }

    public function testShouldDoNothingIfMethodDoesNotImplementSpecificInterface()
    {
        /** @var JsonRpcMethodInterface|ObjectProphecy $method */
        $method = $this->prophesize(JsonRpcMethodInterface::class);
        /** @var JsonRpcRequest $jsonRpcRequest */
        $jsonRpcRequest = $this->prophesize(JsonRpcRequest::class);

        $this->sfValidator->validate(Argument::cetera())
            ->shouldNotBeCalled()
        ;

        $violationList = $this->validator->validate($jsonRpcRequest->reveal(), $method->reveal());

        $this->assertCount(0, $violationList);
    }

    public function testShouldCallSfValidator()
    {
        $paramList = ['paramList'];
        /** @var MethodWithValidatedParamsInterface|JsonRpcMethodInterface|ObjectProphecy $method */
        $method = $this->prophesize(MethodWithValidatedParamsInterface::class);
        $method->willImplement(JsonRpcMethodInterface::class);
        /** @var JsonRpcRequest $jsonRpcRequest */
        $jsonRpcRequest = $this->prophesize(JsonRpcRequest::class);
        /** @var Collection|ObjectProphecy $paramConstraint */
        $paramConstraint = $this->prophesize(Collection::class);

        $method->getParamsConstraint()
            ->willReturn($paramConstraint->reveal())
            ->shouldBeCalled()
        ;
        $jsonRpcRequest->getParamList()
            ->willReturn($paramList)
            ->shouldBeCalled()
        ;

        $this->sfValidator->validate($paramList, $paramConstraint->reveal())
            ->willReturn(new ConstraintViolationList())
            ->shouldBeCalled()
        ;

        $violationList = $this->validator->validate($jsonRpcRequest->reveal(), $method->reveal());

        $this->assertCount(0, $violationList);
    }

    public function testShouldNormalizeViolations()
    {
        $paramList = ['paramList'];
        /** @var MethodWithValidatedParamsInterface|JsonRpcMethodInterface|ObjectProphecy $method */
        $method = $this->prophesize(MethodWithValidatedParamsInterface::class);
        $method->willImplement(JsonRpcMethodInterface::class);
        /** @var JsonRpcRequest $jsonRpcRequest */
        $jsonRpcRequest = $this->prophesize(JsonRpcRequest::class);
        /** @var Collection|ObjectProphecy $paramConstraint */
        $paramConstraint = $this->prophesize(Collection::class);
        /** @var ConstraintViolationInterface|ObjectProphecy $violation1 */
        $violation1 = $this->prophesize(ConstraintViolationInterface::class);
        /** @var ConstraintViolationInterface|ObjectProphecy $violation2 */
        $violation2 = $this->prophesize(ConstraintViolationInterface::class);
        /** @var ConstraintViolationInterface|ObjectProphecy $violation3 */
        $violation3 = $this->prophesize(ConstraintViolationInterface::class);
        $sfViolationList = new ConstraintViolationList([
            $violation1->reveal(),
            $violation2->reveal(),
            $violation3->reveal()
        ]);
        $expectedNormalizedErrorList = [
            [
                'path' => 'path1',
                'message' => 'message1',
                'code' => 'code1',
            ],
            [
                'path' => 'path2',
                'message' => 'message2',
                'code' => 'code2',
            ],
            [
                'path' => 'path3',
                'message' => 'message3',
                'code' => 'code3',
            ]
        ];
        $violation1->getPropertyPath()->willReturn($expectedNormalizedErrorList[0]['path'])->shouldBeCalled();
        $violation1->getMessage()->willReturn($expectedNormalizedErrorList[0]['message'])->shouldBeCalled();
        $violation1->getCode()->willReturn($expectedNormalizedErrorList[0]['code'])->shouldBeCalled();
        $violation2->getPropertyPath()->willReturn($expectedNormalizedErrorList[1]['path'])->shouldBeCalled();
        $violation2->getMessage()->willReturn($expectedNormalizedErrorList[1]['message'])->shouldBeCalled();
        $violation2->getCode()->willReturn($expectedNormalizedErrorList[1]['code'])->shouldBeCalled();
        $violation3->getPropertyPath()->willReturn($expectedNormalizedErrorList[2]['path'])->shouldBeCalled();
        $violation3->getMessage()->willReturn($expectedNormalizedErrorList[2]['message'])->shouldBeCalled();
        $violation3->getCode()->willReturn($expectedNormalizedErrorList[2]['code'])->shouldBeCalled();

        $method->getParamsConstraint()
            ->willReturn($paramConstraint->reveal())
            ->shouldBeCalled()
        ;
        $jsonRpcRequest->getParamList()
            ->willReturn($paramList)
            ->shouldBeCalled()
        ;

        $this->sfValidator->validate($paramList, $paramConstraint->reveal())
            ->willReturn($sfViolationList)
            ->shouldBeCalled()
        ;

        $violationList = $this->validator->validate($jsonRpcRequest->reveal(), $method->reveal());

        $this->assertSame($expectedNormalizedErrorList, $violationList);
    }
}

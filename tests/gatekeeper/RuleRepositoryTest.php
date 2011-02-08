<?php
namespace gatekeeper;

/**
 * Description of RuleRepositoryTest
 *
 * @author cqql
 */
class RuleRepositoryTest
extends \PHPUnit_Framework_TestCase
{
	/**
	 *
	 * @var RuleRepository
	 */
	protected $repository = null;

	/**
	 *
	 * @var Role
	 */
	protected $role1 = null;

	/**
	 *
	 * @var Role
	 */
	protected $role2 = null;

	/**
	 *
	 * @var Resource
	 */
	protected $resource1 = null;

	/**
	 *
	 * @var Resource
	 */
	protected $resource2 = null;

	public function setUp ()
	{
		$this->repository = new RuleRepository();

		$this->role1 = $this->getMock('gatekeeper\Role');
		$this->role1
			->expects($this->any())
			->method('getRoleId')
			->will($this->returnValue('1'));
		$this->role1
			->expects($this->any())
			->method('getParentRole')
			->will($this->throwException(new HasNoParentRoleException()));

		$this->role2 = $this->getMock('gatekeeper\Role');
		$this->role2
			->expects($this->any())
			->method('getRoleId')
			->will($this->returnValue('2'));
		$this->role2
			->expects($this->any())
			->method('getParentRole')
			->will($this->returnValue($this->role1));

		$this->resource1 = $this->getMock('gatekeeper\Resource');
		$this->resource1
			->expects($this->any())
			->method('getResourceId')
			->will($this->returnValue('images'));
		$this->resource1
			->expects($this->any())
			->method('getParentResource')
			->will($this->throwException(new HasNoParentResourceException()));

		$this->resource2 = $this->getMock('gatekeeper\Resource');
		$this->resource2
			->expects($this->any())
			->method('getResourceId')
			->will($this->returnValue('upload'));
		$this->resource2
			->expects($this->any())
			->method('getParentResource')
			->will($this->returnValue($this->resource1));
	}

	public function testExactRuleAppliesFirst ()
	{
		$this->repository->addRule($this->role1, $this->resource2, false);
		$this->repository->addRule($this->role2, $this->resource2, true);

		$rule = $this->repository->getMostApplyingRule(
			$this->role2,
			$this->resource2
		);

		$this->assertSame($this->role2->getRoleId(), $rule->getRoleId());
		$this->assertSame(
			$this->resource2->getResourceId(),
			$rule->getResourceId()
		);
	}

	public function testMostSpecificRuleAppliesIfNoExactRuleIsFound ()
	{
		$this->repository->addRule($this->role1, $this->resource1, true);
		$this->repository->addRule($this->role1, $this->resource2, false);

		$rule = $this->repository->getMostApplyingRule(
			$this->role2,
			$this->resource2
		);

		$this->assertSame($this->role1->getRoleId(), $rule->getRoleId());
		$this->assertSame($this->resource2->getResourceId(), $rule->getResourceId());
	}
}

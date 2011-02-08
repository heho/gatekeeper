<?php
namespace gatekeeper;

class ACLTest
extends \PHPUnit_Framework_TestCase
{
	/**
	 *
	 * @var \gatekeeper\ACL
	 */
	protected $acl = null;

	/**
	 *
	 * @var \gatekeeper\Repository
	 */
	protected $repository = null;

	/**
	 *
	 * @var \gatekeeper\Rule
	 */
	protected $rule = null;

	/**
	 *
	 * @var <type>
	 */
	protected $ruleIsAllowedMethod = null;

	public function setUp ()
	{
		$this->rule = $this->getMock(
			'\gatekeeper\Rule',
			array(),
			array(),
			'',
			false
		);

		$this->repository = $this->getMock(
			'\gatekeeper\RuleRepository',
			array(),
			array(),
			'',
			false
		);

		$this->acl = new ACL($this->repository);
	}

	/**
	 * Sets the return value of the rule mocks isAllowed method to $value
	 *
	 * @param RuleMock $rule
	 * @param bool $value
	 * @return void
	 */
	protected function setIsAllowedReturnValue ($rule, $value)
	{
		$rule->expects($this->any())
			->method('isAllowed')
			->will($this->returnValue((bool)$value));
	}

	public function testAclIsWhiteListByDefault ()
	{
		$this->assertTrue($this->acl->isWhiteList());
	}

	public function testAclCanBeConvertedToABlackList ()
	{
		$this->acl->setWhiteList(false);

		$this->assertFalse($this->acl->isWhiteList());
	}

	public function testWhiteListDeniesAccessByDefault ()
	{
		$this->repository
			->expects($this->any())
			->method('getMostApplyingRule')
			->will($this->throwException(new ThereIsNoApplyingRuleException()));

		$role = $this->getMock('gatekeeper\Role');
		$resource = $this->getMock('gatekeeper\Resource');

		$this->assertFalse($this->acl->isAllowed($role, $resource));
	}

	/**
	 * @depends testAclCanBeConvertedToABlackList
	 */
	public function testBlackListAllowsAccessByDefault ()
	{
		$this->repository
			->expects($this->any())
			->method('getMostApplyingRule')
			->will($this->throwException(new ThereIsNoApplyingRuleException()));

		$role = $this->getMock('gatekeeper\Role');
		$resource = $this->getMock('gatekeeper\Resource');

		$this->acl->setWhiteList(false);

		$this->assertTrue($this->acl->isAllowed($role, $resource));
	}

	public function testRoleCanBeAllowedAccess ()
	{
		$this->repository
			->expects($this->any())
			->method('getMostApplyingRule')
			->will($this->returnValue($this->rule));

		$this->rule
			->expects($this->any())
			->method('isAllowed')
			->will($this->returnValue(true));

		$role = $this->getMock('gatekeeper\Role');
		$resource = $this->getMock('gatekeeper\Resource');

		$this->acl->allow($role, $resource);

		$this->assertTrue($this->acl->isAllowed($role, $resource));
	}

	/**
	 * @depends testRoleCanBeAllowedAccess
	 */
	public function testAccessCanBeRecalled ()
	{
		$this->repository
			->expects($this->any())
			->method('getMostApplyingRule')
			->will($this->returnValue($this->rule));

		$this->rule
			->expects($this->any())
			->method('isAllowed')
			->will($this->returnValue(false));

		$role = $this->getMock('gatekeeper\Role');
		$resource = $this->getMock('gatekeeper\Resource');

		$this->acl->allow($role, $resource);
		$this->acl->deny($role, $resource);

		$this->assertFalse($this->acl->isAllowed($role, $resource));
	}
}

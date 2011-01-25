<?php
namespace gatekeeper;

class ACLTest
extends \PHPUnit_Framework_TestCase
{
	protected $acl = null;

	public function setUp ()
	{
		$this->acl = new ACL();
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
		$role = $this->getMock('gatekeeper\Role');
		$resource = $this->getMock('gatekeeper\Resource');

		$this->assertFalse($this->acl->isAllowed($role, $resource));
	}

	/**
	 * @depends testAclCanBeConvertedToABlackList
	 */
	public function testBlackListAllowsAccessByDefault ()
	{
		$role = $this->getMock('gatekeeper\Role');
		$resource = $this->getMock('gatekeeper\Resource');

		$this->acl->setWhiteList(false);

		$this->assertTrue($this->acl->isAllowed($role, $resource));
	}

	public function testRoleCanBeAllowedAccess ()
	{
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
		$role = $this->getMock('gatekeeper\Role');
		$resource = $this->getMock('gatekeeper\Resource');

		$this->acl->allow($role, $resource);
		$this->acl->deny($role, $resource);

		$this->assertFalse($this->acl->isAllowed($role, $resource));
	}
}

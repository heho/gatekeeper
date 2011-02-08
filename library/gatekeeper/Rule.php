<?php
namespace gatekeeper;

/**
 * A Rule
 *
 * @author cqql
 */
class Rule
{
	/**
	 * Is this an allowing or a denying rule?
	 *
	 * @var bool
	 */
	protected $allowed = true;

	/**
	 * Role ID
	 *
	 * @var string
	 */
	protected $roleId = '';

	/**
	 * Resource ID
	 *
	 * @var string
	 */
	protected $resourceId = '';

	/**
	 * 
	 *
	 * @param string $roleId Role ID
	 * @param string $resourceId Resource ID
	 * @param bool $allowed Is the role allowed or denyed access?
	 */
	public function __construct ($roleId, $resourceId, $allowed)
	{
		$this->setResourceId($resourceId);
		$this->setRoleId($roleId);
		$this->setAllowed($allowed);
	}

	/**
	 * Checks, if this is an allowing rule
	 *
	 * @return bool
	 */
	public function isAllowed ()
	{
		return $this->allowed;
	}

	/**
	 * Sets the "allow-mode" of this rule
	 *
	 * @param bool $allowed
	 * @return void
	 */
	public function setAllowed ($allowed)
	{
		$this->allowed = (bool)$allowed;
	}

	/**
	 * Returns the role id
	 *
	 * @return string
	 */
	public function getRoleId ()
	{
		return $this->roleId;
	}

	/**
	 * Sets the role id
	 *
	 * @param string $roleId
	 * @return void
	 */
	public function setRoleId ($roleId)
	{
		$this->roleId = (string)$roleId;
	}

	/**
	 * Returns the resource id
	 *
	 * @return string
	 */
	public function getResourceId ()
	{
		return $this->resourceId;
	}

	/**
	 * Sets the resource id
	 *
	 * @param string $resourceId
	 * @return void
	 */
	public function setResourceId ($resourceId)
	{
		$this->resourceId = (string)$resourceId;
	}
}

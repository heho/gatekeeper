<?php
namespace gatekeeper;

/**
 * The ACL itself
 *
 * @author cqql
 */
class ACL
{
	/**
	 * Is this ACL a black or a white list?
	 *
	 * @var bool
	 */
	protected $isWhiteList = true;

	/**
	 * All the rules managed by this ACL
	 *
	 * @var array of Rule
	 */
	protected $rules = array();

	/**
	 * Sets the mode of the ACL
	 *
	 * White list means that every access is denied by default. Black list on
	 * the other hand grants access by default.
	 *
	 * @param bool $isWhiteList
	 * @return void
	 */
	public function setWhiteList ($isWhiteList)
	{
		$this->isWhiteList = (bool)$isWhiteList;
	}

	/**
	 * Checks, if this list is a white list
	 *
	 * @return bool
	 */
	public function isWhiteList ()
	{
		return $this->isWhiteList;
	}

	/**
	 * Checks, if $role is allowed access on $resource
	 *
	 * @param Role $role
	 * @param Resource $resource
	 * @return bool
	 */
	public function isAllowed (Role $role, Resource $resource)
	{
		$roleId = $role->getRoleId();
		$resourceId = $resource->getResourceId();

		if (isset($this->rules[$roleId][$resourceId]))
		{
			return $this->rules[$roleId][$resourceId];
		}
		else
		{
			if ($this->isWhiteList())
			{
				return false;
			}
			else
			{
				return true;
			}
		}
	}

	/**
	 * Grants $role access on $resource
	 *
	 * @param Role $role
	 * @param Resource $resource
	 * @return void
	 */
	public function allow (Role $role, Resource $resource)
	{
		$roleId = $role->getRoleId();
		$resourceId = $resource->getResourceId();

		if (!isset($this->rules[$roleId]))
		{
			$this->rules[$roleId] = array();
		}

		$this->rules[$roleId][$resourceId] = true;
	}

	/**
	 * Denies $role access on $resource
	 *
	 * @param Role $role
	 * @param Resource $resource
	 * @return void
	 */
	public function deny (Role $role, Resource $resource)
	{
		$roleId = $role->getRoleId();
		$resourceId = $resource->getResourceId();

		if (!isset($this->rules[$roleId]))
		{
			$this->rules[$roleId] = array();
		}

		$this->rules[$roleId][$resourceId] = false;
	}
}

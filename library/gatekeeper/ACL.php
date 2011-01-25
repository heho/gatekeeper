<?php
namespace gatekeeper;

/**
 * The ACL itself
 *
 * @author cqql
 */
class ACL
{
	protected $isWhiteList = true;

	protected $rules = array();

	public function setWhiteList ($isWhiteList)
	{
		$this->isWhiteList = (bool)$isWhiteList;
	}

	public function isWhiteList ()
	{
		return $this->isWhiteList;
	}

	public function isAllowed (Role $role, Resource $resource)
	{
		$roleId = $role->getId();
		$resourceId = $resource->getId();

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

	public function allow (Role $role, Resource $resource)
	{
		$roleId = $role->getId();
		$resourceId = $resource->getId();

		if (!isset($this->rules[$roleId]))
		{
			$this->rules[$roleId] = array();
		}

		$this->rules[$roleId][$resourceId] = true;
	}

	public function deny (Role $role, Resource $resource)
	{
		$roleId = $role->getId();
		$resourceId = $resource->getId();

		if (!isset($this->rules[$roleId]))
		{
			$this->rules[$roleId] = array();
		}

		$this->rules[$roleId][$resourceId] = false;
	}

	protected function getFirstApplyingRule (Role $role, Resource $resource)
	{
		throw new ThereIsNoApplyingRuleException();
	}
}

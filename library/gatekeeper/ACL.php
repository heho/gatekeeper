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
	 * Repository that manages the rules
	 *
	 * @var \gatekeeper\RuleRepository
	 */
	protected $repository = null;

	/**
	 *
	 * @param \gatekeeper\RuleRepository $repository Repository to manage the rules
	 */
	public function __construct (RuleRepository $repository)
	{
		$this->repository = $repository;
	}

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
		try
		{
			$rule = $this->repository->getMostApplyingRule($role, $resource);

			return $rule->isAllowed();
		}
		catch (ThereIsNoApplyingRuleException $e)
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
		$this->repository->addRule($role, $resource, true);
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
		$this->repository->addRule($role, $resource, false);
	}
}

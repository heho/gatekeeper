<?php
namespace gatekeeper;

/**
 * Manages rules
 *
 * @author cqql
 */
class RuleRepository
{
	/**
	 * Rules managed by the repository
	 *
	 * @var array of Rule
	 */
	protected $rules = array();

	/**
	 * Adds a new rule, that allows/denies access on $resource to $role
	 *
	 * @param Role $role Role for the new rule
	 * @param Resource $resource Resource for the new rule
	 * @param bool $allowed Status of the new rule
	 * @return void
	 */
	public function addRule (Role $role, Resource $resource, $allowed)
	{
		$this->rules[] = new Rule(
			$role->getRoleId(),
			$resource->getResourceId(),
			$allowed
		);
	}

	/**
	 * Returns the rules managed by the repository
	 *
	 * @return array of Rule
	 */
	public function getRules ()
	{
		return $this->rules;
	}

	/**
	 * Returns the rule, that applies most to $role and $resource
	 *
	 * @param \gatekeeper\Role $role Role to search for
	 * @param \gatekeeper\Resource $resource Resource to search for
	 * @return \gatekeeper\Rule
	 * @throws \gatekeeper\ThereIsNoApplyingRuleException if the is no applying
	 *		rule
	 */
	public function getMostApplyingRule (Role $role, Resource $resource)
	{
		do
		{
			$roleId = $role->getRoleId();
			
			foreach ($this->getRules() as $rule)
			{
				if ($rule->getRoleId() !== $roleId)
				{
					continue;
				}

				$tmpResource = $resource;

				do
				{/// Perhaps breadth first search?!
					$resourceId = $tmpResource->getResourceId();

					if ($rule->getResourceId() === $resourceId)
					{
						return $rule;
					}
					
					try
					{
						$tmpResource = $tmpResource->getParentResource();
					}
					catch (HasNoParentResourceException $e)
					{
						$tmpResource = null;
					}
				}
				while ($tmpResource !== null);
			}

			try
			{
				$role = $role->getParentRole();
			}
			catch (HasNoParentRoleException $e)
			{
				$role = null;
			}
		}
		while ($role !== null);

		throw new ThereIsNoApplyingRuleException();
	}
}

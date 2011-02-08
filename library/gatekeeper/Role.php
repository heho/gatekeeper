<?php
namespace gatekeeper;

/**
 * A Role
 *
 * @author cqql
 */
interface Role
{
	/**
	 * Returns the role's id
	 *
	 * @return string
	 */
	public function getRoleId ();

	/**
	 * Returns the parent role if there is any
	 *
	 * @return Role
	 * @throws \gatekeeper\HasNoParentRoleException if there is no parent
	 */
	public function getParentRole ();
}

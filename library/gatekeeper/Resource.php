<?php
namespace gatekeeper;

/**
 * A Resource
 *
 * @author cqql
 */
interface Resource
{
	/**
	 * Returns the resource's id
	 *
	 * @return string
	 */
	public function getResourceId ();

	/**
	 * Returns the parent resource if there is any
	 *
	 * @return Resource
	 * @throws \gatekeeper\HasNoParentResourceException if there is no parent
	 */
	public function getParentResource ();
}

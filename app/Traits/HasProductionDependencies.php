<?php

namespace App\Traits;

trait HasProductionDependencies
{
	/**
	 * Determine if production dependencies have been loaded.
	 *
	 * This refers to dependencies that have been disabled for
	 * production builds. This is done to prevent the user from using
	 * some commands directly, although some commands may still be
	 * required by the application itself.
	 */
	protected bool $dependenciesLoaded = false;

	/**
	 * Load any commands disabled in production that another command
	 * still needs.
	 *
	 * @todo: throw an error in dev if loading dependencies is attempted
	 */
	protected function loadProductionDependencies(array $dependencies): void
	{
		if($this->dependenciesLoaded) return;

		$this->getApplication()
			->addCommands(collect($dependencies)
			->map(fn ($command) => app($command))
			->toArray());
	}
}

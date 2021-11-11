<?php

namespace App\Commands;

use App\Traits\HasProductionDependencies;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class InitCommand extends Command
{
	use HasProductionDependencies;

	/**
	 * The signature of the command.
	 *
	 * @var string
	 */
	protected $signature = 'init';

	/**
	 * The description of the command.
	 *
	 * @var string
	 */
	protected $description = 'Run initialize procedure.';

	/**
	 * Execute the console command.
	 */
	public function handle(): int
	{
		// Load required init dependencies in Production
		if (app()->environment('production')) {
			$this->loadProductionDependencies([
				\Illuminate\Database\Console\Migrations\MigrateCommand::class,
			]);
		}

		// Init application
		if (!Storage::exists('database.sqlite')) {
			Storage::put('database.sqlite', '');
		}

		$this->call('migrate', [
			'--force' => true,
		]);

		return self::SUCCESS;
	}
}

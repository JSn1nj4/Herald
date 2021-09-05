<?php

namespace App\Commands;

use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class InstallCommand extends Command
{
	/**
	 * The signature of the command.
	 *
	 * @var string
	 */
	protected $signature = 'install';

	/**
	 * The description of the command.
	 *
	 * @var string
	 */
	protected $description = 'Run application install procedure.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		if (!Storage::exists('database.sqlite')) {
			Storage::put('database.sqlite', '');
		}

		$this->call('migrate');

		return self::SUCCESS;
	}
}

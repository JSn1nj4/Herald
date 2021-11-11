<?php

namespace App\Commands;

use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class InitCommand extends Command
{
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
		if (!Storage::exists('database.sqlite')) {
			Storage::put('database.sqlite', '');
		}

		$this->call('migrate');

		return self::SUCCESS;
	}
}

<?php

namespace App\Commands;

use App\SlackWebhook;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class SlackWebhookSetDefaultCommand extends Command
{
	/**
	 * The signature of the command.
	 *
	 * @var string
	 */
	protected $signature = 'slack:webhook:set_default
							{name : The name of the webhook to set as the default.}';

	/**
	 * The description of the command.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		if(!$webhook = SlackWebhook::firstWhere('name', $this->argument('name'))) {
			$this->error("A webhook named '{$this->argument('name')}' could not be found.");

			return self::FAILURE;
		};

		if($webhook->default) {
			$this->info("'{$webhook->name}' is already the default webhook.");

			return self::SUCCESS;
		}

		$webhook->default = true;
		$webhook->save();

		SlackWebhook::where('name', '!=', $this->argument('name'))
			->update(['default' => false]);

		$this->info("'{$webhook->name}' is now the default Slack webhook.");

		return self::SUCCESS;
	}

	/**
	 * Define the command's schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 * @return void
	 */
	public function schedule(Schedule $schedule): void
	{
		// $schedule->command(static::class)->everyMinute();
	}
}

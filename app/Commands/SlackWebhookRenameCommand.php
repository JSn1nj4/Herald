<?php

namespace App\Commands;

use App\SlackWebhook;
use LaravelZero\Framework\Commands\Command;

class SlackWebhookRenameCommand extends Command
{
	/**
	 * The signature of the command.
	 *
	 * @var string
	 */
	protected $signature = 'slack:webhook:rename {current} {new}';

	/**
	 * The description of the command.
	 *
	 * @var string
	 */
	protected $description = 'Rename a Slack webhook.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		if(!$webhook = SlackWebhook::firstWhere('name', $this->argument('current'))) {
			$this->error("A webhook named '{$this->argument('current')}' does not exist.");

			return self::FAILURE;
		}

		$webhook->name = $this->argument('new');
		$webhook->save();

		$this->info("Webhook '{$this->argument('current')}' has been renamed to '{$webhook->name}'.");

		return self::SUCCESS;
	}
}

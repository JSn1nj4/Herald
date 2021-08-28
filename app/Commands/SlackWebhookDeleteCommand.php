<?php

namespace App\Commands;

use App\SlackWebhook;
use LaravelZero\Framework\Commands\Command;

class SlackWebhookDeleteCommand extends Command
{
	/**
	 * The signature of the command.
	 *
	 * @var string
	 */
	protected $signature = 'slack:webhook:delete
							{--a|all : Clear all Slack webhooks from storage.}
							{--name= : Delete a Slack webhook by name.}';

	/**
	 * The description of the command.
	 *
	 * @var string
	 */
	protected $description = 'Delete Slack webhooks 1-by-1 or in bulk.';

	protected function deleteAll(): int
	{
		if (!$this->confirm('Warning! This is a destructive operation. Are you sure you wish to continue?')) {
			$this->info('Delete operation aborted.');

			return self::FAILURE;
		}

		SlackWebhook::truncate();

		$this->info('All Slack webhooks have been deleted.');

		return self::SUCCESS;
	}

	protected function deleteByName(): int
	{
		if (!$webhook = SlackWebhook::firstWhere('name', $this->option('name'))) {
			$this->error("A webhook named '{$this->option('name')}' could not be found.");

			return self::FAILURE;
		}

		if (!$this->confirm('Warning! This is a destructive operation. Are you sure you wish to continue?')) {
			$this->info('Delete operation aborted.');

			return self::FAILURE;
		}

		$webhook->delete();

		$this->info("Webhook named '{$this->option('name')}' has been deleted.");

		return self::SUCCESS;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		if (!$this->requiredOptionsUsed()) {
			$this->error("You must pass one of the available options to use this commmand.");
			$this->line("Run 'php herald help slack:webhook:delete' for available options.");

			return self::FAILURE;
		}

		if ($this->option('all')) {
			return $this->deleteAll();
		}

		return $this->deleteByName();
	}

	protected function requiredOptionsUsed(): bool
	{
		return $this->option('all')
		|| $this->option('name') !== null;
	}
}

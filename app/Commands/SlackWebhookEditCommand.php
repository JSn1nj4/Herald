<?php

namespace App\Commands;

use App\SlackWebhook;
use LaravelZero\Framework\Commands\Command;

class SlackWebhookEditCommand extends Command
{
	protected array $editableFields = [
		'url',
	];

	/**
	 * The signature of the command.
	 *
	 * @var string
	 */
	protected $signature = 'slack:webhook:edit
							{name : Name of the webhook to edit.}
							{field : Name of the field to edit.}
							{value : New value to set the field to.}';

	/**
	 * The description of the command.
	 *
	 * @var string
	 */
	protected $description = 'Edit a webhook\'s fields.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		if (!in_array($this->argument('field'), $this->editableFields)) {
			$this->error("Fields that can be edited are: " . implode(", ", $this->editableFields) . ".");

			return self::FAILURE;
		}

		if (!$webhook = SlackWebhook::firstWhere('name', $this->argument('name'))) {
			$this->error("A webhook named '{$this->argument('name')}' does not exist.");

			return self::FAILURE;
		}

		$webhook->{$this->argument('field')} = $this->argument('value');
		$webhook->save();

		$this->info("Webhook '{$webhook->name}' has been updated.");

		return self::SUCCESS;
	}
}

<?php

namespace App\Commands;

use App\SlackWebhook;
use LaravelZero\Framework\Commands\Command;

class SlackWebhookAddCommand extends Command
{
	/**
	 * The signature of the command.
	 *
	 * @var string
	 */
	protected $signature = 'slack:webhook:add
							{--name= : User-friendly webhook label.}
							{--url= : The HTTP endpoint to send payloads to.}
							{--default : Tell the command to set this webhook as the new default Slack webhook.}';

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
		if(SlackWebhook::where('name', $this->option('name'))->get()->count() > 0) {
			$this->error("A webhook named '{$this->option('name')}' already exists. To update this webhook, use the 'slack:webhook:set_url' or 'slack:webhook:set_default' commands instead.");

			return self::FAILURE;
		}

		$webhook = SlackWebhook::create([
			'name' => $this->option('name'),
			'url' => $this->option('url'),
		]);

		$this->info("Webhook named '{$webhook->name}' created successfully.");

		if($this->option('default')) {
			$this->call('slack:webhook:set_default', [
				'name' => $webhook->name,
			]);
		}


		return self::SUCCESS;
	}
}

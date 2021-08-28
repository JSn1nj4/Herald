<?php

namespace App\Commands;

use App\SlackWebhook;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class SendSlackWebhookCommand extends Command
{
	/**
	 * The signature of the command.
	 *
	 * @var string
	 */
	protected $signature = 'send:slack:webhook
							{--name=default : The name of the webhook to use}
							{message : The message to send}';

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
		if($this->option('name') === 'default') {
			$webhook = SlackWebhook::firstWhere('default', true);
		} else {
			$webhook = SlackWebhook::firstwhere('name', $this->option('name'));
		}

		if(!$webhook) {
			$this->error("A matching webhook could not be found.");

			return self::FAILURE;
		}

		$response = Http::post($webhook->url, [
			'text' => $this->argument('message'),
		]);

		if($response->failed()) {
			$response->throw();

			return self::FAILURE;
		}

		return self::SUCCESS;
	}
}

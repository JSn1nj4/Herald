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
	protected $description = 'Send notification to a Slack webhook.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		if($this->option('name') !== 'default') {
			return $this->useNamed();
		}

		return $this->useDefault();
	}

	private function useDefault(): int
	{
		if(!$webhook = SlackWebhook::firstWhere('default', true)) {
			$this->error("No default webhook set.");

			return self::FAILURE;
		}

		return $this->send($webhook);
	}

	private function useNamed(): int
	{
		if(!$webhook = SlackWebhook::firstwhere('name', $this->option('name'))) {
			$this->error("A webhook named '{$this->option('name')}' could not be found.");

			return self::FAILURE;
		}

		return $this->send($webhook);
	}

	private function send(SlackWebhook $webhook): int
	{
		$response = Http::post($webhook->url, [
			'text' => $this->argument('message'),
		]);

		if ($response->failed()) {
			$response->throw();

			return self::FAILURE;
		}

		return self::SUCCESS;
	}
}

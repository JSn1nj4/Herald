<?php

namespace App\Commands;

use App\SlackWebhook;
use Illuminate\Database\Eloquent\Collection;
use LaravelZero\Framework\Commands\Command;

class SlackWebhookListCommand extends Command
{
	protected array $allowedFormats = [
		'json',
		'list',
	];

	/**
	 * The signature of the command.
	 *
	 * @var string
	 */
	protected $signature = "slack:webhook:list
							{--format=list : (WIP) Format to use for webhooks list output.}";
							// {--format=list : (WIP) Format to use for webhooks list output. Available formats are list, json, table, and xml.}";

	/**
	 * The description of the command.
	 *
	 * @var string
	 */
	protected $description = 'List all stored webhooks.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		if(!in_array($this->option('format'), $this->allowedFormats)) {
			$this->error("'format' must be one of: " . implode(", ", $this->allowedFormats) . ".");

			return self::FAILURE;
		}

		$webhooks = SlackWebhook::all();

		if($webhooks->count() < 1) {
			$this->error("No webhooks have been found.");

			return self::FAILURE;
		}

		$this->line(match($this->option('format')) {
			'json' => $this->formatJson($webhooks),
			'list' => $this->formatList($webhooks),
		});

		return self::SUCCESS;
	}

	protected function formatJson(Collection $webhooks): string
	{
		return $webhooks->map(fn ($item) => [
			'name' => $item->name,
			'url' => $item->url,
		])->toJson();
	}

	/**
	 * Format webhooks as list
	 */
	protected function formatList(Collection $webhooks): string
	{
		return $webhooks->reduce(fn ($carry, $item) => "{$carry}{$item->name}: {$item->url}\n", '');
	}
}

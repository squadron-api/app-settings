<?php

namespace Squadron\AppSettings\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

/**
 * Class AppSettings
 * @package App\Models
 */
class AppSettings
{
	private const CACHE_KEY = 'app-settings';

	private $default;

	/**
	 * AppSettings constructor.
	 */
	public function __construct()
	{
		$settings = config('squadron.appSettings');
		$defaults = array_map(function ($value) {
			return $value['default'];
		}, $settings);

		$this->default = collect($defaults);
	}

    public function getAll(): Collection
	{
		return $this->default->merge(
			Cache::get(self::CACHE_KEY, [])
		);
	}

	public function get(string $key)
	{
		return $this->getAll()->get($key);
	}

	public function set(array $settings): void
	{
		$newSettings = $this->getAll()->merge($settings)->only($this->default->keys());
		Cache::forever(self::CACHE_KEY, $newSettings);
	}
}

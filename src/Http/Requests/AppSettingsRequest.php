<?php

namespace Squadron\AppSettings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppSettingsRequest extends FormRequest
{
	/**
	 * @inheritdoc
	 */
	public function rules(): array
	{
        $settings = config('squadron.appSettings');

		return array_map(function ($value) {
			return $value['rules'];
		}, $settings);
	}
}

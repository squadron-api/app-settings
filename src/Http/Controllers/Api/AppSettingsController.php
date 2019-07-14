<?php

namespace Squadron\AppSettings\Http\Controllers\Api;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Squadron\Base\Exceptions\SquadronException;
use Squadron\Base\Helpers\ApiResponse;
use Squadron\Base\Http\Controllers\BaseController;
use Squadron\AppSettings\Http\Requests\AppSettingsRequest;
use Squadron\AppSettings\Models\AppSettings;

class AppSettingsController extends BaseController
{
    /**
     * @param AppSettings $appSettings
     *
     * @return JsonResponse
     */
    public function getList(AppSettings $appSettings): JsonResponse
    {
		return ApiResponse::success(null, [
			'data' => $appSettings->getAll()
		]);
	}

    /**
     * @param AppSettings $appSettings
     * @param string $keys
     *
     * @return JsonResponse
     */
    public function getFilteredList(AppSettings $appSettings, string $keys): JsonResponse
    {
		$keysList = explode(',', $keys);

		return ApiResponse::success(null, [
			'data' => $appSettings->getAll()->only($keysList)
		]);
	}

    /**
     * @param AppSettingsRequest $request
     * @param AppSettings $appSettings
     *
     * @return JsonResponse
     *
     * @throws AuthorizationException
     */
    public function set(AppSettingsRequest $request, AppSettings $appSettings): JsonResponse
    {
        $currentUser = $request->user();

        if (method_exists($currentUser, 'isRoot'))
        {
            if ($currentUser->isRoot())
            {
                $appSettings->set($request->all());

                return ApiResponse::success(__('squadron.appSettings::messages.saveSuccess'));
            }

            throw new AuthorizationException('This action allowed for administrators only');
        }

        throw SquadronException::packageIsNotInstalled('squadron-api/user');
	}
}

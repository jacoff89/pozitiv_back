<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper;
use App\Http\Filters\AdditionalServiceFilter;
use App\Http\Requests\AdditionalService\StoreAdditionalServiceRequest;
use App\Http\Requests\AdditionalService\UpdateAdditionalServiceRequest;
use App\Http\Resources\AdditionalServiceResource;
use App\Interfaces\AdditionalServiceRepositoryInterface;
use App\Models\AdditionalService;
use Illuminate\Foundation\Http\FormRequest;

class AdditionalServiceController extends Controller
{
    private AdditionalService $additionalService;

    private AdditionalServiceRepositoryInterface $additionalServiceRepositoryInterface;

    public function __construct(AdditionalServiceRepositoryInterface $additionalServiceRepositoryInterface, AdditionalService $additionalService)
    {
        $this->additionalServiceRepositoryInterface = $additionalServiceRepositoryInterface;
        $this->additionalService = $additionalService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FormRequest $request, AdditionalServiceFilter $filter)
    {
        $this->authorize('viewAny', $this->additionalService);
        $params = $request->only('name', 'description');
        $data = $this->additionalServiceRepositoryInterface->index($params, $filter);

        return JsonResponseHelper::success(AdditionalServiceResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdditionalServiceRequest $request)
    {
        $this->authorize('create', $this->additionalService);
        $params = $request->only('name', 'description');

        try {
            $additionalService = $this->additionalServiceRepositoryInterface->store($params);

            return JsonResponseHelper::success(new AdditionalServiceResource($additionalService), __('messages.additional_service.added'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.additional_service.add_err'), 400, $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->authorize('view', $this->additionalService);
        $additionalService = $this->additionalServiceRepositoryInterface->getById($id);

        return JsonResponseHelper::success(new AdditionalServiceResource($additionalService));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdditionalServiceRequest $request, $id)
    {
        $this->authorize('update', $this->additionalService);
        $params = $request->only('name', 'description');

        if(empty($params)) {
            return JsonResponseHelper::error(__('messages.update_err.all_fields_is_empty'), 422);
        }
        try {
            $additionalService = $this->additionalServiceRepositoryInterface->update($id, $params);
            return JsonResponseHelper::success(new AdditionalServiceResource($additionalService), __('messages.additional_service.updated'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.additional_service.update_err'), 400, $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Доработать логику удаления сущности (если нет записи все равно показывает что удалена успешно)
        //Сделать это во всех сущностях
        $this->authorize('delete', $this->additionalService);
        try {
            $this->additionalServiceRepositoryInterface->delete((int)$id);
            return JsonResponseHelper::success('', __('messages.additional_service.deleted'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.additional_service.missing'), 404, $ex->getMessage());
        }
    }
}

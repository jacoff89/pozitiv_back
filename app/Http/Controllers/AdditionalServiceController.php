<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponseHelper as ResponseClass;
use App\Http\Requests\StoreAdditionalServiceRequest;
use App\Http\Requests\UpdateAdditionalServiceRequest;
use App\Http\Resources\AdditionalServiceResource;
use App\Interfaces\AdditionalServiceRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AdditionalServiceController extends Controller
{
    private AdditionalServiceRepositoryInterface $additionalServiceRepositoryInterface;

    public function __construct(AdditionalServiceRepositoryInterface $additionalServiceRepositoryInterface)
    {
        $this->additionalServiceRepositoryInterface = $additionalServiceRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->additionalServiceRepositoryInterface->index();

        return ResponseClass::sendResponse(AdditionalServiceResource::collection($data), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdditionalServiceRequest $request)
    {
        $details = [
            'name' => $request->name,
            'description' => $request->description
        ];
        DB::beginTransaction();
        try {
            $additionalService = $this->additionalServiceRepositoryInterface->store($details);

            DB::commit();
            return ResponseClass::sendResponse(new AdditionalServiceResource($additionalService), 'AdditionalService Create Successful', 201);

        } catch (\Exception $ex) {
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $additionalService = $this->additionalServiceRepositoryInterface->getById($id);

        return ResponseClass::sendResponse(new AdditionalServiceResource($additionalService), '', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdditionalServiceRequest $request, $id)
    {
        $updateDetails = [];
        if ($request->name) $updateDetails['name'] = $request->name;
        if ($request->description) $updateDetails['description'] = $request->description;
        if(empty($updateDetails)) {
            return ResponseClass::sendResponse('', 'Update Failed (all fields is empty)', 400);
        }
        DB::beginTransaction();
        try {
            $trip = $this->additionalServiceRepositoryInterface->update($updateDetails, $id);

            DB::commit();
            return ResponseClass::sendResponse(new AdditionalServiceResource($trip), 'AdditionalService Update Successful', 201);

        } catch (\Exception $ex) {
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($this->additionalServiceRepositoryInterface->delete($id)) {
            return ResponseClass::sendResponse('AdditionalService Delete Successful', '', 201);
        }
        return ResponseClass::sendResponse('AdditionalService is missing', '', 404);
    }
}

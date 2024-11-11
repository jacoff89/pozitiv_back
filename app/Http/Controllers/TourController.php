<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass as ResponseClass;
use App\Http\Requests\StoreTourRequest;
use App\Http\Requests\UpdateTourRequest;
use App\Http\Resources\TourResource;
use App\Interfaces\TourRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TourController extends Controller
{

    private TourRepositoryInterface $tourRepositoryInterface;

    public function __construct(TourRepositoryInterface $tourRepositoryInterface)
    {
        $this->tourRepositoryInterface = $tourRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->tourRepositoryInterface->index();

        return ResponseClass::sendResponse(TourResource::collection($data), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTourRequest $request)
    {
        foreach ($request->images as $value) {
            $images[] = $value->store('img/tour', 'public');
        }

        $planPictureImg = $request->planPicture->store('img/tour_planPicture', 'public');

        $details = [
            'name' => $request->name,
            'description' => $request->description,
            'duration' => $request->duration,
            'place' => $request->place,
            'plan' => $request->plan,
            'planPicture' => $planPictureImg,
            'season' => $request->season,
            'images' => $images,
        ];
        DB::beginTransaction();
        try {
            $trip = $this->tourRepositoryInterface->store($details);

            DB::commit();
            return ResponseClass::sendResponse(new TourResource($trip), 'Tour Create Successful', 201);

        } catch (\Exception $ex) {
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $trip = $this->tourRepositoryInterface->getById($id);

        return ResponseClass::sendResponse(new TourResource($trip), '', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTourRequest $request, $id)
    {
        foreach ($request->images as $value) {
            $images[] = $value->store('img/tour', 'public');
        }

        $planPictureImg = $request->planPicture->store('img/tour_planPicture', 'public');

        $updateDetails = [];
        if ($request->name) $updateDetails['name'] = $request->name;
        if ($request->description) $updateDetails['description'] = $request->description;
        if ($request->duration) $updateDetails['duration'] = $request->duration;
        if ($request->place) $updateDetails['place'] = $request->place;
        if ($request->plan) $updateDetails['plan'] = $request->plan;
        if ($request->season) $updateDetails['season'] = $request->season;
        if ($request->images) $updateDetails['images'] = $images;
        if ($request->planPicture) $updateDetails['planPicture'] = $planPictureImg;
        if(empty($updateDetails)) {
            return ResponseClass::sendResponse('', 'Update Failed (all fields is empty)', 400);
        }
        DB::beginTransaction();
        try {
            $trip = $this->tourRepositoryInterface->update($updateDetails, $id);

            DB::commit();
            return ResponseClass::sendResponse(new TourResource($trip), 'Tour Update Successful', 201);

        } catch (\Exception $ex) {
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($this->tourRepositoryInterface->delete($id)) {
            return ResponseClass::sendResponse('Tour Delete Successful', '', 201);
        }
        return ResponseClass::sendResponse('Tour is missing', '', 404);
    }
}

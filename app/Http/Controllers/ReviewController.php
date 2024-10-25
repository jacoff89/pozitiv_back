<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Interfaces\ReviewRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Classes\ApiResponseClass as ResponseClass;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Image;

class ReviewController extends Controller
{

    private ReviewRepositoryInterface $reviewRepositoryInterface;

    public function __construct(ReviewRepositoryInterface $reviewRepositoryInterface)
    {
        $this->reviewRepositoryInterface = $reviewRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->reviewRepositoryInterface->index();

        return ResponseClass::sendResponse(ReviewResource::collection($data), '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        $img = $request->img->store('img/review', 'public');
        $details = [
            'name' => $request->name,
            'text' => $request->text,
            'link' => $request->link,
            'img' => $img,
            'imgWebp' => $request->imgWebp,
        ];
        DB::beginTransaction();
        try {
            $trip = $this->reviewRepositoryInterface->store($details);

            DB::commit();
            return ResponseClass::sendResponse(new ReviewResource($trip), 'Review Create Successful', 201);

        } catch (\Exception $ex) {
            if (Storage::disk('public')->exists($img)) Storage::disk('public')->delete($img);
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $trip = $this->reviewRepositoryInterface->getById($id);

        return ResponseClass::sendResponse(new ReviewResource($trip), '', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, $id)
    {
        $updateDetails = [];
        if ($request->name) $updateDetails['name'] = $request->name;
        if ($request->text) $updateDetails['text'] = $request->text;
        if ($request->link) $updateDetails['link'] = $request->link;
        if ($request->img) $updateDetails['img'] = $request->img;
        if ($request->imgWebp) $updateDetails['imgWebp'] = $request->imgWebp;
        if(empty($updateDetails)) {
            return ResponseClass::sendResponse('', 'Update Failed (all fields is empty)', 400);
        }
        DB::beginTransaction();
        try {
            $trip = $this->reviewRepositoryInterface->update($updateDetails, $id);

            DB::commit();
            return ResponseClass::sendResponse(new ReviewResource($trip), 'Review Update Successful', 201);

        } catch (\Exception $ex) {
            return ResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($this->reviewRepositoryInterface->delete($id)) {
            return ResponseClass::sendResponse('Review Delete Successful', '', 201);
        }
        return ResponseClass::sendResponse('Review is missing', '', 404);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Filters\ReviewFilter;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Interfaces\ReviewRepositoryInterface;
use App\Models\Review;
use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\JsonResponseHelper;

class ReviewController extends Controller
{
    private Review $review;

    private ReviewRepositoryInterface $reviewRepositoryInterface;

    public function __construct(ReviewRepositoryInterface $reviewRepositoryInterface, Review $review)
    {
        $this->reviewRepositoryInterface = $reviewRepositoryInterface;
        $this->review = $review;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FormRequest $request, ReviewFilter $filter)
    {
        $params = $request->only('name');
        $data = $this->reviewRepositoryInterface->index($params, $filter);

        return JsonResponseHelper::success(ReviewResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        $this->authorize('create', Review::class);
        $params = $request->only('name', 'text', 'link');

        try {
            $review = $this->reviewRepositoryInterface->store($params, $request->img);
            return JsonResponseHelper::success(new ReviewResource($review), __('messages.review.added'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.review.add_err'), 400, $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $review = $this->reviewRepositoryInterface->getById($id);

        return JsonResponseHelper::success(new ReviewResource($review));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, $id)
    {
        $this->authorize('update', $this->review);
        $params = $request->only('name', 'text', 'link');

        if (empty($params) && !$request->img) {
            return JsonResponseHelper::error(__('messages.update_err.all_fields_is_empty'), 422);
        }

        try {
            $review = $this->reviewRepositoryInterface->update($id, $params, $request->img);
            return JsonResponseHelper::success(new ReviewResource($review), __('messages.review.updated'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.review.update_err'), 400, $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', $this->review);
        try {
            $this->reviewRepositoryInterface->delete($id);
            return JsonResponseHelper::success('', __('messages.review.deleted'), 201);

        } catch (\Exception $ex) {
            return JsonResponseHelper::error(__('messages.review.missing'), 404, $ex->getMessage());
        }
    }
}

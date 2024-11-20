<?php

namespace App\Repositories;

use App\Models\Tour;
use App\Interfaces\TourRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class TourRepository implements TourRepositoryInterface
{
    public function index(array $queryParams, $filter, array|null $relations = null)
    {
        $tour = Tour::query();
        if ($relations) {
            foreach ($relations as $relation) {
                $tour->with($relation);
            }
        }
        return $filter->apply($tour, $queryParams)->get();
    }

    public function getById($id, array|null $relations = null)
    {
        $tour = Tour::query();
        if ($relations) {
            foreach ($relations as $relation) {
                $tour->with($relation);
            }
        }
        return $tour->findOrFail($id);
    }

    public function store(array $data, $planPicture, $images)
    {
        try {
            foreach ($images as $value) {
                $imagesLink[] = $value->store('img/tour', 'public');
            }

            $planPictureImg = $planPicture->store('img/tour_plan_picture', 'public');
            $data['plan_picture'] = $planPictureImg;
            $data['images'] = $imagesLink;
            return Tour::create($data);

        } catch (\Exception $ex) {
            $imagesLink[] = $planPictureImg;
            foreach ($imagesLink as $image) {
                if (Storage::disk('public')->exists($image)) Storage::disk('public')->delete($image);
            }
            return $ex;
        }
    }

    public function update($id, array $data, $planPicture, $images)
    {
        if ($planPicture) $data['plan_picture'] = $planPicture->store('img/tour_plan_picture', 'public');

        if ($images) {
            foreach ($images as $value) {
                $data['images'][] = $value->store('img/tour', 'public');
            }
        }
        // TODO: Добавить логику удаления старых картинок и обработки исключений (как в отзывах)
        Tour::whereId($id)->update($data);
        return Tour::findOrFail($id);
    }

    public function delete($id)
    {
        return Tour::destroy($id);
    }
}

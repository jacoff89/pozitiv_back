<?php

namespace App\Repositories;

use App\Models\Review;
use App\Interfaces\ReviewRepositoryInterface;
use Buglinjo\LaravelWebp\Webp;
use Illuminate\Support\Facades\Storage;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function index(array $queryParams, $filter)
    {
        return $filter->apply(Review::query(), $queryParams)->get();
    }

    public function getById($id)
    {
        return Review::findOrFail($id);
    }

    public function store(array $data, $image)
    {
        try {
            $uploadImage = $this->uploadImage($image);
            $data = array_merge($uploadImage, $data);
            return Review::create($data);

        } catch (\Exception $ex) {
            $this->deleteImages($uploadImage);
            return $ex;
        }
    }

    public function update($id, array $data, $image)
    {
        try {
            if ($image) {
                $review = Review::findOrFail($id);
                $oldImages = [
                    'img' => $review->img,
                    'img_webp' => $review->img_webp,
                ];

                $uploadImage = $this->uploadImage($image);
                $data = array_merge($uploadImage, $data);
                Review::whereId($id)->update($data);
                $this->deleteImages($oldImages);
            } else {
                Review::whereId($id)->update($data);
                $this->deleteImages($oldImages);
            }

            return Review::findOrFail($id);

        } catch (\Exception $ex) {
            if ($image && isset($uploadImage)) $this->deleteImages($uploadImage);
            return $ex;
        }
    }

    public function delete($id): void
    {
        $review = Review::findOrFail($id);
        $images = [
            'img' => $review->img,
            'img_webp' => $review->img_webp,
        ];
        Review::destroy($id);
        $this->deleteImages($images);
    }

    private function uploadImage($image): array
    {
        $img = $image->store('img/review', 'public');
        $img_webp = 'img/review/' . basename($img, ".jpg") . '.webp';
        (Webp::make($image))->save(Storage::disk('public')->path($img_webp));

        return [
            'img' => $img,
            'img_webp' => $img_webp,
        ];
    }

    private function deleteImages(array $images): void
    {
        foreach ($images as $image) {
            if (Storage::disk('public')->exists($image)) Storage::disk('public')->delete($image);
        }
    }
}

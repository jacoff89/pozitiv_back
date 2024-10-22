<?php

namespace App\Repositories;
use App\Models\Trip;
use App\Interfaces\TripRepositoryInterface;

class TripRepository implements TripRepositoryInterface
{
    public function index(){
        return Trip::all();
    }

    public function getById($id){
        return Trip::findOrFail($id);
    }

    public function store(array $data){
        return Trip::create($data);
    }

    public function update(array $data,$id){
        return Trip::whereId($id)->update($data);
    }

    public function delete($id){
        Trip::destroy($id);
    }
}

<?php

namespace App\Repositories;

use App\Interfaces\PaymentRepositoryInterface;
use App\Models\Payment;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function index()
    {
        return Payment::all();
    }

    public function getById($id)
    {
        return Payment::findOrFail($id);
    }

    public function store(array $data)
    {
        return Payment::create($data);
    }

    public function update($id, array $data)
    {
        Payment::whereId($id)->update($data);
        return Payment::findOrFail($id);
    }

    public function delete($id)
    {
        return Payment::destroy($id);
    }
}

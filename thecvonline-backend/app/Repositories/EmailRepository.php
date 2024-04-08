<?php

namespace App\Repositories;

use App\Models\Email;
use Illuminate\Support\Facades\Validator;

class EmailRepository implements EmailRepositoryInterface
{
    public $model;

    public function __construct(Email $model)
    {
        $this->model = $model;
    }

    public function storeValidation(array $data = [])
    {
        return Validator::make($data, [
            'email' => 'required|email',
            'city' => 'required|string',
            'requirements' => 'required',
        ]);
    }

    public function updateValidation(array $data = [])
    {
        return Validator::make($data, [
            'email' => 'required|email',
            'city' => 'required|string',
            'requirements' => 'required',
        ]);
    }

    public function latest()
    {
        $query = $this->model->query();

        return $this->model->timestamps ? $query->latest() : $query->latest('id'); 
    }

    public function store(array $data = [])
    {
        return $this->model->create($data);
    }

    public function update(array $data = [], $id)
    {
        $email = $this->model->findOrFail($id);
        $email->fill($data);
        $email->save();
        return $email;
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        $model->delete();
    }

    public function getPaginated(array $data = [])
    {
        $pagination_length = $data['page_size'] ?? config("general.request.pagination_length");
        $query = $this->latest()->listingInfo()->filters($data);

        return $query->paginate($pagination_length);
    }

    public function updateOrCreate(array $data = [])
    {
        return $this->model->updateOrCreate(['email' => $data['email']], $data);
    }
}
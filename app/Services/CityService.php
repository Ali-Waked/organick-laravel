<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Support\Collection;

class CityService
{
    public function getAll(?object $filter = null): Collection
    {
        return City::filter($filter)->get();
    }

    public function get(City $city): City
    {
        return $city->append(['number_of_orders', 'number_of_customers']);
    }

    public function create(array $data): City
    {
        return City::create($data);
    }

    public function update(City $city, array $data): bool
    {
        return $city->update($data);
    }

    public function delete(City $city): bool
    {
        return $city->delete();
    }
}

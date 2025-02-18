<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\CityRequest;
use App\Models\City;
use App\Services\CityService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class CityController
{
    public function __construct(protected CityService $cityService)
    {
        //
    }

    /**
     * Get All Cities
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function index(Request $request): Collection
    {
        return $this->cityService->getAll(json_decode($request->filter));
    }

    /**
     * Add New City
     * @param \App\Http\Requests\CityRequest $request
     * @return \App\Models\City
     */
    public function store(CityRequest $request): City
    {
        return $this->cityService->create($request->validated());
    }

    /**
     * Get City
     * @param \App\Models\City $city
     * @return \App\Models\City
     */
    public function show(City $city): City
    {
        return $this->cityService->get($city);
    }

    /**
     * Update City
     * @param \App\Http\Requests\CityRequest $request
     * @param \App\Models\City $city
     * @return City
     */
    public function update(CityRequest $request, City $city): City
    {
        $this->cityService->update($city, $request->validated());
        return $city;
    }
    /**
     * Delete City
     * @param \App\Models\City $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city): Response
    {
        $this->cityService->delete($city);
        return response()->noContent(200);
    }
}

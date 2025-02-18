<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Services\MyServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;

class ServiceController extends Controller
{

    public function __construct(protected MyServices $service)
    {
        //
    }

    /**
     * Get All Services
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        return $this->service->getServices($request->filter);
    }

    /**
     * Show Service Details
     *
     * @param Service $service
     * @return Service
     */
    public function show(Service $service): Service
    {
        return $this->service->show($service);
    }

    /**
     * Update Service Information
     *
     * @param ServiceRequest $request
     * @param Service $service
     * @return JsonResponse
     */
    public function update(ServiceRequest $request, Service $service): JsonResponse
    {
        $this->service->update($service, $request->validated());
        return Response::json([
            'message' => 'update service successflly',
        ]);
    }
}

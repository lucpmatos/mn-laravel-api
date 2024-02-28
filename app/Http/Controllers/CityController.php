<?php

namespace App\Http\Controllers;

use App\Interfaces\CityRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{

    public function __construct(
        private readonly CityRepositoryInterface $cityRepository
    ){}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->cityRepository->getAll(
                perPage: $request->has('perPage') ? $request->input('perPage') : 0,
                params: $request->has('params') ? json_decode($request->input('params'), true) : []
            )
        );
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            $this->cityRepository->getById(id: $id)
        );
    }

}

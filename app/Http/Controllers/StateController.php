<?php

namespace App\Http\Controllers;

use App\Interfaces\StateRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StateController extends Controller
{

    public function __construct(
        private readonly StateRepositoryInterface $stateRepository
    ){}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->stateRepository->getAll(
                perPage: $request->has('perPage') ? $request->input('perPage') : 0,
                params: $request->has('params') ? json_decode($request->input('params'), true) : []
            )
        );
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            $this->stateRepository->getById(id: $id)
        );
    }

}

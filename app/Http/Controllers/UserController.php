<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ){}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->userRepository->getAll(
                perPage: $request->has('perPage') ? $request->input('perPage') : 0,
                params: $request->has('params') ? json_decode($request->input('params'), true) : []
            )
        );
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            $this->userRepository->getById(id: $id)
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->only(['name','email','password']);
        $user = $this->userRepository->create(data: $data);

        if(!$user){
            return response()->json([
                'error' => 'Unable to create user.'
            ], 401);
        }

        return response()->json($user);
    }

    public function update(int $id, UpdateUserRequest $request): JsonResponse
    {
        $data = $request->only(['name','email','password']);
        $userUpdated = $this->userRepository->update(id: $id, data: $data);

        if(!$userUpdated instanceof User){
            return response()->json([
                'error' => 'Unable to update user.'
            ], 401);
        }

        return response()->json($userUpdated);
    }

    public function destroy(int $id): JsonResponse
    {
        $status = $this->userRepository->delete(id: $id);

        if(!$status){
            return response()->json([
                'error' => 'Unable to delete user.'
            ], 401);
        }

        return response()->json([
            'success' => 'User has been deleted.'
        ]);
    }

}

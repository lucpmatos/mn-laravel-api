<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Interfaces\AddressRepositoryInterface;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    public function __construct(
        private readonly AddressRepositoryInterface $addressRepository
    ){}

    public function index(int $user, Request $request): JsonResponse
    {
        return response()->json(
            $this->addressRepository->getAll(
                perPage: $request->has('perPage') ? $request->input('perPage') : 0,
                params: $request->has('params') ? json_decode($request->input('params'), true) : []
            )
        );
    }

    public function show(int $user, int $id): JsonResponse
    {
        return response()->json(
            $this->addressRepository->getById(id: $id)
        );
    }

    public function store(int $user, StoreAddressRequest $request): JsonResponse
    {
        $data = $request->only(['city_id','address','number','postal_code','description']);
        $data['user_id'] = $user;
        $address = $this->addressRepository->create(data: $data);

        if(!$address){
            return response()->json([
                'error' => 'Unable to create address.'
            ], 401);
        }

        return response()->json($address);
    }

    public function update(int $user, int $id, UpdateAddressRequest $request): JsonResponse
    {
        $data = $request->only(['city_id','address','number','postal_code','description']);
        $addressUpdated = $this->addressRepository->update(id: $id, data: $data);

        if(!$addressUpdated instanceof Address){
            return response()->json([
                'error' => 'Unable to update address.'
            ], 401);
        }

        return response()->json($addressUpdated);
    }

    public function destroy(int $user, int $id): JsonResponse
    {
        $status = $this->addressRepository->delete(id: $id);

        if(!$status){
            return response()->json([
                'error' => 'Unable to delete address.'
            ], 401);
        }

        return response()->json([
            'success' => 'Address has been deleted.'
        ]);
    }

}

<?php

namespace App\Repositories;

use App\Interfaces\AddressRepositoryInterface;
use App\Models\Address;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class AddressRepository implements AddressRepositoryInterface
{

    public function getAll(int $perPage = 0, $params = []): Collection|LengthAwarePaginator|array
    {
        $data = Address::with(['user','city.state'])->select()
            ->when(count($params) > 0, function ($query) use($params){
                foreach ($params as $param){
                    $query->where($param['column'], $param['operator'], $param['value']);
                }
            })
            ->orderBy('id', 'desc');
        return $perPage > 0 ? $data->paginate($perPage) : $data->get();
    }

    public function getAllByUser(int $userId, int $perPage = 0, $params = []): Collection|LengthAwarePaginator|array
    {
        $data = Address::with(['user','city.state'])->select()
            ->when(count($params) > 0, function ($query) use($params){
                foreach ($params as $param){
                    $query->where($param['column'], $param['operator'], $param['value']);
                }
            })
            ->where('user_id', $userId)
            ->orderBy('id', 'desc');
        return $perPage > 0 ? $data->paginate($perPage) : $data->get();
    }

    public function getById(int $id): Model|Collection|Builder|array|null
    {
        return Address::with(['user','city.state'])->find($id);
    }

    public function create(array $data): bool|Address
    {
        try {
            DB::beginTransaction();
            $model = Address::create($data);
        }catch (Throwable $e){
            report($e);
            return false;
        }
        DB::commit();
        return $model;
    }

    public function update(int $id, array $data): bool|Address
    {
        $model = Address::find($id);
        if(!$model instanceof Address){return false;}

        try {
            DB::beginTransaction();
            $model->update($data);
        }catch (Throwable $e){
            report($e);
            return false;
        }
        DB::commit();
        return $model;
    }

    public function delete(int $id): bool
    {
        $model = Address::find($id);
        if(!$model instanceof Address){return false;}

        if($model->delete()){
            return true;
        }
        return false;
    }

}

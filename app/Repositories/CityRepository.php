<?php

namespace App\Repositories;

use App\Interfaces\CityRepositoryInterface;
use App\Models\City;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class CityRepository implements CityRepositoryInterface
{

    public function getAll(int $perPage = 0, $params = []): Collection|LengthAwarePaginator|array
    {
        $data = City::with(['state'])->select()
            ->when(count($params) > 0, function ($query) use($params){
                foreach ($params as $param){
                    $query->where($param['column'], $param['operator'], $param['value']);
                }
            })
            ->orderBy('name');
        return $perPage > 0 ? $data->paginate($perPage) : $data->get();
    }

    public function getById(int $id): Model|Collection|Builder|array|null
    {
        return City::with(['state'])->find($id);
    }

    public function create(array $data): bool|City
    {
        try {
            DB::beginTransaction();
            $model = City::create($data);
        }catch (Throwable $e){
            report($e);
            return false;
        }
        DB::commit();
        return $model;
    }

    public function update(int $id, array $data): bool|City
    {
        $model = City::find($id);
        if(!$model instanceof City){return false;}

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
        $model = City::find($id);
        if(!$model instanceof City){return false;}

        if($model->delete()){
            return true;
        }
        return false;
    }

}

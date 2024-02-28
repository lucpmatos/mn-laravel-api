<?php

namespace App\Repositories;

use App\Interfaces\StateRepositoryInterface;
use App\Models\State;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class StateRepository implements StateRepositoryInterface
{

    public function getAll(int $perPage = 0, $params = []): Collection|LengthAwarePaginator|array
    {
        $data = State::with(['cities'])->select()
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
        return State::with(['cities'])->find($id);
    }

    public function create(array $data): bool|State
    {
        try {
            DB::beginTransaction();
            $model = State::create($data);
        }catch (Throwable $e){
            report($e);
            return false;
        }
        DB::commit();
        return $model;
    }

    public function update(int $id, array $data): bool|State
    {
        $model = State::find($id);
        if(!$model instanceof State){return false;}

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
        $model = State::find($id);
        if(!$model instanceof State){return false;}

        if($model->delete()){
            return true;
        }
        return false;
    }

}

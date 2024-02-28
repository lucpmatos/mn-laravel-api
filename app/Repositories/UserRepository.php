<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserRepository implements UserRepositoryInterface
{

    public function getAll(int $perPage = 0, $params = []): Collection|LengthAwarePaginator|array
    {
        $data = User::with(['addresses.city.state'])->select()
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
        return User::with(['addresses.city.state'])->find($id);
    }

    public function create(array $data): bool|User
    {
        $data['password'] = Hash::make($data['password']);
        try {
            DB::beginTransaction();
            $model = User::create($data);
        }catch (Throwable $e){
            report($e);
            return false;
        }
        DB::commit();
        return $model;
    }

    public function update(int $id, array $data): bool|User
    {
        $model = User::find($id);
        if(!$model instanceof User){return false;}
        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }

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
        $model = User::find($id);
        if(!$model instanceof User){return false;}

        if($model->delete()){
            return true;
        }
        return false;
    }

}

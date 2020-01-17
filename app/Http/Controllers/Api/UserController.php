<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\User;
use Illuminate\Http\Request;
use CodeShopping\Common\OnlyTrashed;
use CodeShopping\Events\UserCreatedEvent;
use CodeShopping\Http\Filters\UserFilter;
use CodeShopping\Http\Requests\UserRequest;
use CodeShopping\Http\Controllers\Controller;
use CodeShopping\Http\Resources\UserResource;

class UserController extends Controller
{
    use OnlyTrashed;
    
    public function index(Request $request)
    {
        /*$query = User::query();
        $query = $this->onlyTrashedIfRequested($request, $query);
        $users = $query->paginate(10);
        return UserResource::collection($users);*/

        $filter = app(UserFilter::class);
        $filterQuery = User::filtered($filter);
        if($request->has('all')) $users = $filterQuery->get();
        else $users = $filterQuery->paginate(10);
        return UserResource::collection($users);
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->all());
        event(new UserCreatedEvent($user));
        return new UserResource($user);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(UserRequest $request, User $user)
    {
        $user->fill($request->all());
        $user->save();
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([], 204);
    }

    public function restore(User $user)
    {
        $user->restore();
        return response()->json([], 204);
    }
}

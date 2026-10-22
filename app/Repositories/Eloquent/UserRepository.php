<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->with(['role'])->where('email', $email)->first();
    }

    public function getCustomersPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->whereHas('role', fn ($q) => $q->where('slug', Role::CUSTOMER))
            ->withCount(['bookings', 'vehicles'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}

<?php
namespace App\Http\Services\Users;

use App\Exceptions\ErrorException;
use App\Http\Services\BaseService;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class UserAdminService extends BaseService
{
    /**
     * @param array $data
     * @return Collection
     */
    public function getAll(array $data) : Collection
    {
        $user = User::query();

        if (isset($data['status'])) {
            $user = $user->ofStatus($data['status']);
        }

        $user = $user
            ->when(data_get($data, 'search'), function($query) use ($data) {
                $query->where(function($query1) use($data) {
                    $query1->where('first_name', 'LIKE', "%{$data['search']}%")
                        ->orWhere('last_name', 'LIKE', "%{$data['search']}%")
                        ->orWhere('username', 'LIKE', "%{$data['search']}%")
                        ->orWhere('email', 'LIKE', "%{$data['search']}%");
                    })
                    ->orWhere(function($query1) use($data) {
                        $query1->whereHas('country', function (Builder $builder) use ($data) {
                            $builder->where('label', 'LIKE', "%{$data['search']}%");
                        });
                    });
        });

        return $user->isAdmin()->get();
    }

    /**
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function update(User $user, array $data) : bool
    {
        Log::info(__METHOD__ . " -- user: -- " . $user->email . " -- Update user by admin", $data);

        return $user->update($data);
    }

    /**
     * @param array $data
     * @return User
     * @throws ErrorException
     */
    public function disableUser(array $data) : User
    {
        $user = User::where('id', $data['user_id'])->first();

        if ($user->status == User::USER_STATUS_DISABLED) {
            throw new ErrorException('exception.user_already_disabled');
        }

        $user->update(['status' => User::USER_STATUS_DISABLED]);

        Log::info(__METHOD__ . " -- user: -- " . $user->email . " -- Disabled by admin");

        return $user;
    }
}

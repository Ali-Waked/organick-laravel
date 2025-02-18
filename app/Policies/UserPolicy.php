<?php

namespace App\Policies;

use App\Enums\UserTypes;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $authorizationMap = [
            UserTypes::Member => 'view.members',
            UserTypes::Moderator => 'view.moderators',
            UserTypes::Customer => 'view.user',
        ];

        $ability = $authorizationMap[$user->type] ?? 'view.user';

        return Gate::allows($ability);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        $authorizationMap = [
            UserTypes::Member => 'show.member',
            UserTypes::Moderator => 'show.moderator',
            UserTypes::Customer => 'show.user',
        ];

        $ability = $authorizationMap[$user->type] ?? 'show.user';

        return Gate::allows($ability);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $authorizationMap = [
            UserTypes::Member => 'create.member',
            UserTypes::Moderator => 'create.moderator',
            UserTypes::Customer => 'create.user',
        ];

        $ability = $authorizationMap[$user->type] ?? 'create.user';

        return Gate::allows($ability);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        $authorizationMap = [
            UserTypes::Member => 'update.member',
            UserTypes::Moderator => 'update.moderator',
            UserTypes::Customer => 'update.user',
        ];

        $ability = $authorizationMap[$user->type] ?? 'update.user';

        return Gate::allows($ability);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authUser, User $user): bool
    {
        $authorizationMap = [
            UserTypes::Member => 'delete.member',
            UserTypes::Moderator => 'delete.moderator',
            UserTypes::Customer => 'delete.user',
        ];

        $ability = $authorizationMap[$user->type] ?? 'delete.user';

        return Gate::allows($ability);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\UserTypes;
use App\Events\AddNewMember;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use Nette\Utils\Random;

class MemberController extends Controller
{
    public function __construct(protected ImageService $imageService)
    {
        //
    }
    public function index(Request $request): LengthAwarePaginator
    {
        return User::member(json_decode($request->filter))->paginate();
    }
    public function store(Request $request): User
    {
        $date = now()->subYears(20);
        $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'birthday' => ['required', 'date', "before:$date"],
            'image' => ['required', 'image', 'max:10020'],
            'email' => ['required', 'unique:users,email'],
            'job_title' => ['nullable', 'string', 'max:150'],
        ]);
        // Nette\Utils\Random::generate();
        $password = Str::random(12);
        $request->merge([
            // 'email_verified_at' => now(),
            'password' => Hash::make($password),
            'avatar' => $this->imageService->uploadImage($request->file('image'), 'membersPhoto'),
            'type' => UserTypes::Member->value
        ]);
        $user = User::create($request->all());
        AddNewMember::dispatch($user, $password);
        return $user;
    }

    public function show(User $member): User
    {
        return  $member;
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $date = now()->subYears(20);
        // return response()->json($request->all());
        $request->validate([
            'first_name' => ['sometimes', 'required', 'string'],
            'last_name' => ['sometimes', 'required', 'string'],
            'birthday' => ['sometimes', 'required', 'date', "before:$date"],
            'image' => ['sometimes', 'required', 'image', 'max:10020'],
            'job_title' => ['nullable', 'string', 'max:150'],
        ]);
        // $password = Str::random(12);
        $old_image = $user->avatar;
        if ($request->hasFile('image')) {
            $request->merge([
                'avatar' => $this->imageService->uploadImage($request->file('image'), 'membersPhoto'),
            ]);
        }
        // return  $this->imageService->uploadImage($request->file('image'), 'membersPhoto');
        // return response()->json(['message' => 'User updated successfully'], 200);
        // $request->merge([
        //     'password' => Hash::make($password),

        // ]);
        $user = $user->update($request->all());
        if ($old_image && $request->hasFile('image')) {
            $this->imageService->removeImage($old_image);
        }
        return  response()->json(['message' => 'User updated successfully'], 200);
    }

    public function destroy(User $member): JsonResponse
    {
        $member->delete();
        $this->imageService->removeImage($member->avatar);
        return Response::json([
            'message' =>  'Members deleted successfully',
        ]);
    }
}

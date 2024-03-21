<?php

namespace App\Services\Auth;

use App\Contracts\Interfaces\AuthorInterface;
use App\Enums\RoleEnum;
use SebastianBergmann\Type\VoidType;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Validation\ValidationException;
use App\Contracts\Interfaces\RegisterInterface;
use App\Enums\UploadDiskEnum;
use App\Enums\UserStatusEnum;
use App\Http\Requests\AuthorRequest;

class RegisterService
{
    public function __construct()
    {

    }

    /**
     * Handle a login request to the application.
     *
     * @param LoginRequest $request
     * @return void
     *
     * @throws ValidationException
     */

    public function handleRegister(RegisterRequest $request,RegisterInterface $register): void
    {
        $data = $request->validated();
        $password = bcrypt($data['password']);
        $data['password'] = $password;
        $user = $register->store($data);
        $user->assignRole(RoleEnum::USER);
        return;
    }

    public function registerWithAdmin(RegisterRequest $request): array
    {
        $datas = $request->validated();

        if ($request->hasFile('cv')) {
            $img = $request->file('cv');
            $stored_image = $img->store(UploadDiskEnum::AUTHOR_CV->value , 'public');
        }

        return [
            'name' => $datas['name'],
            'email' => $datas['email'],
            'password' => bcrypt($datas['password']),
            'phone_number' => $datas['phone_number'],
            'address' => $datas['address'],
            'cv' => $stored_image,
        ];
    }
}

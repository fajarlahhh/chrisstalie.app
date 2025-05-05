<?php

namespace App\Actions\Fortify;

use App\Models\Pengguna;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given pengguna's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(Pengguna $pengguna, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($pengguna->id),
            ],
        ])->validateWithBag('updateProfileInformation');

        if ($input['email'] !== $pengguna->email &&
            $pengguna instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($pengguna, $input);
        } else {
            $pengguna->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * Update the given verified pengguna's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(Pengguna $pengguna, array $input): void
    {
        $pengguna->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $pengguna->sendEmailVerificationNotification();
    }
}

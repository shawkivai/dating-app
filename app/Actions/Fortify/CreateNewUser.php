<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
     
    class CreateNewUser implements CreatesNewUsers
    {
        use PasswordValidationRules;
     
        /**
         * Create a newly registered user.
         *
         * @param  array  $input
         * @return \App\Models\User
         */
        public function create(array $input)
        {
            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => $this->passwordRules(),
                'gender'   => ['required','string'],
                'latitude'   => ['required','string'],
                'longitude'   => ['required','string'],
                'date_of_birth'   => ['required','date'],
            ])->validate();
     
            return DB::transaction(function () use ($input) {
                return tap(User::create([
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'password' => Hash::make($input['password']),
                    'gender'   => $input['gender'],
                    'latitude' => $input['latitude'],
                    'longitude' => $input['longitude'],
                    'date_of_birth' => $input['date_of_birth']
                ]), function (User $user) {
                    $this->createTeam($user);
                });
            });
        }
     
        /**
         * Create a personal team for the user.
         *
         * @param  \App\Models\User  $user
         * @return void
         */
        protected function createTeam(User $user)
        {
            $user->ownedTeams()->save(Team::forceCreate([
                'user_id' => $user->id,
                'name' => explode(' ', $user->name, 2)[0]."'s Team",
                'personal_team' => true,
            ]));
        }
    }

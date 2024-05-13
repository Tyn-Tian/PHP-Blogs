<?php

namespace Blog\Service;

use Blog\Config\Database;
use Blog\Domain\User;
use Blog\Exception\ValidationException;
use Blog\Model\UserRegisterRequest;
use Blog\Model\UserRegisterResponse;
use Blog\Repository\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->validateUserRegisterRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByEmail($request->email);

            if ($user != null) {
                throw new ValidationException("Email is registered");
            }

            $user = new User();
            $user->id = $request->id;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
            $this->userRepository->save($user);

            $response = new UserRegisterResponse();
            $response->user = $user;

            Database::commit();
            return $response;
        } catch (ValidationException $exception) {
            Database::rollBack();
            throw $exception;
        }
    }

    private function validateUserRegisterRequest(UserRegisterRequest $request) 
    {
        if (
            $request->email == null || $request->username == null || $request->password == null ||
            trim($request->email) == "" || trim($request->username) == "" || trim($request->password) == ""
        ) {
            throw new ValidationException("Email, username, password cannot be blank");
        }
    }
}
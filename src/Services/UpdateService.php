<?php

namespace App\Services;

use App\Helpers\Validator;
use App\Models\Update;


class UpdateService
{
    private Validator $validator;
    private Update $request;

    public function __construct()
    {
        $this->validator = Validator::getInstance();
        $this->request = new Update();
    }
    public function updateSettings(int $userId, array $data): array
    {
        $validationResult = $this->validator->updateValidation($data);

        if ($validationResult['status'] === 'errors') {
            return $validationResult;
        }

        $result = [];
        if (!empty($data['username'])) {
            $this->request->updateUsername($userId, $data['username']);
            $result['username'] = 'success';
        }

        if (!empty($data['email'])) {
            $this->request->updateEmail($userId, $data['email']);
            $result['email'] = 'success';
        }
        if (!empty($data['new_password'])) {
            $this->request->updatePassword($userId, $data['new_password']);
            $result['password'] = 'success';
        }
        if (isset($data['notify_comments'])) {
            $notify = (bool)$data['notify_comments'];
            $this->request->updateNotifyComments($userId, $notify);
            $result['notify_comments'] = 'success';
        }
        return [
            'status' => 'success',
            'updated' => $result
        ];
    }
}

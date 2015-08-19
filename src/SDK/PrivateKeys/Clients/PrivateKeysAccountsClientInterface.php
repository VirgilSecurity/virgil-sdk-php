<?php

namespace Virgil\SDK\PrivateKeys\Clients;

interface PrivateKeysAccountsClientInterface {

    public function getAccount($accountId);

    public function create($accountId, $accountType, $publicKeyId, $sign, $password);

    public function remove($accountId, $publicKeyId, $sign);

    public function resetPassword($userId, $newPassword);

    public function verifyResetPassword($token);

}
<?php
declare(strict_types=1);

namespace Ixocreate\Admin\Entity;

use Google\Service\Oauth2\Userinfo;

final class GoogleUser {
    private Userinfo $userInfo;
    private string $token;

    public function __construct(Userinfo $userInfo, string $token) {
        $this->userInfo = $userInfo;
        $this->token = $token;
    }

    public function getEmail(): string {
        return $this->userInfo->getEmail();
    }

    public function getPicture(): string {
        return $this->userInfo->getPicture();
    }

    public function getToken(): string {
        return $this->token;
    }
}

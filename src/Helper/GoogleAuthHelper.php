<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Helper;

use Exception;
use GuzzleHttp\Client;
use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Entity\GoogleUser;
use Ixocreate\Application\ApplicationConfig;

class GoogleAuthHelper {
    private ApplicationConfig $applicationConfig;
    private AdminConfig $adminConfig;

    public function __construct(ApplicationConfig $applicationConfig, AdminConfig $adminConfig) {
        $this->applicationConfig = $applicationConfig;
        $this->adminConfig = $adminConfig;
    }

    public function isAllowed(): bool {
        return !empty($this->applicationConfig->getLoginTypes()['google']);
    }

    /**
     * @throws Exception
     */
    private function throwIfNotAllowed(): void {
        if (!$this->isAllowed()) {
            throw new Exception('Google Auth not enabled.');
        }
    }

    /**
     * @throws Exception
     */
    public function getClient(): \Google_Client {
        $this->throwIfNotAllowed();

        $client = new \Google_Client();
        $client->setClientId($this->getClientId());
        $client->setClientSecret($this->getClientSecret());
        $client->setRedirectUri((empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]" . $this->adminConfig->uri()->getPath() . '/google-auth-callback');
        $client->addScope('https://www.googleapis.com/auth/userinfo.email');
        $client->addScope('https://www.googleapis.com/auth/userinfo.profile');
        if ($this->getAllowedGroups() > 0) {
            $client->addScope('https://www.googleapis.com/auth/cloud-identity.groups.readonly');
        }

        return $client;
    }

    /**
     * @throws Exception
     */
    public function getUser(string $code): GoogleUser {
        $client = $this->getClient();
        $token = $client->fetchAccessTokenWithAuthCode($code);
        $client->setAccessToken($token['access_token']);

        $oauth = new \Google_Service_Oauth2($client);
        return new GoogleUser($oauth->userinfo->get(), $token['access_token']);
    }

    /**
     * @throws Exception
     */
    public function userIsAllowed(GoogleUser $googleUser): bool {
        if (!count($this->getAllowedGroups())) {
            return true;
        }
        foreach ($this->getAllowedGroups() as $group) {
            if ($this->userIsInGroup($googleUser, $group)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @throws Exception
     */
    private function userIsInGroup(GoogleUser $googleUser, string $group): bool {
        try {
            $client = new Client();
            $response = $client->get('https://cloudidentity.googleapis.com/v1/groups/' . $group . '/memberships', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $googleUser->getToken(),
                ],
            ]);
            $groupData = json_decode($response->getBody()->getContents(), true)['memberships'];
            foreach ($groupData as $group) {
                if ($group['preferredMemberKey']['id'] === $googleUser->getEmail()) {
                    return true;
                }
            }
        } catch (\Throwable $e) {
            // do nothing if something fails
        }
        return false;
    }

    private function getConfig(): array {
        return $this->applicationConfig->getLoginTypes()['google'] ?? [];
    }

    private function getClientId(): ?string {
        return $this->getConfig()['client_id'] ?? null;
    }

    private function getClientSecret(): ?string {
        return $this->getConfig()['client_secret'] ?? null;
    }

    private function getAllowedGroups(): array {
        return $this->getConfig()['allowed_groups'] ?? [];
    }
}

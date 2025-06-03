<?php

namespace App\Services;

use Illuminate\Support\Str;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Storage;
use Exception;

class FirebaseService
{
    protected Auth $auth;
    protected Storage $storage;
    protected $bucket;

    public function __construct($auth, $storage, $bucket)
    {
        $this->auth = $auth;
        $this->storage = $storage;
        $this->bucket = $bucket;
    }

    public function createUser(array $payload): bool|object
    {
        try {
            $userProperties = [
                'email' => $payload['email'],
                'password' => $payload['password'],
                'displayName' => $payload['user_name'] ?? null,
                'phoneNumber' => $payload['phone_number'] ?? null,
            ];

            if (!empty($payload['avatar_link'])) {
                $userProperties['photoUrl'] = $this->getPublicFileUrl($payload['avatar_link']);
            }

            return $this->auth->createUser($userProperties);
        } catch (Exception $e) {
            throw new Exception('Firebase create user error: ' . $e->getMessage());
        }
    }

    public function updateUserProfile(string $uid, array $data): bool
    {
        try {
            $user = $this->auth->getUser($uid);
            $updateAttributes = [];
            if (!empty($data['email']) && $data['email'] !== $user->email) {
                $updateAttributes['email'] = $data['email'];
            }

            if (!empty($data['password'])) {
                $updateAttributes['password'] = $data['password'];
            }

            if (!empty($data['user_name'])) {
                $updateAttributes['displayName'] = $data['user_name'];
            }

            if (!empty($data['avatar_link'])) {
                $updateAttributes['photoUrl'] = $this->getPublicFileUrl($data['avatar_link']);
            }
            if (!empty($updateAttributes)) {
                $this->auth->updateUser($uid, $updateAttributes);
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function uploadFile($file, $path)
    {
        try {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filePath = $path . '/' . Str::slug($originalName) . '-' .  uniqid() . '.' . $extension;

            $this->bucket->upload(
                fopen($file->getRealPath(), 'r'),
                [
                    'name' => $filePath,
                    'predefinedAcl' => 'publicRead'
                ]
            );

            return [
                'path' => $filePath,
                'url' => $this->getPublicFileUrl($filePath),
            ];
        } catch (Exception $e) {
            throw new Exception('Firebase storage upload error: ' . $e->getMessage());
        }
    }

    public function deleteFile($path)
    {
        try {
            $object = $this->bucket->object($path);
            $object->delete();
            return true;
        } catch (Exception $e) {
            throw new Exception('Firebase delete file error: ' . $e->getMessage());
        }
    }

    public function getPublicFileUrl($path)
    {
        if (empty($path)) {
            return null;
        }

        if(filter_var($path, FILTER_VALIDATE_URL)) return $path;

        $encodedPath = rawurlencode($path);

        return "https://firebasestorage.googleapis.com/v0/b/{$this->bucket->name()}/o/{$encodedPath}?alt=media";
    }


    public function listFiles(string $prefix = '')
    {
        try {
            $files = [];
            $objects = $this->bucket->objects(['prefix' => $prefix]);

            foreach ($objects as $object) {
                $files[] = [
                    'name' => basename($object->name()),
                    'path' => $object->name(),
                    'url' => $object->signedUrl(new \DateTime('2099-01-01')),
                    'size' => $object->info()['size'],
                    'created' => $object->info()['timeCreated'],
                ];
            }

            return $files;
        } catch (Exception $e) {
            throw new Exception('Firebase list files error: ' . $e->getMessage());
        }
    }
}

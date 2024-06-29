<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/UserProfileRepository.php';
require_once PRIVATE_PATH . '/utils.php';

class UserProfileService
{
    private DB $db;
    private UserProfileRepository $userProfileRepository;

    public function __construct(DB $db, UserProfileRepository $userProfileRepository)
    {
        $this->db = $db;
        $this->userProfileRepository = $userProfileRepository;
    }

    public function getProfile(int $user_id): ?UserProfile
    {
        $userProfile = $this->userProfileRepository->get($user_id);
        return $userProfile;
    }

    public function updateProfile(int $user_id, string $full_name, string $bio, ?array $profile_picture): ?UserProfile
    {
        $this->db->beginTransaction();

        try {
            $profile = $this->userProfileRepository->get($user_id);

            if (!$profile) {
                $this->userProfileRepository->create($user_id, $full_name, $bio);
                $profile = $this->userProfileRepository->get($user_id);
            }

            if ($profile_picture && $profile_picture['error'] === UPLOAD_ERR_OK) {
                $target_dir = PROJECT_PATH . '/uploads/';
                $target_file = $target_dir . basename($profile_picture['name']);
                // If the directory does not exist, create it
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                move_uploaded_file($profile_picture['tmp_name'], $target_file);
                $profile->profile_picture = '/uploads/' . basename($profile_picture['name']);
            }

            $profile->full_name = $full_name;
            $profile->bio = $bio;

            $updatedProfile = $this->userProfileRepository->update($profile->user_id, $profile->full_name, $profile->bio, $profile->profile_picture);
            $this->db->commit();
            return $updatedProfile;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
?>
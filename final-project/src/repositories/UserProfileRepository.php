<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/models/UserProfile.php';

class UserProfileRepository
{
    private DB $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function create(int $user_id, ?string $full_name = null, ?string $bio = null, ?string $profile_picture = null): void
    {
        $params = ['user_id' => $user_id];
        $fields = ['user_id'];

        if ($full_name !== null) {
            $params['full_name'] = $full_name;
            $fields[] = 'full_name';
        }

        if ($bio !== null) {
            $params['bio'] = $bio;
            $fields[] = 'bio';
        }

        if ($profile_picture !== null) {
            $params['profile_picture'] = $profile_picture;
            $fields[] = 'profile_picture';
        }

        $sql = sprintf(
            "INSERT INTO user_profiles (%s) VALUES (:%s)",
            implode(', ', $fields),
            implode(', :', $fields)
        );

        $this->db->run($sql, $params);
    }

    public function get(int $user_id): ?UserProfile
    {
        $stmt = $this->db->run(
            "SELECT * FROM user_profiles WHERE user_id = :user_id LIMIT 1",
            ['user_id' => $user_id]
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, UserProfile::class);
        $profile = $stmt->fetch();
        return $profile ? $profile : NULL;
    }

    public function update(int $user_id, ?string $full_name = null, ?string $bio = null, ?string $profile_picture = null): ?UserProfile
    {
        $profile = $this->get($user_id);
        if (!$profile) {
            return NULL;
        }

        $params = ['user_id' => $user_id];
        $fields = [];

        if ($full_name !== null) {
            $params['full_name'] = $full_name;
            $fields[] = 'full_name = :full_name';
        }

        if ($bio !== null) {
            $params['bio'] = $bio;
            $fields[] = 'bio = :bio';
        }

        if ($profile_picture !== null) {
            $params['profile_picture'] = $profile_picture;
            $fields[] = 'profile_picture = :profile_picture';
        }

        if (empty($fields)) {
            return $profile; // No fields to update
        }

        $sql = sprintf(
            "UPDATE user_profiles SET %s WHERE user_id = :user_id",
            implode(', ', $fields)
        );

        $stmt = $this->db->run($sql, $params);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, UserProfile::class);
        $stmt->fetch();
        return $this->get($user_id);
    }

    public function delete(int $user_id): void
    {
        $this->db->run(
            "DELETE FROM user_profiles WHERE user_id = :user_id",
            ['user_id' => $user_id]
        );
    }
}
?>
<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/models/Topic.php';
class TopicRepository
{
    private DB $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function create(int $section_id, int $user_id, string $title, string $content): void
    {
        $this->db->run(
            "INSERT INTO topics (section_id, user_id, title, content) VALUES (:section_id, :user_id, :title, :content)",
            [
                'section_id' => $section_id,
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content
            ]
        );
    }

    public function get(int $id): ?Topic
    {
        $stmt = $this->db->run(
            "SELECT * FROM topics WHERE id = :id",
            ['id' => $id]
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Topic::class);
        $result = $stmt->fetch();
        return $result ? $result : NULL;
    }

    /**
     * @return Topic[]
     */
    public function getAllBySection(int $section_id): array
    {
        return $this->db->run(
            "SELECT * FROM topics WHERE section_id = :section_id",
            ['section_id' => $section_id]
        )->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Topic::class);
    }

    public function update(int $id, ?string $title = NULL, ?string $content = NULL): ?Topic
    {
        $topic = $this->get($id);
        if (!$topic) {
            return NULL;
        }

        $params = ['id' => $id];
        $fields = [];

        if ($title !== null) {
            $params['title'] = $title;
            $fields[] = 'title = :title';
        }

        if ($content !== null) {
            $params['content'] = $content;
            $fields[] = 'content = :content';
        }

        if (empty($fields)) {
            return $topic; // No fields to update
        }

        $sql = sprintf(
            "UPDATE topics SET %s WHERE id = :id",
            implode(', ', $fields)
        );

        $stmt = $this->db->run($sql, $params);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Topic::class);
        $stmt->fetch();
        return $this->get($id);
    }

    public function delete(int $id): void
    {
        $this->db->run(
            "DELETE FROM topics WHERE id = :id",
            ['id' => $id]
        );
    }

    /**
     * Get the latest topics by section IDs with user info.
     *
     * @param int[] $section_ids The array of section IDs.
     * @return array The array of latest topics with user info.
     */
    public function getLatestTopicsBySections(array $section_ids): array
    {
        if (empty($section_ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($section_ids), '?'));
        $sql = "
            SELECT t1.*, u.username
            FROM topics t1
            JOIN (
                SELECT section_id, MAX(created_at) as max_created_at
                FROM topics
                WHERE section_id IN ($placeholders)
                GROUP BY section_id
            ) t2 ON t1.section_id = t2.section_id AND t1.created_at = t2.max_created_at
            JOIN users u ON t1.user_id = u.id
        ";

        $stmt = $this->db->run($sql, $section_ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
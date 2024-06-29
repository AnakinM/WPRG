<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/models/Comment.php';

class CommentRepository
{
    private DB $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function create(int $topic_id, ?int $user_id, ?string $nickname, string $content): void
    {
        $this->db->run(
            "INSERT INTO comments (topic_id, user_id, nickname, content) VALUES (:topic_id, :user_id, :nickname, :content)",
            [
                'topic_id' => $topic_id,
                'user_id' => $user_id,
                'nickname' => $nickname,
                'content' => $content
            ]
        );
    }

    public function get(int $id): ?Comment
    {
        $stmt = $this->db->run(
            "SELECT * FROM comments WHERE id = :id",
            ['id' => $id]
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Comment::class);
        return $stmt->fetch();
    }

    /**
     * @return Comment[]
     */
    public function getAllByTopic(int $topic_id): array
    {
        return $this->db->run(
            "SELECT c.*, u.username
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.topic_id = :topic_id
            ORDER BY c.created_at DESC",
            ['topic_id' => $topic_id]
        )->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Comment::class);
    }
    public function update(int $id, string $content): ?Comment
    {
        $stmt = $this->db->run(
            "UPDATE comments SET content = :content WHERE id = :id",
            [
                'id' => $id,
                'content' => $content
            ]
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Comment::class);
        $stmt->fetch();
        return $this->get($id);
    }

    public function delete(int $id): void
    {
        $this->db->run(
            "DELETE FROM comments WHERE id = :id",
            ['id' => $id]
        );
    }
}
?>
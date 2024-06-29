<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/models/Comment.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/CommentRepository.php';
require_once PRIVATE_PATH . '/repositories/UserRepository.php';
require_once PRIVATE_PATH . '/models/enums.php';
require_once PRIVATE_PATH . '/utils.php';
class CommentService
{
    private DB $db;
    private CommentRepository $commentRepository;
    private UserRepository $userRepository;

    public function __construct(DB $db, CommentRepository $commentRepository, UserRepository $userRepository)
    {
        $this->db = $db;
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
    }

    private function isAllowedToEditOrDelete(int $user_id, ?int $comment_user_id): bool
    {
        $role = $_SESSION['role'] ?? null;
        return $role === Role::Administrator->value ||
            $role === Role::Moderator->value ||
            ($role === Role::User->value && $user_id === $comment_user_id);
    }

    public function getComment(int $id): ?Comment
    {
        return $this->commentRepository->get($id);
    }

    public function addComment(int $topic_id, ?int $user_id, ?string $nickname, string $content): bool
    {
        $this->db->beginTransaction();

        try {
            $this->commentRepository->create($topic_id, $user_id, $nickname, $content);
            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function getCommentsByTopic(int $topic_id): array
    {
        return $this->commentRepository->getAllByTopic($topic_id);
    }

    public function updateComment(int $id, string $content): ?Comment
    {
        $comment = $this->commentRepository->get($id);

        if (!$comment) {
            throw new Exception("Comment not found.");
        }

        if (!$this->isAllowedToEditOrDelete($_SESSION['user_id'], $comment->user_id)) {
            throw new Exception("You do not have permission to update this comment.");
        }

        $this->db->beginTransaction();

        try {
            $updatedComment = $this->commentRepository->update($id, $content);
            if ($updatedComment === null) {
                $this->db->rollback();
                return null;
            }

            $this->db->commit();
            return $updatedComment;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function deleteComment(int $id): bool
    {
        $comment = $this->commentRepository->get($id);

        if (!$comment) {
            throw new Exception("Comment not found.");
        }

        if (!$this->isAllowedToEditOrDelete($_SESSION['user_id'], $comment->user_id)) {
            throw new Exception("You do not have permission to delete this comment.");
        }

        $this->db->beginTransaction();

        try {
            $this->commentRepository->delete($id);
            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
?>
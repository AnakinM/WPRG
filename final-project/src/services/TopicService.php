<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/models/Topic.php';
require_once PRIVATE_PATH . '/utils.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/TopicRepository.php';
require_once PRIVATE_PATH . '/repositories/SectionRepository.php';
require_once PRIVATE_PATH . '/repositories/UserRepository.php';
require_once PRIVATE_PATH . '/models/enums.php';

class TopicService
{
    private DB $db;
    private TopicRepository $topicRepository;
    private SectionRepository $sectionRepository;
    private UserRepository $userRepository;

    public function __construct(DB $db, TopicRepository $topicRepository, SectionRepository $sectionRepository, UserRepository $userRepository)
    {
        $this->db = $db;
        $this->topicRepository = $topicRepository;
        $this->sectionRepository = $sectionRepository;
        $this->userRepository = $userRepository;
    }

    private function isAllowedToEditOrDelete(int $user_id, int $topic_user_id): bool
    {
        $role = $_SESSION['role'] ?? null;
        return $role === Role::Administrator->value ||
            ($role === Role::Moderator->value && $_SESSION['user_id'] === $user_id) ||
            ($role === Role::User->value && $_SESSION['user_id'] === $topic_user_id);
    }

    public function getTopic(int $id): ?Topic
    {
        return $this->topicRepository->get($id);
    }
    public function createTopic(int $section_id, int $user_id, string $title, string $content): bool
    {
        $this->db->beginTransaction();

        try {
            $this->topicRepository->create($section_id, $user_id, $title, $content);
            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function updateTopic(int $id, ?string $title = null, ?string $content = null): bool
    {
        $topic = $this->topicRepository->get($id);

        if (!$topic) {
            throw new Exception("Topic not found.");
        }

        if (!$this->isAllowedToEditOrDelete($_SESSION['user_id'], $topic->user_id)) {
            throw new Exception("You do not have permission to update this topic.");
        }

        $this->db->beginTransaction();

        try {
            $updatedTopic = $this->topicRepository->update($id, $title, $content);

            if ($updatedTopic === null) {
                $this->db->rollback();
                return false; // Topic update failed
            }

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function removeTopic(int $id): bool
    {
        $topic = $this->topicRepository->get($id);

        if (!$topic) {
            throw new Exception("Topic not found.");
        }

        if (!$this->isAllowedToEditOrDelete($_SESSION['user_id'], $topic->user_id)) {
            throw new Exception("You do not have permission to delete this topic.");
        }

        $this->db->beginTransaction();

        try {
            $this->topicRepository->delete($id);
            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * Get all topics within a specified section.
     *
     * @param int $section_id The ID of the section.
     * @return Topic[] The array of topics.
     */
    public function getAllTopicsBySection(int $section_id): array
    {
        return $this->topicRepository->getAllBySection($section_id);
    }

    /**
     * Get the latest topic for each section.
     *
     * @return array An associative array with section IDs as keys and the latest topic as values.
     */
    public function getLatestTopicsBySection(): array
    {
        $sections = $this->sectionRepository->getAll();
        $section_ids = array_map(fn($section) => $section->id, $sections);
        $latestTopics = $this->topicRepository->getLatestTopicsBySections($section_ids);

        $latestTopicsBySection = [];
        foreach ($latestTopics as $topic) {
            $latestTopicsBySection[$topic['section_id']] = $topic;
        }

        return $latestTopicsBySection;
    }
}
?>
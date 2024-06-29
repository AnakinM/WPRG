<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/utils.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/SectionRepository.php';

class SectionService
{
    private DB $db;
    private SectionRepository $sectionRepository;

    public function __construct(DB $db, SectionRepository $sectionRepository)
    {
        $this->db = $db;
        $this->sectionRepository = $sectionRepository;
    }

    public function createSection(string $name, string $description): bool
    {
        if (!isAdmin()) {
            throw new Exception("Only administrators can create sections.");
        }

        if ($this->sectionRepository->getBy('name', $name)) {
            return false; // Section already exists
        }

        $this->db->beginTransaction();

        try {
            $this->sectionRepository->create($name, $description);
            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function updateSection(int $id, ?string $name = null, ?string $description = null): ?Section
    {
        if (!isAdmin()) {
            throw new Exception("Only administrators can update sections.");
        }

        $this->db->beginTransaction();

        try {
            $updatedSection = $this->sectionRepository->update($id, $name, $description);

            if ($updatedSection === null) {
                $this->db->rollback();
                return null;
            }

            $this->db->commit();
            return $updatedSection;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function removeSection(int $id): bool
    {
        if (!isAdmin()) {
            throw new Exception("Only administrators can remove sections.");
        }

        $this->db->beginTransaction();

        try {
            $this->sectionRepository->delete($id);
            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function getAllSections(): array
    {
        return $this->sectionRepository->getAll();
    }

    public function getSection(int $id): ?Section
    {
        return $this->sectionRepository->get($id);
    }
}
?>
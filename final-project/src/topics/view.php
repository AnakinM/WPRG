<?php
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/TopicRepository.php';
require_once PRIVATE_PATH . '/repositories/CommentRepository.php';
require_once PRIVATE_PATH . '/services/TopicService.php';
require_once PRIVATE_PATH . '/services/CommentService.php';
require_once PRIVATE_PATH . '/SessionManager.php';
require_once PRIVATE_PATH . '/utils.php';
SessionManager::startSession();
// Initialize the database connection
$db = new DB(DSN, USERNAME, PASSWORD);

// Initialize repositories
$topicRepository = new TopicRepository($db);
$commentRepository = new CommentRepository($db);

// Initialize services
$topicService = new TopicService($db, $topicRepository, new SectionRepository($db), new UserRepository($db));
$commentService = new CommentService($db, $commentRepository, new UserRepository($db));

$topic_id = $_GET['id'];
$topic = $topicService->getTopic($topic_id);
$comments = $commentService->getCommentsByTopic($topic_id);

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';
$user_logged_in = isset($_SESSION['user_id']);
$isOwner = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $topic->user_id;
?>

<div class="container mt-5">
    <h1><?php echo htmlspecialchars($topic->title); ?></h1>
    <p><?php echo htmlspecialchars($topic->content); ?></p>
    <small>By <?php echo htmlspecialchars($topic->user_id); ?> on
        <?php echo $topic->created_at->format('Y-m-d H:i:s'); ?></small>
    <hr>
    <?php if (isAdmin() || $isOwner || isModerator()): ?>
        <?php if (isAdmin() || $isOwner): ?>
            <a href="edit.php?id=<?php echo $topic->id; ?>" class="btn btn-primary">Edit Topic</a>
            <a href="delete.php?id=<?php echo $topic->id; ?>" class="btn btn-danger"
                onclick="return confirm('Are you sure you want to delete this topic?');">Delete Topic</a>
        <?php elseif (isModerator()): ?>
            <a href="edit.php?id=<?php echo $topic->id; ?>" class="btn btn-primary">Edit Topic</a>
        <?php endif; ?>
        <hr>
    <?php endif; ?>
    <h3>Comments</h3>
    <?php if ($comments): ?>
        <?php foreach ($comments as $comment): ?>
            <div class="comment mb-3">
                <p><?php echo htmlspecialchars($comment->content); ?></p>
                <?php if ($comment->nickname): ?>
                    <small>By <?php echo htmlspecialchars($comment->nickname); ?> on
                    <?php else: ?>
                        <small>By <a
                                href="/profiles/view.php?id=<?php echo $comment->user_id; ?>"><?php echo htmlspecialchars($comment->username); ?></a>
                            on
                        <?php endif; ?>
                        <?php echo $comment->created_at->format('Y-m-d H:i:s'); ?></small>
                    <?php if ($user_logged_in && (isAdmin() || $comment->user_id === $_SESSION['user_id'] || (isModerator() && $comment->user_id !== $_SESSION['user_id']) && !isAdmin($comment->user_id))): ?>
                        <a href="/comments/edit.php?id=<?php echo $comment->id; ?>&topic_id=<?php echo $topic_id; ?>"
                            class="btn btn-sm btn-primary">Edit</a>
                        <?php if (isAdmin() || $comment->user_id === $_SESSION['user_id']): ?>
                            <a href="/comments/delete.php?id=<?php echo $comment->id; ?>&topic_id=<?php echo $topic_id; ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this comment?');">Delete</a>
                        <?php endif; ?>
                    <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No comments yet.</p>
    <?php endif; ?>
    <hr>
    <h4>Add a Comment</h4>
    <?php if (isset($_SESSION['username'])): ?>
        <form method="post" action="add_comment.php?topic_id=<?php echo $topic_id; ?>">
            <div class="form-group">
                <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Comment</button>
        </form>
    <?php else: ?>
        <form method="post" action="add_comment.php?topic_id=<?php echo $topic_id; ?>">
            <div class="form-group">
                <label for="nickname">Nickname</label>
                <input type="text" class="form-control" id="nickname" name="nickname" required>
            </div>
            <div class="form-group">
                <label for="content">Comment</label>
                <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Comment</button>
        </form>
    <?php endif; ?>
</div>

<?php
include TEMPLATES_PATH . '/footer.php';
?>
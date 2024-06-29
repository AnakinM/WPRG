CREATE DATABASE IF NOT EXISTS forumdb;
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%';
FLUSH PRIVILEGES;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('administrator', 'moderator', 'user') DEFAULT 'user',
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (section_id) REFERENCES sections(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id INT,
    user_id INT NULL,
    nickname VARCHAR(50) NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (topic_id) REFERENCES topics(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE user_profiles (
    user_id INT PRIMARY KEY,
    full_name VARCHAR(100),
    bio TEXT,
    profile_picture VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

ALTER TABLE topics ADD CONSTRAINT fk_section
    FOREIGN KEY (section_id) REFERENCES sections(id)
    ON DELETE CASCADE;

ALTER TABLE comments ADD CONSTRAINT fk_topic
    FOREIGN KEY (topic_id) REFERENCES topics(id)
    ON DELETE CASCADE;

-- Creating Sections
INSERT INTO sections (name, description, created_at) VALUES
('Game Engines', 'Discuss and learn about various game engines, including Unity, Unreal Engine, Godot, and more. Share tips, tutorials, and troubleshoot issues related to game development engines.', NOW()),
('Game Design', 'Explore the principles of game design, including mechanics, storyboarding, level design, and user experience. Share ideas, get feedback, and discuss the latest trends in game design.', NOW()),
('Programming and Scripting', 'Dive into the technical side of game development. Discuss programming languages, scripting, algorithms, and optimization techniques. Share code snippets and solve programming challenges together.', NOW()),
('Art and Animation', 'Showcase your game art, animations, and visual effects. Get feedback on your work, discuss techniques, and share resources for creating stunning visuals in your games.', NOW());

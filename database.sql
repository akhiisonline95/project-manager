DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`
(
    `id`       int NOT NULL AUTO_INCREMENT,
    `username` varchar(40)  DEFAULT NULL,
    `password` varchar(255) DEFAULT NULL,
    `role`     enum('admin','member') NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id`, `username`, `password`, `role`)
VALUES (1, 'admin', '$2y$10$sZrf45tlFSjzJyxa39S9e.SJXjYXvoHENZE8mz0FTieHtUz2MdxJa', 'admin'),
       (2, 'member1', '$2y$10$l/XvZ8CPmnC84mNo94pAeuHXWiSgASOQC.P/qm00sjowEVevoxkly', 'member');

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `title`       varchar(100) NOT NULL,
    `description` text,
    `created_by`  int DEFAULT NULL,
    `created_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY           `created_by` (`created_by`),
    CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `project_id`  int          NOT NULL,
    `assigned_to` int          NOT NULL,
    `title`       varchar(100) NOT NULL,
    `description` text,
    `due_date`    date DEFAULT NULL,
    `priority`    enum('low','medium','high') DEFAULT 'medium',
    `status`      enum('todo','in_progress','done') DEFAULT 'todo',
    `created_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY           `project_id` (`project_id`),
    KEY           `assigned_to` (`assigned_to`),
    CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
    CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

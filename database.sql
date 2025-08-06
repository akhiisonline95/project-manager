CREATE TABLE `users`
(
    `id`       int NOT NULL AUTO_INCREMENT,
    `username` varchar(40)  DEFAULT NULL,
    `password` varchar(255) DEFAULT NULL,
    `role`     enum('admin','member') NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users`
VALUES (1, 'admin', '$2y$10$sZrf45tlFSjzJyxa39S9e.SJXjYXvoHENZE8mz0FTieHtUz2MdxJa', 'admin'),
       (2, 'member1', '$2y$10$l/XvZ8CPmnC84mNo94pAeuHXWiSgASOQC.P/qm00sjowEVevoxkly', 'member');

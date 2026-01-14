CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE todo_app;

DROP TABLE IF EXISTS tasks;

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  priority ENUM('high','medium','low') NOT NULL DEFAULT 'medium',
  due_date DATE NULL,
  is_done TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_done (is_done),
  INDEX idx_priority (priority),
  INDEX idx_due (due_date)
);

-- demo
INSERT INTO tasks(title, priority, due_date, is_done) VALUES
('Comprar proteína', 'high', DATE_ADD(CURDATE(), INTERVAL 2 DAY), 0),
('Lavar el carro', 'low', NULL, 0),
('Estudiar JavaScript', 'medium', DATE_ADD(CURDATE(), INTERVAL 1 DAY), 1);

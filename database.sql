CREATE DATABASE IF NOT EXISTS kjg_albachten
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE kjg_albachten;

-- =========================
-- Benutzer
-- =========================

CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(50) NOT NULL UNIQUE,
                       email VARCHAR(255) NOT NULL UNIQUE,
                       password_hash VARCHAR(255) NOT NULL,
                       role ENUM('admin','editor') DEFAULT 'editor',
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- Beiträge
-- =========================

CREATE TABLE posts (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       title VARCHAR(255) NOT NULL,
                       content LONGTEXT NOT NULL,
                       image_path VARCHAR(255),
                       author_id INT,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                       FOREIGN KEY (author_id)
                           REFERENCES users(id)
                           ON DELETE SET NULL
);

-- =========================
-- Termine
-- =========================

CREATE TABLE events (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        title VARCHAR(255) NOT NULL,
                        description TEXT,
                        start_date DATETIME NOT NULL,
                        end_date DATETIME NOT NULL,

                        registration_enabled BOOLEAN DEFAULT FALSE,
                        max_participants INT DEFAULT NULL,
                        registration_deadline DATETIME DEFAULT NULL,

                        created_by INT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                        FOREIGN KEY (created_by)
                            REFERENCES users(id)
                            ON DELETE SET NULL
);

-- =========================
-- Anmeldungen
-- =========================

CREATE TABLE event_registrations (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     event_id INT NOT NULL,

                                     first_name VARCHAR(100) NOT NULL,
                                     last_name VARCHAR(100) NOT NULL,
                                     email VARCHAR(255) NOT NULL,

                                     registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                                     FOREIGN KEY (event_id)
                                         REFERENCES events(id)
                                         ON DELETE CASCADE
);

-- =========================
-- Kontaktformular
-- =========================

CREATE TABLE contact_messages (
                                  id INT AUTO_INCREMENT PRIMARY KEY,

                                  name VARCHAR(255) NOT NULL,
                                  email VARCHAR(255) NOT NULL,

                                  subject VARCHAR(255) NOT NULL,
                                  message TEXT NOT NULL,

                                  sent_successfully BOOLEAN DEFAULT FALSE,

                                  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- Einstellungen
-- =========================

CREATE TABLE settings (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          setting_key VARCHAR(100) UNIQUE,
                          setting_value TEXT
);

INSERT INTO settings(setting_key, setting_value)
VALUES
    ('site_name','KJG Albachten'),
    ('primary_color','#00b7b3'),
    ('secondary_color','#008c89');
-- Créer la table des conférences
CREATE TABLE conference (
                            id SERIAL PRIMARY KEY,
                            city VARCHAR(255) NOT NULL,
                            year INT NOT NULL,
                            is_international BOOLEAN NOT NULL
);

-- Créer la table des commentaires
CREATE TABLE comment (
                         id SERIAL PRIMARY KEY,
                         author VARCHAR(255) NOT NULL,
                         email VARCHAR(255) NOT NULL,
                         photo_filename VARCHAR(255),
                         text TEXT NOT NULL,
                         created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                         conference_id INT NOT NULL,
                         FOREIGN KEY (conference_id) REFERENCES conference (id) ON DELETE CASCADE
);

-- Créer la table des admins
CREATE TABLE Admin (
                         id SERIAL PRIMARY KEY,
                         username character varying(180) NOT NULL,
                         roles json NOT NULL,
                         password VARCHAR(255) NOT NULL);

-- Insérer des données d'exemple dans la table des conférences
INSERT INTO conference (city, year, is_international) VALUES
                                                          ('Paris', 2023, TRUE),
                                                          ('New York', 2022, FALSE),
                                                          ('Tokyo', 2021, TRUE);

-- Insérer des données d'exemple dans la table des commentaires
INSERT INTO comment (author, email, photo_filename, text, conference_id) VALUES
                                                       ('John Doe', 'john.doe@example.com', 'teqezfqf' , 'test text for text column', 1),
                                                       ('Jane Smith', 'jane.smith@example.com', 'teqezfqf' , 'test text for text column',2),
                                                       ('Alice Johnson', 'alice.johnson@example.com','teqezfqf' , 'test text for text column',3);

-- Insérer des données d'exemple dans la table des admins
INSERT INTO Admin (id, username, roles, password) VALUES
                                                         (1, 'admin', '["ROLE_ADMIN"]', '$2y$10$1Q7'),
                                                         (2, 'user', '["ROLE_USER"]', '$2y$10$1Q7');
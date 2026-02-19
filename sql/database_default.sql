-- =============================================
-- Database Schema for TED Backoffice
-- Created: 2026-01-31
-- =============================================

-- Drop tables if they exist (in reverse order of dependencies)
DROP TABLE IF EXISTS candidate;
DROP TABLE IF EXISTS experience;
DROP TABLE IF EXISTS formation;
DROP TABLE IF EXISTS mission;
DROP TABLE IF EXISTS knowledge;
DROP TABLE IF EXISTS language;
DROP TABLE IF EXISTS skill;
DROP TABLE IF EXISTS personality;
DROP TABLE IF EXISTS social;
DROP TABLE IF EXISTS demand;
DROP TABLE IF EXISTS identity;
DROP TABLE IF EXISTS degree;
DROP TABLE IF EXISTS language_level;
DROP TABLE IF EXISTS hobbie;

-- =============================================
-- Table: degree
-- Description: Stores degree/education level information
-- =============================================
CREATE TABLE degree (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: language_level
-- Description: Stores language proficiency levels
-- =============================================
CREATE TABLE language_level (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(50) NOT NULL COMMENT 'A1, A2, B1, B2, C1, C2, Native',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: identity
-- Description: Stores candidate identity information
-- =============================================
CREATE TABLE identity (
    id INT PRIMARY KEY AUTO_INCREMENT,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    birthdate DATE,
    degree_id INT,
    job_title VARCHAR(200),
    phone VARCHAR(20),
    location VARCHAR(255),
    email VARCHAR(255),
    vehicule BOOLEAN DEFAULT FALSE,
    description TEXT,
    language_ids TEXT COMMENT 'JSON array of language IDs',
    hobbie_ids TEXT COMMENT 'JSON array of hobbie IDs',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    CONSTRAINT fk_identity_degree FOREIGN KEY (degree_id) REFERENCES degree(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: demand
-- Description: Stores candidate job demands/requirements
-- =============================================
CREATE TABLE demand (
    id INT PRIMARY KEY AUTO_INCREMENT,
    contract_type VARCHAR(50) COMMENT 'CDI, CDD, Freelance, etc.',
    salary DECIMAL(10, 2) DEFAULT 0,
    work_condition TEXT COMMENT 'Remote, Hybrid, On-site, etc.',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: social
-- Description: Stores candidate social media links
-- =============================================
CREATE TABLE social (
    id INT PRIMARY KEY AUTO_INCREMENT,
    linkedin VARCHAR(500),
    twitter VARCHAR(500),
    github VARCHAR(500),
    website VARCHAR(500),
    instagram VARCHAR(500),
    behance VARCHAR(500),
    facebook VARCHAR(500),
    other TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: skill
-- Description: Stores candidate skills
-- =============================================
CREATE TABLE skill (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    year VARCHAR(50),
    favorite BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: personality
-- Description: Stores candidate personality traits
-- =============================================
CREATE TABLE personality (
    id INT PRIMARY KEY AUTO_INCREMENT,
    trait VARCHAR(200) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: hobbie
-- Description: Stores candidate hobbies
-- =============================================
CREATE TABLE hobbie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: language
-- Description: Stores language proficiency information
-- =============================================
CREATE TABLE language (
    id INT PRIMARY KEY AUTO_INCREMENT,
    language_name VARCHAR(100) NOT NULL,
    level_id INT,
    is_native BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    CONSTRAINT fk_language_level FOREIGN KEY (level_id) REFERENCES language_level(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: mission
-- Description: Stores mission descriptions
-- =============================================
CREATE TABLE mission (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: knowledge
-- Description: Stores knowledge items for formations
-- =============================================
CREATE TABLE knowledge (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: experience
-- Description: Stores candidate work experiences
-- =============================================
CREATE TABLE experience (
    id INT PRIMARY KEY AUTO_INCREMENT,
    start_date DATE NOT NULL,
    end_date DATE,
    current BOOLEAN DEFAULT FALSE,
    position VARCHAR(200) NOT NULL,
    company VARCHAR(200),
    location VARCHAR(200),
    missions TEXT COMMENT 'JSON array of mission IDs',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: formation
-- Description: Stores candidate education/training
-- =============================================
CREATE TABLE formation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    start_date DATE NOT NULL,
    end_date DATE,
    current BOOLEAN DEFAULT FALSE,
    title VARCHAR(300) NOT NULL,
    school VARCHAR(200),
    location VARCHAR(200),
    knowledge TEXT COMMENT 'JSON array of knowledge IDs',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: candidate
-- Description: Main candidate table with references to all sub-entities
-- =============================================
CREATE TABLE candidate (
    id INT PRIMARY KEY AUTO_INCREMENT,
    identity_id INT,
    skill_ids TEXT COMMENT 'JSON array of skill IDs',
    personality_ids TEXT COMMENT 'JSON array of personality IDs',
    social_id INT,
    demand_id INT,
    experience_ids TEXT COMMENT 'JSON array of experience IDs',
    formation_ids TEXT COMMENT 'JSON array of formation IDs',
    mission_ids TEXT COMMENT 'JSON array of mission IDs',
    media_code VARCHAR(12) UNIQUE NOT NULL COMMENT 'Unique code for secure media access (photos, QR codes)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    CONSTRAINT fk_candidate_identity FOREIGN KEY (identity_id) REFERENCES identity(id) ON DELETE SET NULL,
    CONSTRAINT fk_candidate_social FOREIGN KEY (social_id) REFERENCES social(id) ON DELETE SET NULL,
    CONSTRAINT fk_candidate_demand FOREIGN KEY (demand_id) REFERENCES demand(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Indexes for performance optimization
-- =============================================
CREATE INDEX idx_identity_email ON identity(email);
CREATE INDEX idx_identity_name ON identity(last_name, first_name);
CREATE INDEX idx_candidate_identity ON candidate(identity_id);
CREATE INDEX idx_candidate_media_code ON candidate(media_code);
CREATE INDEX idx_experience_dates ON experience(start_date, end_date);
CREATE INDEX idx_formation_dates ON formation(start_date, end_date);

-- =============================================
-- Sample data (optional)
-- =============================================
-- Insert sample degrees
INSERT INTO degree (title) VALUES
('Bac'),
('Bac+2'),
('Bac+3 (Licence)'),
('Bac+5 (Master)'),
('Bac+8 (Doctorat)'),
('Master 1');

-- Insert sample language levels
INSERT INTO language_level (title) VALUES
('A1'),
('A2'),
('B1'),
('B2'),
('C1'),
('C2'),
('Native');

-- Insert a sample identity
INSERT INTO identity (last_name, first_name, birthdate, degree_id, job_title, phone, location, email, vehicule, description, language_ids, hobbie_ids) VALUES
('Konda', 'Eudes', '1992-06-09', 6, 'Développeur Full Stack', '+33 6 12 34 56 78', 'Mulhouse', 'jean.dupont@example.com', FALSE, 'Développeur passionné avec 5 ans d\'expérience', '[1, 2]', '[1, 2]');

-- Insert sample demand
INSERT INTO demand (contract_type, salary, work_condition) VALUES
('CDI', 45000.00, 'Hybrid - 2 jours en remote');

-- Insert sample social
INSERT INTO social (linkedin, github, website) VALUES
('https://linkedin.com/in/jeandupont', 'https://github.com/jeandupont', 'https://jeandupont.dev');

-- Insert sample skill
INSERT INTO skill (title, year, favorite) VALUES
('PHP', '5 ans', TRUE),
('JavaScript', '3 ans', FALSE),
('MySQL', '4 ans', FALSE);

-- Insert sample personality
INSERT INTO personality (trait) VALUES
('Autonome'),
('Créatif'),
('Équipe');

-- Insert sample language
INSERT INTO language (language_name, level_id, is_native) VALUES
('Français', 7, TRUE),
('Anglais', 5, FALSE);

-- Insert sample hobbie
INSERT INTO hobbie (title) VALUES
('Football'),
('Lecture'),
('Photographie');

-- Insert sample missions
INSERT INTO mission (title) VALUES
('Développement d\'applications web'),
('Maintenance du code existant'),
('Formation des juniors');

-- Insert sample knowledge
INSERT INTO knowledge (title) VALUES
('Programmation orientée objet'),
('Bases de données'),
('Algorithmique');

-- Insert sample experience
INSERT INTO experience (start_date, end_date, current, position, company, location, missions) VALUES
('2020-01-01', '2023-12-31', FALSE, 'Développeur Full Stack', 'Aienova', 'Mulhouse', '[1, 2, 3]');

-- Insert sample formation
INSERT INTO formation (start_date, end_date, current, title, school, location, knowledge) VALUES
('2015-09-01', '2018-06-30', FALSE, 'Licence Informatique', 'EEMI', 'Paris', '[1, 2, 3]');

-- Insert sample candidate
INSERT INTO candidate (identity_id, skill_ids, personality_ids, social_id, demand_id, experience_ids, formation_ids, mission_ids, media_code) VALUES
(1, '[1, 2, 3]', '[1, 2, 3]', 1, 1, '[1]', '[1]', '[]', '8w2H1q0U5Z3m');

-- =============================================
-- END OF SCHEMA
-- =============================================

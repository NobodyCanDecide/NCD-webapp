-- ============================================================
-- Nobody Can Decide - IT490 Movie App
-- Database schema (MySQL 8)
--
-- Rebuild/apply with (run as root, since the app user
-- cannot create tables):
--   sudo mysql < ~/IT490-Project/schema.sql
--
-- Safe to re-run: uses IF NOT EXISTS, so it will not
-- delete or overwrite existing tables or data.
-- ============================================================

CREATE DATABASE IF NOT EXISTS moviedb;
USE moviedb;

-- ------------------------------------------------------------
-- App user (created manually, NOT stored here so the password
-- stays out of this file / the repo). For reference:
--
--   CREATE USER 'moviedb_app'@'localhost' IDENTIFIED BY '<password>';
--   GRANT SELECT, INSERT, UPDATE, DELETE ON moviedb.* TO 'moviedb_app'@'localhost';
--
-- 'localhost' only = no remote logins. No CREATE/DROP/ALTER
-- rights = least privilege.
-- ------------------------------------------------------------

-- ------------------------------------------------------------
-- users: one row per account
--   password_hash holds the output of PHP password_hash()
--   (bcrypt) - never the plain-text password.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(50)  NOT NULL UNIQUE,
  email         VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- sessions: one row per logged-in session
--   token = bin2hex(random_bytes(32)) -> 64 hex chars
--   Deleting a user automatically deletes their sessions.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sessions (
  token      CHAR(64) PRIMARY KEY,
  user_id    INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  expires_at DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Coming later (after auth works): movies, services,
-- user_services, movie_providers, watchlist, groups,
-- group_members, movie_nights, ratings, notifications
-- ------------------------------------------------------------

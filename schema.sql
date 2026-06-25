CREATE TABLE IF NOT EXISTS users (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    hr_id       TEXT    NOT NULL UNIQUE,
    first_name  TEXT    NOT NULL,
    last_name   TEXT    NOT NULL,
    email       TEXT    NOT NULL,
    department  TEXT    NOT NULL DEFAULT '',
    is_active   INTEGER NOT NULL DEFAULT 1
);

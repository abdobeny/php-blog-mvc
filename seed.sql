-- ===========================================================================
--  PHP Blog — sample data
--  Import AFTER database.sql, into an EMPTY database:
--
--      mysql -u USER -p DB_NAME < seed.sql
--
--  Demo accounts (passwords are bcrypt-hashed):
--      admin / admin123   (administrator)
--      user  / user123    (author)
-- ===========================================================================

INSERT INTO users (username, email, password, role) VALUES
  ('admin', 'admin@example.com', '$2y$12$uvNLvljvhim3iTlV5CSsP.BBRM3gURo7rL4qMOOXRng/cO9sZcBJ6', 'admin'),
  ('user',  'user@example.com',  '$2y$12$vCBwCUFZOSwaQMd7m.2alusimfgTHXhRkVKXbxepnY5vaWIXxs8xW', 'user');

INSERT INTO categories (name) VALUES
  ('Technology'),
  ('Tutorials'),
  ('Security'),
  ('Web Development');

INSERT INTO articles (title, content, user_id) VALUES
  ('Understanding PHP MVC Architecture',
   'This post delves into the core concepts of Model-View-Controller (MVC) architecture in PHP applications. We cover how it separates concerns for better maintainability and scalability, and why a central router makes URLs clean and friendly.',
   1),
  ('My First Blog Entry',
   'Hello everyone! This is my very first blog post. I am excited to share my thoughts and experiences here, and to start writing about the things I learn along the way.',
   2),
  ('Tips for Secure PHP Development',
   'Security is paramount in web development. This article outlines essential tips and best practices for writing secure PHP code: prepared statements against SQL injection, output escaping against XSS, and bcrypt for password storage.',
   1);

INSERT INTO article_categories (article_id, category_id) VALUES
  (1, 1), (1, 4),
  (2, 2),
  (3, 3), (3, 1);

INSERT INTO comments (content, user_id, article_id) VALUES
  ('Excellent overview of MVC! Very helpful for beginners.', 2, 1),
  ('Glad you found it useful!', 1, 1);

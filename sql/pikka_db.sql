-- =====================================================================
-- Pikka Creatives — Home Page CMS Database
-- Import this file into your cPanel MySQL database (phpMyAdmin > Import)
-- =====================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- Table: admin_users  (login for the admin panel)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(60) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `display_name` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default login -> username: admin   password: pikka123
-- (Change this from the admin panel after first login.)
INSERT INTO `admin_users` (`username`, `password_hash`, `display_name`) VALUES
('admin', '$2y$10$2If/XJP8gGvr70mfrywmVuXIpXmmUB18cMk1TdxUSLgjKPrqKsk9W', 'Site Admin');

-- ---------------------------------------------------------------------
-- Table: site_settings  (single-value key/value pairs: logo, colours...)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(80) NOT NULL,
  `setting_value` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'Pikka Creatives'),
('logo_text', 'Pikka'),
('accent_color', '#F1592A'),
('phone', '+64 000 000 000'),
('email', 'hello@pikkacreatives.co.nz'),
('marquee_items', 'Brand & Identity | Web Design | SEO & Marketing | Social & Content | Packaging & Print | Creative Direction'),
('footer_text', 'Creative & digital studio helping New Zealand businesses look sharp, sound clear, and stand out.'),
('maintenance_mode', '0'),
('maintenance_message', "We're currently making some improvements. Please check back soon.");

-- ---------------------------------------------------------------------
-- Table: page_content  (all editable text blocks, keyed by section)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `page_content` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `content_key` VARCHAR(100) NOT NULL,
  `content_value` TEXT DEFAULT NULL,
  `section` VARCHAR(60) DEFAULT NULL,
  `label` VARCHAR(160) DEFAULT NULL,
  `field_type` VARCHAR(20) DEFAULT 'text',
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_key` (`content_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `page_content` (`content_key`, `content_value`, `section`, `label`, `field_type`) VALUES
-- Hero
('hero_eyebrow', 'Creative and Digital Solutions · New Zealand', 'Hero', 'Eyebrow', 'text'),
('hero_headline', 'Your vision, brought to life.', 'Hero', 'Headline', 'text'),
('hero_subheadline', 'Pikka Creatives helps businesses from Kaitaia to Bluff grow through smart design, high-performing websites and digital marketing, backed by local support.', 'Hero', 'Sub-headline', 'textarea'),
('hero_btn_primary', 'See our work', 'Hero', 'Primary button', 'text'),
('hero_btn_secondary', 'Start a project', 'Hero', 'Secondary button', 'text'),
('hero_quote', 'Pikka Creative understood our brand from day one and gave it a look that feels premium but still genuinely Kiwi.', 'Hero', 'Floating quote', 'textarea'),
('hero_reviews_stat', '5.0 rating', 'Hero', 'Reviews stat', 'text'),
('hero_reviews_label', 'From valued NZ clients', 'Hero', 'Reviews label', 'text'),
-- Intro
('intro_eyebrow', 'Kia ora', 'Intro', 'Eyebrow', 'text'),
('intro_heading', 'Big ideas for New Zealand brands ready to grow.', 'Intro', 'Heading', 'text'),
('intro_body', 'Every business begins with an idea, a purpose and a story worth sharing. We help people see it, feel it and remember it. Pikka Creatives combines local support with experienced creative talent to bring your story to life through branding, websites, digital marketing and content. We listen, keep things simple and create work that feels unmistakably yours, wherever you are in New Zealand.', 'Intro', 'Body', 'textarea'),
-- Services
('services_eyebrow', 'What we do', 'Services', 'Eyebrow', 'text'),
('services_heading', 'Creative services for New Zealand businesses.', 'Services', 'Heading', 'text'),
('services_intro', 'Everything you need to build a brand people remember, all in one place.', 'Services', 'Intro line', 'textarea'),
-- Why Choose Us
('why_eyebrow', 'Why Pikka Creative', 'Why', 'Eyebrow', 'text'),
('why_heading', 'Local understanding. Creative expertise.', 'Why', 'Heading', 'text'),
('why_body', 'Great creative starts with understanding your business, your customers and the market you serve. Pikka Creatives gives you a trusted New Zealand point of contact, backed by experienced creative and digital talent. The result is work that feels relevant locally and stands confidently alongside leading brands anywhere.', 'Why', 'Body', 'textarea'),
-- Process
('process_eyebrow', 'How we work', 'Process', 'Eyebrow', 'text'),
('process_heading', 'A simple process, built around you.', 'Process', 'Heading', 'text'),
('process_intro', 'From first chat to final delivery, we keep it clear and collaborative.', 'Process', 'Intro line', 'text'),
-- Industries
('industries_eyebrow', 'Who we help', 'Industries', 'Eyebrow', 'text'),
('industries_heading', 'Creative for every corner of Aotearoa.', 'Industries', 'Heading', 'text'),
('industries_body', 'From hospitality to tourism, retail to trades, we help businesses across New Zealand build brands that stand out. No industry is too big or too small — if you have got a story worth telling, we would love to help you tell it.', 'Industries', 'Body', 'textarea'),
('industries_tags', 'Hospitality & Cafés|Tourism & Hospitality|Retail & E-commerce|Food & Beverage|Health & Wellness|Trades & Construction|Startups & Tech|Professional Services', 'Industries', 'Tags (separate with | )', 'textarea'),
-- Stats
('stats_heading', 'Creative you can count on.', 'Stats', 'Heading', 'text'),
-- Testimonial
('testi_eyebrow', 'What our clients say', 'Testimonial', 'Eyebrow', 'text'),
('testi_quote', 'Pikka Creative understood our brand from day one. They gave us a look that feels premium but still genuinely Kiwi — and our customers have noticed.', 'Testimonial', 'Quote', 'textarea'),
('testi_attribution', 'Add client name, Business name', 'Testimonial', 'Attribution', 'text'),
-- CTA
('cta_eyebrow', 'Let''s work together', 'CTA', 'Eyebrow', 'text'),
('cta_heading', 'Ready to bring your vision to life?', 'CTA', 'Heading', 'text'),
('cta_body', 'Whether you have got a clear brief or just a spark of an idea, we would love to hear from you. Let us create branding, websites, and content that help your New Zealand business stand out.', 'CTA', 'Body', 'textarea'),
('cta_btn_primary', 'Start your project', 'CTA', 'Primary button', 'text'),
('cta_btn_secondary', 'View our portfolio', 'CTA', 'Secondary button', 'text');

-- ---------------------------------------------------------------------
-- Table: hero_slides  (rotating hero banner — text + button + image per slide)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `hero_slides` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `eyebrow` VARCHAR(160) DEFAULT NULL,
  `headline` VARCHAR(255) DEFAULT NULL,
  `subheadline` TEXT DEFAULT NULL,
  `btn_primary_text` VARCHAR(80) DEFAULT NULL,
  `btn_primary_link` VARCHAR(255) DEFAULT NULL,
  `btn_secondary_text` VARCHAR(80) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `hero_slides` (`eyebrow`, `headline`, `subheadline`, `btn_primary_text`, `btn_primary_link`, `btn_secondary_text`, `image`, `sort_order`) VALUES
('Creative and Digital Solutions · New Zealand', 'Your vision, brought to life.', 'Pikka Creatives helps businesses from Kaitaia to Bluff grow through smart design, high-performing websites and digital marketing, backed by local support.', 'See our work', '#services', 'Start a project', '', 1);

-- ---------------------------------------------------------------------
-- Table: team_members  (About Us — Meet the Team section)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `team_members` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) DEFAULT NULL,
  `role` VARCHAR(120) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `team_members` (`name`, `role`, `bio`, `photo`, `sort_order`) VALUES
('[Add name]', 'Founder & Creative Director', 'Add a short 1–2 line bio — background, what they do, a personal touch.', '', 1),
('[Add name]', 'Designer', 'Add a short 1–2 line bio — background, what they do, a personal touch.', '', 2),
('[Add name]', 'Web Developer', 'Add a short 1–2 line bio — background, what they do, a personal touch.', '', 3),
('[Add name]', 'Content & Social', 'Add a short 1–2 line bio — background, what they do, a personal touch.', '', 4);

-- ---------------------------------------------------------------------
-- Table: services  (Section 3 cards / accordion)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(120) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `sort_order` INT(11) DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `services` (`title`, `description`, `sort_order`) VALUES
('Brand & Identity', 'Logos, colour palettes and visual systems that give your business a face customers recognise and trust. We build brand identities that work everywhere, from your shopfront to your social media.', 1),
('Web Design & Development', 'Fast, mobile-friendly websites designed to look great and convert visitors into customers. Every site we build is made for the New Zealand market and optimised to be found on Google.', 2),
('SEO & Digital Marketing', 'Practical digital marketing that helps the right people find your business. From search optimisation and Google Ads to targeted campaigns, we focus on visibility that supports real growth.', 3),
('Social Media & Content', 'Scroll-stopping content and social media design that keeps your brand active and engaging. We help your business show up consistently and speak in a voice that feels local.', 4),
('Packaging & Print', 'Packaging, labels, and print design made to stand out on the shelf. From food producers to retail brands, we make products people want to pick up.', 5),
('Strategy & Creative Direction', 'Campaign thinking and creative direction that helps you reach the right audience at the right time — grounded in a real understanding of the New Zealand market.', 6);

-- ---------------------------------------------------------------------
-- Table: why_reasons  (Section 4 grid)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `why_reasons` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(120) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `sort_order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `why_reasons` (`title`, `description`, `sort_order`) VALUES
('Made for the NZ market', 'Every project is shaped around your business, your audience and how you want to be recognised in New Zealand.', 1),
('One local point of contact', 'You have one dedicated contact who listens, keeps communication clear and manages your project from the first conversation to final delivery.', 2),
('Honest pricing, no surprises', 'Clear quotes and fair pricing from the start, so you understand what is included and what you are paying for.', 3),
('Built to grow with you', 'We create flexible brands, websites and digital solutions that can evolve as your business grows.', 4);

-- ---------------------------------------------------------------------
-- Table: process_steps  (Section 5)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `process_steps` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `step_number` VARCHAR(6) DEFAULT NULL,
  `title` VARCHAR(120) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `sort_order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `process_steps` (`step_number`, `title`, `description`, `sort_order`) VALUES
('01', 'Discover', 'We get to know your business, your goals and the people you want to reach. Every great project begins with understanding.', 1),
('02', 'Create', 'We turn your ideas into something real, exploring creative directions and shaping your brand, website or campaign.', 2),
('03', 'Refine', 'We listen to your feedback and carefully refine the work until the final direction feels right.', 3),
('04', 'Deliver', 'We provide polished, ready-to-use files and solutions, along with the support you need to put them to work.', 4);

-- ---------------------------------------------------------------------
-- Table: stats  (Section 7 trust bar)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `stats` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(120) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `sort_order` INT(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `stats` (`title`, `description`, `sort_order`) VALUES
('100% New Zealand-based', 'Locally owned and operated, working with clients nationwide.', 1),
('Clear communication', 'One dedicated contact managing your project from the first conversation to final delivery.', 2),
('End-to-end creative', 'Branding, web, social, and print, all under one roof.', 3),
('Made for your market', 'Every project is shaped around your business, your customers and the audience you want to reach.', 4);

-- ---------------------------------------------------------------------
-- Table: contact_messages  (submissions from the CTA / contact form)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) DEFAULT NULL,
  `email` VARCHAR(160) DEFAULT NULL,
  `message` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

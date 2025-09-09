-- Database structure for password management

-- Create managers table if it doesn't exist
CREATE TABLE IF NOT EXISTS `managers` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL UNIQUE,
    `password` varchar(255) NOT NULL,
    `position` varchar(50) DEFAULT 'Manager',
    `department` varchar(50) DEFAULT 'Operations',
    `employee_id` varchar(20) UNIQUE,
    `phone` varchar(20),
    `birth_date` date,
    `start_date` date,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `password_changed_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `is_active` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`id`),
    INDEX `idx_email` (`email`),
    INDEX `idx_employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create password change log table
CREATE TABLE IF NOT EXISTS `password_change_log` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `manager_id` int(11) NOT NULL,
    `changed_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `ip_address` varchar(45),
    `user_agent` text,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`manager_id`) REFERENCES `managers`(`id`) ON DELETE CASCADE,
    INDEX `idx_manager_id` (`manager_id`),
    INDEX `idx_changed_at` (`changed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert a sample manager (password: Manager123!)
INSERT INTO `managers` (
    `name`, 
    `email`, 
    `password`, 
    `position`, 
    `department`, 
    `employee_id`, 
    `phone`, 
    `birth_date`, 
    `start_date`,
    `password_changed_at`
) VALUES (
    'Duvini Weerasinghe',
    'duvini@beautyhub.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Manager123!
    'Store Manager',
    'Operations',
    'BH2022001',
    '+1 (555) 123-4567',
    '1995-06-15',
    '2022-03-01',
    NOW()
) ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `position` = VALUES(`position`),
    `department` = VALUES(`department`);

-- Update existing managers table to add password-related columns if they don't exist
ALTER TABLE `managers` 
ADD COLUMN IF NOT EXISTS `password_changed_at` timestamp DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) DEFAULT 1;

-- Create index for password expiry checks
CREATE INDEX IF NOT EXISTS `idx_password_changed_at` ON `managers`(`password_changed_at`);

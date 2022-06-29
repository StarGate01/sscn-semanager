<?php

class DB
{
    
    const QUERY_CREATE_REGISTRATIONS = <<<EOD
CREATE TABLE IF NOT EXISTS `registrations` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `creation` datetime NOT NULL DEFAULT current_timestamp(),
    `firstname` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `lastname` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `city` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `birth` date NOT NULL,
    `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `phone` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `newsletter` tinyint(1) NOT NULL,
    `signature` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
EOD;

    const QUERY_CREATE_EVENTS = <<<EOD
CREATE TABLE IF NOT EXISTS `events` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `reg_id` int(11) NOT NULL,
    `creation` datetime NOT NULL DEFAULT current_timestamp(),
    `group` int(11) NOT NULL,
    `action` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`reg_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
EOD;

    const QUERY_CORRELATE = <<<EOD
SELECT * FROM
(
    (
        SELECT `id`, `e0_id`, CASE WHEN `e0_action` IS NULL THEN 0 ELSE `e0_action` END AS `e0_action_norm` FROM `registrations` AS r 
        LEFT JOIN
        (
            SELECT e.`id` as `e0_id`, e.`action` AS `e0_action`, e.`reg_id` FROM `events` e WHERE (`reg_id`, `creation`) IN
            (
                SELECT `reg_id`, MAX(`creation`) FROM `events` WHERE `group`=0 GROUP BY `reg_id`
            )
        ) t 
        ON r.`id` = t.`reg_id`
    ) e0
    INNER JOIN
    (
        SELECT `id`, `e1_id`, CASE WHEN `e1_action` IS NULL THEN 0 ELSE `e1_action` END AS `e1_action_norm` FROM `registrations` AS r 
        LEFT JOIN
        (
            SELECT e.`id` as `e1_id`, e.`action` AS `e1_action`, e.`reg_id` FROM `events` e WHERE (`reg_id`, `creation`) IN
            (
                SELECT `reg_id`, MAX(`creation`) FROM `events` WHERE `group`=1 GROUP BY `reg_id`
            )
        ) t
        ON r.`id` = t.`reg_id`
    ) e1
    ON e0.`id` = e1.`id`
)
INNER JOIN
`registrations` r
ON e0.`id` = r.`id` AND e1.`id` = r.`id`
EOD;

    public static function open()
    {
        $mysqli = new mysqli($_ENV["DB_HOST"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"], $_ENV["DB_DATABASE"], $_ENV["DB_PORT"]);
        if ($mysqli->connect_errno) return false;

        if ($mysqli->query(self::QUERY_CREATE_REGISTRATIONS) !== true) return false;
        if ($mysqli->query(self::QUERY_CREATE_EVENTS) !== true) return false;

        return $mysqli;
    }
    
    public static function close($db)
    {
        $db->close();
    }
    
}

?>
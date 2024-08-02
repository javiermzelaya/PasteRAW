-- Estructura de tabla para la tabla `ads_settings`
CREATE TABLE IF NOT EXISTS `ads_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_type` varchar(50) NOT NULL,
  `ad_code` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_ad_type` (`ad_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Insertar los nuevos tipos de anuncios si no existen
INSERT INTO `ads_settings` (`ad_type`, `ad_code`)
SELECT 'banner_top', ''
WHERE NOT EXISTS (SELECT 1 FROM `ads_settings` WHERE `ad_type` = 'banner_top');

INSERT INTO `ads_settings` (`ad_type`, `ad_code`)
SELECT 'banner_bottom', ''
WHERE NOT EXISTS (SELECT 1 FROM `ads_settings` WHERE `ad_type` = 'banner_bottom');

-- Estructura de tabla para la tabla `pastes`
CREATE TABLE IF NOT EXISTS `pastes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Estructura de tabla para la tabla `settings`
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `footer_legend` text NOT NULL,
  `logo_filename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Volcado de datos para la tabla `settings`
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `title`, `footer_legend`, `logo_filename`) VALUES
(1, 'site_title', '', 'PasteRAW', '', NULL),
(2, 'footer_legend', '', 'PasteRAW', '', NULL),
(3, '', '', 'PasteRAW', '', NULL);

-- Estructura de tabla para la tabla `users`
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL,
  `recovery_phrase` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- AUTO_INCREMENT de las tablas volcadas
-- AUTO_INCREMENT de la tabla `ads_settings`
ALTER TABLE `ads_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

-- AUTO_INCREMENT de la tabla `pastes`
ALTER TABLE `pastes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- AUTO_INCREMENT de la tabla `settings`
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

-- AUTO_INCREMENT de la tabla `users`
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

-- Restricciones para tablas volcadas
-- Filtros para la tabla `pastes`
ALTER TABLE `pastes`
  ADD CONSTRAINT `pastes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;
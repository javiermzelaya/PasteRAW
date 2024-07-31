-- Estructura de tabla para la tabla `ads_settings`
CREATE TABLE `ads_settings` (
  `id` int(11) NOT NULL,
  `ad_type` varchar(50) NOT NULL,
  `ad_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Volcado de datos para la tabla `ads_settings`
INSERT INTO `ads_settings` (`id`, `ad_type`, `ad_code`) VALUES
(1, 'banner', ''),
(2, 'skyscraper', ''),
(3, 'leaderboard', ''),
(4, 'rectangle', ''),
(5, 'mobile', '');

-- Estructura de tabla para la tabla `pastes`
CREATE TABLE `pastes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Estructura de tabla para la tabla `settings`
CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `footer_legend` text NOT NULL,
  `logo_filename` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Volcado de datos para la tabla `settings`
INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `title`, `footer_legend`, `logo_filename`) VALUES
(1, 'site_title', '', 'PASTEBIN', '', NULL),
(2, 'footer_legend', '', 'PASTEBIN', '', NULL),
(3, '', '', 'PASTEBIN', '', NULL);

-- Estructura de tabla para la tabla `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL,
  `recovery_phrase` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- Índices para tablas volcadas
-- Índices de la tabla `ads_settings`
ALTER TABLE `ads_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ad_type` (`ad_type`);

-- Índices de la tabla `pastes`
ALTER TABLE `pastes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

-- Índices de la tabla `settings`
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

-- Índices de la tabla `users`
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

-- AUTO_INCREMENT de las tablas volcadas
-- AUTO_INCREMENT de la tabla `ads_settings`
ALTER TABLE `ads_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

-- AUTO_INCREMENT de la tabla `pastes`
ALTER TABLE `pastes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
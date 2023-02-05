<?php
/*
 * This is part two of a two-part test. It must be in a higher-numbered file
 * than part one, to trigger the bug in
 * https://github.com/Automattic/VIP-Coding-Standards/issues/739
 */
printf(
	'<div class="%1$s"><p>%2$s</p></div>',
	esc_attr($class),
	wp_kses_post($message)
);

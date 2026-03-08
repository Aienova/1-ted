<?php

/**
 * Configuration Email / SMTP
 *
 * Variables supportées (local.env ou variables d'environnement système):
 *
 * MAIL_TRANSPORT=smtp
 * MAIL_SMTP_HOST=smtp.example.com
 * MAIL_SMTP_PORT=587
 * MAIL_SMTP_ENCRYPTION=tls   (tls|ssl|none)
 * MAIL_SMTP_TIMEOUT=15
 * MAIL_SMTP_AUTH=true
 * MAIL_SMTP_USERNAME=your_smtp_login
 * MAIL_SMTP_PASSWORD=your_smtp_password
 *
 * MAIL_CONTACT_ADDRESS=contact@1-ted.com
 * MAIL_CONTACT_NAME=1-TED Contact
 * MAIL_CONTACT_SMTP_USERNAME=...
 * MAIL_CONTACT_SMTP_PASSWORD=...
 *
 * MAIL_NOREPLY_ADDRESS=no-reply@1-ted.com
 * MAIL_NOREPLY_NAME=1-TED
 * MAIL_NOREPLY_SMTP_USERNAME=...
 * MAIL_NOREPLY_SMTP_PASSWORD=...
 *
 * MAIL_REDFLAG_ADDRESS=support@1-ted.com
 * MAIL_REDFLAG_NAME=1-TED Red Flag
 * MAIL_REDFLAG_SMTP_USERNAME=...
 * MAIL_REDFLAG_SMTP_PASSWORD=...
 */

function mailGetEnvMap() {
	static $cache = null;

	if ($cache !== null) {
		return $cache;
	}

	$cache = [];
	$envFile = __DIR__ . '/../local.env';

	if (file_exists($envFile)) {
		$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($lines as $line) {
			$line = trim($line);
			if ($line === '' || strpos($line, '#') === 0) {
				continue;
			}

			$parts = explode('=', $line, 2);
			if (count($parts) !== 2) {
				continue;
			}

			$key = trim($parts[0]);
			$value = trim($parts[1]);
			$value = trim($value, "\"'");

			$cache[$key] = $value;
		}
	}

	return $cache;
}

function mailGetEnvValue($key, $default = null) {
	$systemValue = getenv($key);
	if ($systemValue !== false && $systemValue !== '') {
		return $systemValue;
	}

	$envMap = mailGetEnvMap();
	if (array_key_exists($key, $envMap) && $envMap[$key] !== '') {
		return $envMap[$key];
	}

	return $default;
}

function mailToBool($value, $default = false) {
	if ($value === null) {
		return $default;
	}

	$normalized = strtolower(trim((string)$value));
	return in_array($normalized, ['1', 'true', 'yes', 'on'], true);
}

function getMailSenderProfiles() {
	$defaultUsername = mailGetEnvValue('MAIL_SMTP_USERNAME', '');
	$defaultPassword = mailGetEnvValue('MAIL_SMTP_PASSWORD', '');

	return [
		'contact@1-ted.com' => [
			'email' => mailGetEnvValue('MAIL_CONTACT_ADDRESS', 'contact@1-ted.com'),
			'name' => mailGetEnvValue('MAIL_CONTACT_NAME', '1-TED Contact'),
			'smtp_username' => mailGetEnvValue('MAIL_CONTACT_SMTP_USERNAME', $defaultUsername),
			'smtp_password' => mailGetEnvValue('MAIL_CONTACT_SMTP_PASSWORD', $defaultPassword),
		],
		'no-reply@1-ted.com' => [
			'email' => mailGetEnvValue('MAIL_NOREPLY_ADDRESS', 'no-reply@1-ted.com'),
			'name' => mailGetEnvValue('MAIL_NOREPLY_NAME', '1-TED'),
			'smtp_username' => mailGetEnvValue('MAIL_NOREPLY_SMTP_USERNAME', $defaultUsername),
			'smtp_password' => mailGetEnvValue('MAIL_NOREPLY_SMTP_PASSWORD', $defaultPassword),
		],
		'applications@1-ted.com' => [
			'email' => mailGetEnvValue('MAIL_APPLICATIONS_ADDRESS', 'applications@1-ted.com'),
			'name' => mailGetEnvValue('MAIL_APPLICATIONS_NAME', '1-TED Candidatures'),
			'smtp_username' => mailGetEnvValue('MAIL_APPLICATIONS_SMTP_USERNAME', $defaultUsername),
			'smtp_password' => mailGetEnvValue('MAIL_APPLICATIONS_SMTP_PASSWORD', $defaultPassword),
		],
		'support@1-ted.com' => [
			'email' => mailGetEnvValue('MAIL_SUPPORT_ADDRESS', 'support@1-ted.com'),
			'name' => mailGetEnvValue('MAIL_SUPPORT_NAME', '1-TED Support'),
			'smtp_username' => mailGetEnvValue('MAIL_SUPPORT_SMTP_USERNAME', $defaultUsername),
			'smtp_password' => mailGetEnvValue('MAIL_SUPPORT_SMTP_PASSWORD', $defaultPassword),
		],
		'redflag@1-ted.com' => [
			'email' => mailGetEnvValue('MAIL_REDFLAG_ADDRESS', 'redflag@1-ted.com'),
			'name' => mailGetEnvValue('MAIL_REDFLAG_NAME', '1-TED Red Flag'),
			'smtp_username' => mailGetEnvValue('MAIL_REDFLAG_SMTP_USERNAME', $defaultUsername),
			'smtp_password' => mailGetEnvValue('MAIL_REDFLAG_SMTP_PASSWORD', $defaultPassword),
		],
	];
}

function resolveMailSenderProfile($fromEmail, $fromName) {
	$profiles = getMailSenderProfiles();

	$normalizedFrom = strtolower(trim((string)$fromEmail));

	// Alias pour compatibilité avec l'ancien noreply sans tiret + faute redfalg
	if ($normalizedFrom === 'noreply@1-ted.com') {
		$normalizedFrom = 'no-reply@1-ted.com';
	}
	if ($normalizedFrom === 'redfalg@1-ted.com') {
		$normalizedFrom = 'support@1-ted.com';
	}

	if (isset($profiles[$normalizedFrom])) {
		$profile = $profiles[$normalizedFrom];
		if (!empty($fromName)) {
			$profile['name'] = $fromName;
		}
		return $profile;
	}

	// Fallback no-reply
	$fallback = $profiles['no-reply@1-ted.com'];
	if (!empty($fromName)) {
		$fallback['name'] = $fromName;
	}
	if (!empty($fromEmail) && filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
		$fallback['email'] = $fromEmail;
	}

	return $fallback;
}

function getResolvedMailConfig($fromEmail, $fromName, $replyToEmail = null, $replyToName = null) {
	$sender = resolveMailSenderProfile($fromEmail, $fromName);

	$encryption = strtolower((string)mailGetEnvValue('MAIL_SMTP_ENCRYPTION', 'tls'));
	if (!in_array($encryption, ['tls', 'ssl', 'none'], true)) {
		$encryption = 'tls';
	}

	$config = [
		'transport' => strtolower((string)mailGetEnvValue('MAIL_TRANSPORT', 'smtp')),
		'host' => mailGetEnvValue('MAIL_SMTP_HOST', ''),
		'port' => (int)mailGetEnvValue('MAIL_SMTP_PORT', 587),
		'encryption' => $encryption,
		'timeout' => (int)mailGetEnvValue('MAIL_SMTP_TIMEOUT', 15),
		'auth' => mailToBool(mailGetEnvValue('MAIL_SMTP_AUTH', 'true'), true),
		'username' => $sender['smtp_username'],
		'password' => $sender['smtp_password'],
		'from_email' => $sender['email'],
		'from_name' => $sender['name'],
		'reply_to_email' => $replyToEmail ?: $sender['email'],
		'reply_to_name' => $replyToName ?: $sender['name'],
	];

	return $config;
}

function isSmtpMailConfigReady($config) {
	if (($config['transport'] ?? 'smtp') !== 'smtp') {
		return false;
	}

	if (empty($config['host']) || empty($config['port'])) {
		return false;
	}

	if (!empty($config['auth'])) {
		return !empty($config['username']) && !empty($config['password']);
	}

	return true;
}


<?php

//TODO: remove E_NOTICE when const VirgilChunkCipher_kPreferredChunkSize will released.
error_reporting(E_ALL & ~E_NOTICE);

require __DIR__ . '/../vendor/autoload.php';

defined('VIRGIL_FIXTURE_PATH') or define('VIRGIL_FIXTURE_PATH', './tests/fixtures/');
defined('VIRGIL_TEST_CONFIG_PATH') or define('VIRGIL_TEST_CONFIG_PATH', './tests/config.json');

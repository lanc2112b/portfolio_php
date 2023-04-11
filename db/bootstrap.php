<?php

/** load global .env into PHPUnit tests */
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../', '.env.test')->load();
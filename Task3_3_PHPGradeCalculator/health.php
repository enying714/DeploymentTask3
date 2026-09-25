<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['status' => 'healthy', 'app' => 'GradePHP', 'version' => '1.0.0'], JSON_THROW_ON_ERROR);

<?php
// JSON API for local company users (agreed schema)
// Output:
// { "success": true, "company": "...", "total_users": N, "users": [...] }

declare(strict_types=1);

// --- TEMP: enable during debugging, remove for production ---
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Content type & no-cache for APIs
header('Content-Type: application/json');
header('Cache-Control: no-store');

// --- Token auth (query ?token=... OR header X-API-Token) ---
$REQUIRE_TOKEN  = true;
$EXPECTED_TOKEN = 'dreamspace_secret_2025';

function get_request_token(): string {
  // 1) query string
  if (isset($_GET['token'])) return (string)$_GET['token'];

  // 2) header (works even if getallheaders() is missing)
  $headerNames = [
    'HTTP_X_API_TOKEN',      // most PHP SAPIs
    'X_API_TOKEN',           // rare
    'REDIRECT_HTTP_X_API_TOKEN', // some proxies
  ];
  foreach ($headerNames as $h) {
    if (!empty($_SERVER[$h])) return (string)$_SERVER[$h];
  }

  // optional: try getallheaders if present
  if (function_exists('getallheaders')) {
    foreach (getallheaders() as $k => $v) {
      if (strtolower($k) === 'x-api-token') return (string)$v;
    }
  }
  return '';
}

if ($REQUIRE_TOKEN) {
  $provided = get_request_token();
  if (!hash_equals($EXPECTED_TOKEN, $provided)) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized'], JSON_UNESCAPED_SLASHES);
    exit;
  }
}

// --- Load users from flat file: /data/users.txt (Name | username | role) ---
$companyName = 'DreamSpace Realty';
$filePath    = __DIR__ . '/../data/users.txt';
$resultUsers = [];

if (is_readable($filePath)) {
  $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $id = 1;
  foreach ($lines as $line) {
    $parts = array_map('trim', explode('|', $line));
    // Expected format: Name | username | role
    if (count($parts) >= 2) {
      [$name, $username] = $parts;
      $email  = $username . '@dreamspace.local';
      $joined = date('Y-m-d', strtotime('-' . random_int(10, 900) . ' days'));
      $resultUsers[] = [
        'id'     => $id++,
        'name'   => $name,
        'email'  => $email,
        'joined' => $joined,
      ];
    }
  }
}

// Always return valid schema
echo json_encode([
  'success'     => true,
  'company'     => $companyName,
  'total_users' => count($resultUsers),
  'users'       => $resultUsers,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

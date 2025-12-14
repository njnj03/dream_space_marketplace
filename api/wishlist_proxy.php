<?php
/**
 * Wishlist Proxy - Forwards wishlist requests to Group Marketplace
 * 
 * This proxy exists because CORS headers aren't working properly on the marketplace server.
 * By making the request server-side, we bypass CORS restrictions entirely.
 */

// Set CORS headers for our own domain
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get parameters from request
$userId = $_GET['user_id'] ?? '';
$productId = $_GET['product_id'] ?? '';
$productName = $_GET['product_name'] ?? '';
$companyName = 'Dreamspace Realty'; // Fixed company name

// Validate required parameters
if (empty($userId) || empty($productId)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Missing required parameters (user_id, product_id)'
    ]);
    exit;
}

// Build marketplace API URL
$marketplaceUrl = 'https://cmpe272groupproject.infinityfree.me/api/add_to_wishlist_v2.php';
$queryParams = http_build_query([
    'token' => 'group_marketplace_secret_2025',
    'user_id' => $userId,
    'company' => $companyName,
    'product_id' => $productId,
    'product_name' => $productName
]);

$fullUrl = $marketplaceUrl . '?' . $queryParams;

// Make server-side request (bypasses CORS)
$ch = curl_init($fullUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Handle curl errors
if ($curlError) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to connect to marketplace: ' . $curlError
    ]);
    exit;
}

// Handle non-200 responses
if ($httpCode !== 200) {
    http_response_code($httpCode);
    echo json_encode([
        'success' => false,
        'error' => 'Marketplace returned error code: ' . $httpCode,
        'response' => $response
    ]);
    exit;
}

// Forward the marketplace response
echo $response;
?>

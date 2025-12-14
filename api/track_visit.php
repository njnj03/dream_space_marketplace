<?php
/**
 * Visit Tracking - Records product visits to Group Marketplace database
 * 
 * When marketplace users visit products on our site, this records the visit
 * directly to the marketplace database so it appears in their browsing history.
 */

// Start session to get session ID
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get parameters - support both GET and direct function call
$userId = $_GET['user_id'] ?? null;
$productId = $_GET['product_id'] ?? '';
$productName = $_GET['product_name'] ?? '';

// Company information
$companyName = 'Dreamspace Realty';
$companyId = 3;
$sessionId = session_id();

// Function to track visit
function trackMarketplaceVisit($userId, $companyId, $productId, $productName, $companyName, $sessionId) {
    // Marketplace database credentials (same as in group-site/includes/config.php)
    $db_servername = "sql101.infinityfree.com";
    $db_username = "if0_40010144";
    $db_password = "hostedpassword";
    $dbname = "if0_40010144_groupprojectdb";
    
    try {
        // Create connection with error suppression
        $conn = @new mysqli($db_servername, $db_username, $db_password, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            error_log("Visit tracking DB error: " . $conn->connect_error);
            return false;
        }
        
        // Set charset
        $conn->set_charset("utf8mb4");
        
        // Prepare and execute insert
        $stmt = $conn->prepare("INSERT INTO product_visits (user_id, company_id, product_id, product_name, company_name, session_id, visited_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        
        if (!$stmt) {
            error_log("Visit tracking prepare error: " . $conn->error);
            $conn->close();
            return false;
        }
        
        // Bind parameters - user_id can be null for guest visits
        $stmt->bind_param("iissss", $userId, $companyId, $productId, $productName, $companyName, $sessionId);
        
        // Execute
        $result = $stmt->execute();
        
        if (!$result) {
            error_log("Visit tracking execute error: " . $stmt->error);
        }
        
        $stmt->close();
        $conn->close();
        
        return $result;
    } catch (Exception $e) {
        error_log("Visit tracking exception: " . $e->getMessage());
        return false;
    }
}

// If called via GET, track and return JSON
if (isset($_GET['user_id']) || isset($_GET['product_id'])) {
    $tracked = false;
    
    if ($userId && $productId) {
        $tracked = trackMarketplaceVisit($userId, $companyId, $productId, $productName, $companyName, $sessionId);
    }
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $tracked,
        'message' => $tracked ? 'Visit tracked successfully' : 'Visit tracking failed',
        'user_id' => $userId,
        'product_id' => $productId
    ]);
    exit;
}

// If included as a file, make function available
?>

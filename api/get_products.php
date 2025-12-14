<?php
// Start output buffering FIRST
ob_start();

// Set CORS headers IMMEDIATELY (before any output)
header('Access-Control-Allow-Origin: *', true);
header('Access-Control-Allow-Methods: GET, POST, OPTIONS', true);
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept', true);
header('Access-Control-Allow-Credentials: false', true);
header('Access-Control-Max-Age: 86400', true);
header('Content-Type: application/json; charset=utf-8', true);

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    ob_end_clean();
    exit;
}

// Security token (from document: dreamspace_secret_2025)
$required_token = 'dreamspace_secret_2025';
$provided_token = $_GET['token'] ?? '';

// Also check header token
if (empty($provided_token) && function_exists('getallheaders')) {
    $headers = getallheaders();
    if ($headers) {
        foreach ($headers as $key => $value) {
            if (strtolower($key) === 'x-api-token' || strtolower($key) === 'authorization') {
                $provided_token = $value;
                break;
            }
        }
    }
}

if ($provided_token !== $required_token) {
    ob_end_clean();
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access - invalid token'], JSON_PRETTY_PRINT);
    exit;
}

// Product data (matching the structure from listings.php and product.php)
$products = [
    [
        'id' => '201',
        'name' => 'Property Valuation Service',
        'description' => 'Professional property assessment and market analysis',
        'long_description' => 'Our certified appraisers provide comprehensive property valuations using the latest market data and industry standards. This service includes detailed market analysis, comparable sales research, and a professional valuation report that can be used for refinancing, selling, or investment decisions.',
        'price' => '$299',
        'thumbnail' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=800',
        'emoji' => '🏠',
        'slug' => 'property-valuation-service',
        'category' => 'Valuation'
    ],
    [
        'id' => '202',
        'name' => 'Home Staging Consultation',
        'description' => 'Expert advice to maximize your property\'s appeal',
        'long_description' => 'Transform your property into a buyer\'s dream with our professional staging consultation. Our certified staging experts will provide personalized recommendations to enhance your home\'s visual appeal and maximize its market value.',
        'price' => '$199',
        'thumbnail' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=800',
        'emoji' => '✨',
        'slug' => 'home-staging-consultation',
        'category' => 'Staging'
    ],
    [
        'id' => '203',
        'name' => 'Real Estate Photography',
        'description' => 'Professional photos that showcase your property',
        'long_description' => 'High-quality photography is essential for attracting potential buyers. Our professional photographers use advanced equipment and techniques to capture your property in the best light, creating stunning images that make your listing stand out.',
        'price' => '$150',
        'thumbnail' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=800',
        'emoji' => '📸',
        'slug' => 'real-estate-photography',
        'category' => 'Photography'
    ],
    [
        'id' => '204',
        'name' => 'Market Analysis Report',
        'description' => 'Comprehensive neighborhood and market trends analysis',
        'long_description' => 'Stay informed about your local real estate market with our detailed analysis reports. We track market trends, price movements, inventory levels, and neighborhood developments to help you make informed decisions.',
        'price' => '$99',
        'thumbnail' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800',
        'emoji' => '📊',
        'slug' => 'market-analysis-report',
        'category' => 'Analysis'
    ],
    [
        'id' => '205',
        'name' => 'Property Management',
        'description' => 'Complete property management and tenant services',
        'long_description' => 'Let us handle all aspects of your rental property management. From tenant screening and rent collection to maintenance coordination and legal compliance, we provide comprehensive management services to maximize your investment returns.',
        'price' => '$200/mo',
        'thumbnail' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?q=80&w=800',
        'emoji' => '🔑',
        'slug' => 'property-management',
        'category' => 'Management'
    ],
    [
        'id' => '206',
        'name' => 'Legal Documentation',
        'description' => 'Contract preparation and legal document review',
        'long_description' => 'Ensure your real estate transactions are legally sound with our professional legal documentation services. Our experienced real estate attorneys will prepare, review, and explain all necessary contracts and legal documents.',
        'price' => '$399',
        'thumbnail' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=800',
        'emoji' => '📋',
        'slug' => 'legal-documentation',
        'category' => 'Legal'
    ],
    [
        'id' => '207',
        'name' => 'Investment Consultation',
        'description' => 'Strategic advice for real estate investment opportunities',
        'long_description' => 'Maximize your real estate investment potential with our expert consultation services. We analyze market opportunities, evaluate investment properties, and provide strategic advice tailored to your financial goals and risk tolerance.',
        'price' => '$250',
        'thumbnail' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=800',
        'emoji' => '💼',
        'slug' => 'investment-consultation',
        'category' => 'Investment'
    ],
    [
        'id' => '208',
        'name' => 'Home Inspection Service',
        'description' => 'Thorough property inspection and detailed reporting',
        'long_description' => 'Protect your investment with our comprehensive home inspection service. Our certified inspectors thoroughly examine all major systems and components of the property, providing detailed reports to help you make informed decisions.',
        'price' => '$350',
        'thumbnail' => 'https://images.unsplash.com/photo-1581578731548-c6a0c3f2fcc0?q=80&w=800',
        'emoji' => '🔍',
        'slug' => 'home-inspection-service',
        'category' => 'Inspection'
    ],
    [
        'id' => '209',
        'name' => 'Mortgage Brokerage',
        'description' => 'Connect with the best mortgage rates and lenders',
        'long_description' => 'Secure the best mortgage rates and terms with our extensive network of lenders. Our mortgage brokers work with multiple financial institutions to find the most competitive rates and loan products that fit your specific needs.',
        'price' => '$0',
        'thumbnail' => 'https://images.unsplash.com/photo-1554224154-26032ffc0d07?q=80&w=800',
        'emoji' => '💰',
        'slug' => 'mortgage-brokerage',
        'category' => 'Financing'
    ],
    [
        'id' => '210',
        'name' => 'Relocation Assistance',
        'description' => 'Complete moving and relocation support services',
        'long_description' => 'Make your move stress-free with our comprehensive relocation assistance. We help you find new homes, coordinate moving services, and provide local area information to ensure a smooth transition to your new location.',
        'price' => '$199',
        'thumbnail' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&w=800',
        'emoji' => '🚚',
        'slug' => 'relocation-assistance',
        'category' => 'Relocation'
    ]
];

ob_end_clean();
echo json_encode([
    'success' => true,
    'company' => 'Dreamspace Realty',
    'products' => $products
], JSON_PRETTY_PRINT);
ob_end_flush();
?>


# For Your Teammates - Group Marketplace Integration Guide

## 👋 Welcome!

This document contains all the information your teammates need to integrate their websites with the Group Marketplace project.

## ⚡ Quick Start (5-Minute Setup)

If you just want to get started quickly, follow these essential steps:

1. **Create API Directory:** Create an `api/` folder in your website root

2. **Create `api/get_products.php`:** Copy the template from Section 1A below

3. **Create `api/get_product.php`:** Copy the template from Section 1B below

4. **Add CORS Headers:** Either:
   - Create `api/.htaccess` with CORS configuration (Section 7), OR
   - Add CORS headers directly in your PHP files (Section 7)

5. **Test Your APIs:**
   ```
   https://yoursite.com/api/get_products.php?token=your_secret_token
   https://yoursite.com/api/get_product.php?token=your_secret_token&product_id=1
   ```

6. **Share Your Info:**
   - Company Name
   - Website URL
   - API Base URL (usually `https://yoursite.com/api`)
   - Your secret token
   - Your assigned Company ID (1, 2, 3, or 4)

That's it! The Group Marketplace will automatically:
- Display your products
- Track visits
- Collect reviews
- Manage wishlists
- Calculate rankings

**For advanced features** (user authentication, wishlist buttons, etc.), see the detailed sections below.

## 📋 What You Need to Do

### 1. Create API Endpoints on Your Website

You need to create **TWO API endpoints** that the Group Marketplace can access via CURL:

#### A. Products API Endpoint

**File:** `api/get_products.php` (or similar path)

**Purpose:** Returns a JSON list of all your products

**Required Format:**
```php
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Security token (USE YOUR OWN SECRET TOKEN)
$required_token = 'your_secret_token_here';
$provided_token = $_GET['token'] ?? '';
if ($provided_token !== $required_token) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access - invalid token']);
    exit;
}

// Get your products (adjust based on your data structure)
$products = [
    [
        'id' => 1,
        'name' => 'Product Name',
        'description' => 'Product description',
        'price' => '$29.99',
        'thumbnail' => 'path/to/thumbnail.jpg', // Optional
        'emoji' => '📦', // Optional fallback
        'slug' => 'product-slug',
        // ... any other product fields
    ],
    // ... more products
];

echo json_encode([
    'success' => true,
    'company' => 'Your Company Name',
    'products' => $products
], JSON_PRETTY_PRINT);
?>
```

#### B. Single Product API Endpoint

**File:** `api/get_product.php` (or similar path)

**Purpose:** Returns details for a single product by ID

**Required Format:**
```php
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Security token (USE YOUR OWN SECRET TOKEN)
$required_token = 'your_secret_token_here';
$provided_token = $_GET['token'] ?? '';
if ($provided_token !== $required_token) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access - invalid token']);
    exit;
}

$productId = $_GET['product_id'] ?? '';

if (empty($productId)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Product ID is required']);
    exit;
}

// Fetch your product by ID (adjust based on your data structure)
$product = [
    'id' => $productId,
    'name' => 'Product Name',
    'description' => 'Short description',
    'long_description' => 'Long detailed description',
    'price' => '$29.99',
    'thumbnail' => 'path/to/thumbnail.jpg',
    'emoji' => '📦',
    'slug' => 'product-slug',
    // ... any other product fields
];

echo json_encode([
    'success' => true,
    'product' => $product
], JSON_PRETTY_PRINT);
?>
```

### 2. Share Your API Information

Please provide the following information:

1. **Your Company Name**
2. **Your Website URL** (e.g., https://yoursite.com)
3. **API Base URL** (e.g., https://yoursite.com/api)
4. **Secret Token** for API access (for security)
5. **Your Company ID** (will be assigned: 1, 2, 3, or 4)

### 3. Current Company Information

Based on the existing setup, here's what's already configured:

| Company | URL | API Token | Company ID |
|---------|-----|-----------|------------|
| Ember Interactive | https://dhruvjcmpe272.page.gd | dhruvs_secret_api | 1 |
| Odyssey Horizons | https://tanishadave.great-site.net | secret_2025 | 2 |
| Dreamspace Realty | https://neeraja272.infinityfreeapp.com | dreamspace_secret_2025 | 3 |
| Group Marketplace | (TBD) | group_marketplace_secret_2025 | 4 |

### 4. Product Data Structure Requirements

Your products should include at minimum:
- `id` - Unique product identifier (string or number)
- `name` - Product name
- `description` - Short description
- `price` - Price (can be formatted like "$29.99")

Optional but recommended:
- `long_description` - Detailed product description
- `thumbnail` - Image URL path
- `emoji` - Emoji fallback if no image
- `slug` - URL-friendly product identifier
- Any other fields you want to display

### 5. Testing Your API

Once you've created your API endpoints, test them:

```
# Test products endpoint
https://yoursite.com/api/get_products.php?token=your_secret_token

# Test single product endpoint
https://yoursite.com/api/get_product.php?token=your_secret_token&product_id=1
```

Both should return valid JSON responses.

### 6. Database Requirements (Optional but Recommended)

If you want to support reviews and ratings on your products from the Group Marketplace:

**No changes needed to your database!** The Group Marketplace stores all reviews/ratings in its own database, linked to your products by:
- `company_id` (your company ID)
- `product_id` (your product ID as a string)

So reviews will work automatically once your API endpoints are set up.

### 7. Enable CORS Headers for API Endpoints

**CRITICAL:** Your API endpoints must allow cross-origin requests from the Group Marketplace.

#### Option A: Using .htaccess (Recommended for Apache servers)

Create or update `api/.htaccess`:

```apache
# Enable CORS headers for API endpoints
<IfModule mod_headers.c>
    Header always set Access-Control-Allow-Origin "*"
    Header always set Access-Control-Allow-Methods "GET, POST, OPTIONS"
    Header always set Access-Control-Allow-Headers "Content-Type, Authorization, X-Requested-With, Accept"
    Header always set Access-Control-Allow-Credentials "false"
    Header always set Access-Control-Max-Age "86400"
</IfModule>

# Handle OPTIONS preflight requests
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_METHOD} OPTIONS
    RewriteRule ^(.*)$ $1 [R=200,L]
</IfModule>
```

#### Option B: Add CORS Headers in PHP (Alternative)

If you can't use .htaccess, add these headers at the **VERY BEGINNING** of each API file (before any output):

```php
<?php
// MUST be at the top, before any output or includes
header('Access-Control-Allow-Origin: *', true);
header('Access-Control-Allow-Methods: GET, POST, OPTIONS', true);
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept', true);
header('Access-Control-Allow-Credentials: false', true);
header('Access-Control-Max-Age: 86400', true);

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
?>
```

### 8. Handle User Authentication from Group Marketplace

When users click on your products from the Group Marketplace, you'll receive authentication parameters in the URL.

#### What Happens:

When a logged-in user from Group Marketplace visits your site, your product URLs will include:
- `marketplace_user_id` - The user's ID in the Group Marketplace database
- `hash` - A security hash for validation: `md5(user_id + 'marketplace_secret_2025')`

**Example URL:**
```
https://yoursite.com/pages/product_detail.php?product_id=123&marketplace_user_id=5&hash=abc123...
```

#### What You Need to Do:

1. **Detect and Validate the User ID:**

In your product pages (especially `product_detail.php`), check for these parameters:

```php
<?php
// At the top of your product_detail.php or relevant pages
$marketplaceUserId = $_GET['marketplace_user_id'] ?? null;
$hash = $_GET['hash'] ?? '';

// Validate the hash if user ID is present
if ($marketplaceUserId) {
    $expectedHash = md5($marketplaceUserId . 'marketplace_secret_2025');
    if ($hash === $expectedHash) {
        // Valid marketplace user!
        // You can now:
        // 1. Create/update a local user session
        // 2. Track that this is a marketplace user
        // 3. Show personalized content
        $_SESSION['marketplace_user_id'] = $marketplaceUserId;
        $_SESSION['is_marketplace_user'] = true;
    }
}
?>
```

2. **Optional: Create Local User Mapping**

If you want to track marketplace users in your own database:

```php
// Check if marketplace user exists in your local users table
if ($marketplaceUserId && $hash === $expectedHash) {
    // Create a mapping between marketplace user ID and your local user system
    // This allows you to:
    // - Track wishlist items locally
    // - Show personalized recommendations
    // - Maintain user preferences
}
```

### 9. Support Wishlist Integration from Group Marketplace

Users can add your products to their Group Marketplace wishlist. To support this feature:

#### A. Accept Wishlist Add Requests via API (Optional but Recommended)

The Group Marketplace can add items to wishlist directly via API call. If you want to also store wishlist items locally on your site, create:

**File:** `api/add_to_wishlist.php` (Optional)

```php
<?php
// Start output buffering FIRST
ob_start();

// Set CORS headers IMMEDIATELY
header('Access-Control-Allow-Origin: *', true);
header('Access-Control-Allow-Methods: GET, POST, OPTIONS', true);
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept', true);
header('Content-Type: application/json; charset=utf-8', true);

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    ob_end_clean();
    exit;
}

// Security token
$required_token = 'group_marketplace_secret_2025';
$provided_token = $_GET['token'] ?? '';
if ($provided_token !== $required_token) {
    ob_end_clean();
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$marketplaceUserId = $_GET['user_id'] ?? null;
$productId = $_GET['product_id'] ?? '';
$productName = $_GET['product_name'] ?? 'Product';

if (empty($marketplaceUserId) || empty($productId)) {
    ob_end_clean();
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}

// Add to your local wishlist if you have one
// This is OPTIONAL - Group Marketplace stores wishlist in its own database
// You only need this if you want to also store wishlist items locally

// Example implementation (if you have a local wishlist table):
/*
require_once '../includes/config.php'; // Your database config
$conn = getDbConnection();

if ($conn) {
    // Map marketplace_user_id to your local user_id if needed
    // Or create a new entry linking marketplace_user_id to product_id
    $stmt = $conn->prepare("INSERT INTO wishlist (marketplace_user_id, product_id, product_name) 
                           VALUES (?, ?, ?)
                           ON DUPLICATE KEY UPDATE added_at = NOW()");
    $stmt->bind_param("iss", $marketplaceUserId, $productId, $productName);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
}
*/

ob_end_clean();
echo json_encode(['success' => true, 'message' => 'Product added to wishlist']);
ob_end_flush();
?>
```

**Note:** This is OPTIONAL. The Group Marketplace stores all wishlist items in its own database. You only need this endpoint if you want to also maintain a local copy of wishlist items.

#### B. Add "Add to Group Marketplace Wishlist" Button (Optional)

On your product pages, you can add a button that lets users add products to their Group Marketplace wishlist:

```php
<?php
// In your product_detail.php
$marketplaceUserId = $_SESSION['marketplace_user_id'] ?? $_GET['marketplace_user_id'] ?? null;
$productId = $product['id'];
$productName = $product['name'];
$companyName = 'Your Company Name'; // Must match exactly what's in Group Marketplace config
?>

<?php if ($marketplaceUserId): ?>
    <button onclick="addToMarketplaceWishlist()" class="btn">
        ❤️ Add to Group Marketplace Wishlist
    </button>
    
    <script>
    function addToMarketplaceWishlist() {
        const marketplaceUserId = <?php echo json_encode($marketplaceUserId); ?>;
        const productId = <?php echo json_encode($productId); ?>;
        const productName = <?php echo json_encode($productName); ?>;
        const companyName = <?php echo json_encode($companyName); ?>;
        
        fetch('https://group-marketplace-url.com/api/add_to_wishlist_v2.php?token=group_marketplace_secret_2025&user_id=' + marketplaceUserId + '&company=' + encodeURIComponent(companyName) + '&product_id=' + encodeURIComponent(productId) + '&product_name=' + encodeURIComponent(productName))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Added to Group Marketplace wishlist!');
                } else {
                    alert('Error: ' + (data.error || 'Failed to add to wishlist'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to add to wishlist. Please try again.');
            });
    }
    </script>
<?php endif; ?>
```

### 10. Track Product Visits for Group Marketplace

To help the Group Marketplace track which products are popular, you can optionally send visit tracking data.

#### Option A: Automatic Tracking via URL Parameters (Recommended)

When users visit your products from the Group Marketplace, visits are tracked automatically. No action needed!

#### Option B: Manual Tracking via API (Advanced)

If you want to actively send visit data to Group Marketplace:

**File:** `api/track_visit.php` (Create this only if you want active tracking)

```php
<?php
// This would call Group Marketplace's tracking API
// Usually not needed - Group Marketplace tracks visits when users click from marketplace
?>
```

**Note:** Usually not needed - the Group Marketplace automatically tracks visits when users browse products through the marketplace interface.

### 11. Product URL Structure

Ensure your product URLs can accept additional parameters:

**Your product URLs should work with:**
- Basic: `product_detail.php?product_id=123`
- With marketplace user: `product_detail.php?product_id=123&marketplace_user_id=5&hash=abc123`
- With slug: `product_detail.php?slug=product-name&marketplace_user_id=5&hash=abc123`

Make sure your product pages:
1. Don't break when extra URL parameters are present
2. Handle both `product_id` and `slug` if you use slugs
3. Extract and validate `marketplace_user_id` and `hash` as shown in Section 8

### 12. Product Image/Thumbnail URLs

**IMPORTANT:** For product thumbnails to display correctly in Group Marketplace:

- Use **absolute URLs** in your API responses when possible
- If using relative paths, ensure they resolve correctly when accessed from external sites
- Example:
  ```php
  // Good - Absolute URL
  'thumbnail' => 'https://yoursite.com/images/product1.jpg'
  
  // Also OK - Relative path from site root
  'thumbnail' => '/images/product1.jpg'
  
  // Problematic - Relative to current directory
  'thumbnail' => '../images/product1.jpg'  // Won't work from external sites
  ```

### 13. Visit Tracking (Automatic - No Action Needed)

The Group Marketplace automatically tracks product visits from users when they browse through the marketplace. This data powers:
- **Browsing History** - Users can see their recently viewed products
- **Top 5 Rankings** - Most visited products by company and marketplace-wide
- **Trending Products** - Products trending in the last 7 days

**No action needed on your end** - visits are tracked in the Group Marketplace database.

## 🎯 Complete Implementation Checklist

### Required (For Basic Functionality)
- [ ] Create `api/get_products.php` endpoint with CORS headers
- [ ] Create `api/get_product.php` endpoint with CORS headers
- [ ] Add `.htaccess` with CORS configuration OR add CORS headers in PHP
- [ ] Test both endpoints return valid JSON
- [ ] Share API information (company name, URL, token, company ID)
- [ ] Ensure product URLs accept additional parameters

### Recommended (For Full Feature Support)
- [ ] Handle `marketplace_user_id` and `hash` parameters in product pages
- [ ] Validate hash for security
- [ ] Create local user session mapping (optional)
- [ ] Use absolute URLs for product thumbnails
- [ ] Support both `product_id` and `slug` in URLs (if applicable)

### Optional (For Enhanced Integration)
- [ ] Create `api/add_to_wishlist.php` for local wishlist sync (if you maintain local wishlist)
- [ ] Add "Add to Group Marketplace Wishlist" button on product pages
- [ ] Create user mapping between marketplace users and local users

## 🎨 Features Enabled by Your Integration

Once you've completed the required setup, the following features will automatically work:

### 1. Product Display & Browsing
- ✅ Your products appear in Group Marketplace product listings
- ✅ Users can view all products from all companies in one place
- ✅ Product details pages show full product information
- ✅ Product images/thumbnails display (if using absolute URLs)

### 2. Product Search & Discovery
- ✅ Products searchable across all companies
- ✅ Category filtering (if you provide category data in API)
- ✅ Company-based filtering

### 3. Reviews & Ratings
- ✅ Users can submit reviews (1-5 stars) for your products
- ✅ Users can write detailed review text
- ✅ Average ratings calculated automatically
- ✅ Review count displayed on product pages
- ✅ All reviews stored in Group Marketplace database
- ✅ Reviews linked to your products via `company_id` + `product_id`

**What You Need:** Nothing! Reviews are stored in Group Marketplace database. Just ensure your product IDs are consistent in API responses.

### 4. Wishlist Functionality
- ✅ Users can add your products to their Group Marketplace wishlist
- ✅ Wishlist accessible from Group Marketplace account
- ✅ Users can remove items from wishlist
- ✅ Wishlist persists across sessions
- ✅ All wishlist data stored in Group Marketplace database

**What You Need:** Nothing! Wishlist is fully managed by Group Marketplace. Optional: Add "Add to Wishlist" button on your product pages if you want users to add items directly from your site.

### 5. Browsing History
- ✅ User browsing history tracked automatically
- ✅ History shows products viewed from all companies
- ✅ Users can view their complete browsing history
- ✅ History shows company name and visit timestamp
- ✅ Users can clear their browsing history
- ✅ Tracks both logged-in users and guests (via session)

**What You Need:** Nothing! Visit tracking happens automatically when users browse through Group Marketplace.

### 6. Top 5 Rankings

The Group Marketplace provides several ranking features powered by visit and review data:

#### A. Top 5 by Company
- ✅ Shows most visited products per company
- ✅ Shows best rated products per company
- ✅ Updated in real-time based on actual data
- ✅ Direct links to view products

#### B. Top 5 Marketplace-Wide
- ✅ Most visited products across ALL companies
- ✅ Best rated products across ALL companies  
- ✅ Most reviewed products across ALL companies
- ✅ Trending products (last 7 days)

**What You Need:** Ensure your products are accessible via API. Rankings are calculated automatically from visit and review data.

### 7. User Authentication Integration

When users log into Group Marketplace:
- ✅ They can browse all company products with one account
- ✅ Their reviews, wishlist, and history work across all companies
- ✅ When they visit your site from Group Marketplace, you receive their user ID
- ✅ You can optionally create local user sessions based on marketplace user ID

**What You Need:** Handle `marketplace_user_id` and `hash` parameters as described in Section 8.

### 8. Analytics & Insights

The Group Marketplace tracks:
- ✅ Total product visits per product
- ✅ Average ratings per product
- ✅ Review counts per product
- ✅ Trending products (recent activity)
- ✅ Company-level statistics

**What You Need:** Nothing! All analytics are automatically calculated.

## 📊 Data Flow Summary

### Product Data Flow
```
Your Website API → Group Marketplace → Displayed to Users
     ↓
get_products.php (all products)
get_product.php (single product)
```

### User Action Flow

#### Adding to Wishlist
```
User clicks "Add to Wishlist" on Group Marketplace
→ Group Marketplace API call
→ Stored in Group Marketplace database
→ (Optional) Your site receives notification via API
```

#### Viewing Products
```
User browses Group Marketplace
→ Clicks on your product
→ Group Marketplace tracks visit
→ Redirects to your product page
→ (Optional) Passes marketplace_user_id for personalization
```

#### Writing Reviews
```
User writes review on Group Marketplace product page
→ Stored in Group Marketplace database
→ Linked to your product via company_id + product_id
→ Displayed on product pages for all users
```

### Database Storage

**Group Marketplace Database Stores:**
- Users (shared across all companies)
- Product visits (browsing history)
- Reviews & ratings (for all products from all companies)
- Wishlist items (for all products from all companies)

**Your Website:**
- Your own product data (via API)
- Your own user data (if applicable)
- Optional: Local wishlist copy (if you implement it)

## 🔐 Security Best Practices

1. **API Token Security**
   - Use a strong, unique token
   - Never expose token in client-side JavaScript
   - Rotate tokens if compromised

2. **Hash Validation**
   - Always validate the `hash` parameter when receiving `marketplace_user_id`
   - Hash formula: `md5(user_id + 'marketplace_secret_2025')`
   - Reject requests with invalid hashes

3. **CORS Configuration**
   - Only allow necessary origins if possible
   - Use `*` only if absolutely necessary (as we do for simplicity)
   - Consider restricting to Group Marketplace domain in production

4. **Input Validation**
   - Sanitize all user input
   - Validate product IDs match expected format
   - Check product existence before operations

## 🐛 Troubleshooting

### Products Not Showing
- ✅ Verify API endpoints return valid JSON
- ✅ Check CORS headers are set correctly
- ✅ Test endpoints directly in browser
- ✅ Verify token matches exactly (case-sensitive)
- ✅ Check error logs for API call failures

### Images Not Displaying
- ✅ Use absolute URLs for thumbnails
- ✅ Ensure images are publicly accessible
- ✅ Check CORS for image resources if on different domain

### Wishlist Not Working
- ✅ Verify user is logged into Group Marketplace
- ✅ Check API endpoint accepts correct parameters
- ✅ Ensure company name matches exactly (case-sensitive)
- ✅ Verify product ID format is correct

### User Authentication Issues
- ✅ Validate hash correctly
- ✅ Check session handling
- ✅ Ensure `marketplace_user_id` is preserved in URLs

### CORS Errors
- ✅ Ensure CORS headers are set BEFORE any output
- ✅ Handle OPTIONS preflight requests
- ✅ Check `.htaccess` is in correct directory
- ✅ Verify mod_headers is enabled (Apache)

## 📝 API Endpoint Reference

### Required Endpoints

#### GET `/api/get_products.php`
**Parameters:**
- `token` (required) - Your secret API token

**Response:**
```json
{
  "success": true,
  "company": "Your Company Name",
  "products": [
    {
      "id": "1",
      "name": "Product Name",
      "description": "Short description",
      "price": "$29.99",
      "thumbnail": "https://yoursite.com/image.jpg",
      "emoji": "📦",
      "slug": "product-slug"
    }
  ]
}
```

#### GET `/api/get_product.php`
**Parameters:**
- `token` (required) - Your secret API token
- `product_id` (required) - Product identifier

**Response:**
```json
{
  "success": true,
  "product": {
    "id": "1",
    "name": "Product Name",
    "description": "Short description",
    "long_description": "Long detailed description",
    "price": "$29.99",
    "thumbnail": "https://yoursite.com/image.jpg",
    "emoji": "📦",
    "slug": "product-slug"
  }
}
```

### Optional Endpoints

#### POST/GET `/api/add_to_wishlist.php`
**Parameters:**
- `token` (required) - `group_marketplace_secret_2025`
- `user_id` (required) - Marketplace user ID
- `company` (required) - Your company name (exact match)
- `product_id` (required) - Product identifier
- `product_name` (optional) - Product name for display

**Response:**
```json
{
  "success": true,
  "message": "Product added to wishlist"
}
```

## 🎉 Summary

Once you complete the **Required** checklist items, all Group Marketplace features will work with your products:

✅ **Product Display** - Your products appear in marketplace  
✅ **Product Details** - Full product information available  
✅ **Reviews & Ratings** - Users can review your products  
✅ **Wishlist** - Users can save your products  
✅ **Browsing History** - Visit tracking works automatically  
✅ **Top 5 Rankings** - Your products can appear in rankings  
✅ **User Integration** - Marketplace users can access your site  

The Group Marketplace handles all user data, reviews, wishlist, and analytics - you just need to provide product data via API!

---

**Questions or Need Help?** Contact the Group Marketplace administrator for assistance.

**Document Version:** 2.0 | **Last Updated:** Based on full feature implementation



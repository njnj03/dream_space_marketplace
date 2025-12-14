<?php $page='users'; $companyName='DreamSpace Realty'; include 'includes/header.php'; ?>
<?php
// Configuration: endpoints for each company's users API
// Replace the example URLs for Company B and Company C with the real hosts when available
$companyEndpoints = [
  'Company A' => 'http://neeraja272.infinityfreeapp.com/api/users.php?token=dreamspace_secret_2025', // local this site
  'Company B' => 'https://tanishadave.great-site.net/api/get_users.php?token=secret_2025',
  'Company C' => 'https://dhruvjcmpe272.page.gd/api/get_users.php?token=dhruvs_secret_api',
  'Company D' => 'https://mchopra.great-site.net/api/get_users.php?token=brew_secret_2025',
];

// Optional token shared across group (match server setting if enabled)
$USE_TOKEN = true; // set to true if you enable token on servers
$GROUP_TOKEN = 'ember_secret_2024';
// Optional per-company token overrides (use if teammates use different tokens)
$companyTokens = [
  'Company A' => 'ember_secret_2024',
  'Company B' => 'secret_2025',
  // 'Company C' => 'another_token',
];

function fetchUsersViaCurl(string $url, string $company, bool $useToken, string $token): array {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 6);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
  $headers = ['Accept: application/json'];
  if ($useToken) { $headers[] = 'X-API-Token: ' . $token; }
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $body = curl_exec($ch);
  $err = curl_error($ch);
  $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if ($err || $status >= 400 || $body === false) {
    return ['error' => $err ?: ('HTTP ' . $status)];
  }
  $json = json_decode($body, true);
  if (!is_array($json)) {
    return ['error' => 'Invalid JSON'];
  }
  // Agreed schema: success, company, total_users, users[] with id, name, email, joined
  if (isset($json['success']) && $json['success'] === true && isset($json['users']) && is_array($json['users'])) {
    $normalized = [];
    foreach ($json['users'] as $u) {
      $normalized[] = [
        'company' => $json['company'] ?? $company,
        'name' => $u['name'] ?? '',
        'email' => $u['email'] ?? '',
        'joined' => $u['joined'] ?? '',
      ];
    }
    return ['users' => $normalized];
  }
  return ['error' => 'Schema mismatch'];
}

$allUsers = [];
$errors = [];
foreach ($companyEndpoints as $company => $url) {
  $tokenToUse = $companyTokens[$company] ?? $GROUP_TOKEN;
  $result = fetchUsersViaCurl($url, $company, $USE_TOKEN, $tokenToUse);
  if (isset($result['error'])) {
    $errors[] = $company . ': ' . $result['error'];
    continue;
    }
  foreach ($result['users'] as $user) {
    $allUsers[] = $user;
  }
}

// Sort by company then name for consistency
usort($allUsers, function($a, $b){
  return [$a['company'], $a['name']] <=> [$b['company'], $b['name']];
});
?>

<h1>Users Across Companies</h1>
<p class="lead">Aggregated list of users from DreamSpace Realty, Odessey Horizons, Brew & Blend Cafe and Ember Interactive via cURL. Follows the shared JSON schema.</p>

<?php if (!empty($errors)): ?>
  <div class="notice error">
    <strong>Some sources could not be loaded:</strong>
    <ul>
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="card">
  <table class="table">
    <thead>
      <tr>
        <th>Company</th>
        <th>Name</th>
        <th>Email</th>
        <th>Joined</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($allUsers)): ?>
        <tr>
          <td colspan="4">No users available.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($allUsers as $u): ?>
          <tr>
            <td><?= htmlspecialchars($u['company']) ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['joined']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<p style="margin-top:16px">
  <a class="btn" href="api/users.php?token=dreamspace_secret_2025" style="background:#374151">View local JSON</a>
  <a class="btn" href="index.php" style="margin-left:8px">Back to Home</a>
</p>

<?php include 'includes/footer.php'; ?>



<?php
  $page='contacts'; $companyName='DreamSpace Realty'; include 'includes/header.php';
  $contactsFile = __DIR__ . '/data/contacts.txt';
  $contacts = [];
  if (is_readable($contactsFile)) {
    foreach (file($contactsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $i => $line) {
      $parts = array_map('trim', explode('|', $line));
      if (count($parts) >= 4) {
        [$name, $role, $email, $phone] = $parts;
        $contacts[] = compact('name','role','email','phone');
      }
    }
  } else {
    $error = "Contacts file not found or not readable at " . $contactsFile;
  }
?>
<h1>Contact Us</h1>
<p class="lead">Drop us a line and the right agent will get back to you quickly.</p>
<?php if(isset($error)): ?>
  <p class="notice error"><?= htmlspecialchars($error) ?></p>
<?php elseif(empty($contacts)): ?>
  <p class="notice">No contacts yet. Add entries to <code>data/contacts.txt</code> using the format: <code>Name | Role | email@example.com | +1 555 555 5555</code>.</p>
<?php else: ?>
  <table class="table contacts-table" role="table" aria-label="Company contacts">
    <thead><tr><th>Name</th><th>Role</th><th>Email</th><th>Phone</th></tr></thead>
    <tbody>
      <?php foreach($contacts as $c): ?>
        <tr>
          <td><?= htmlspecialchars($c['name']) ?></td>
          <td><?= htmlspecialchars($c['role']) ?></td>
          <td><a href="mailto:<?= htmlspecialchars($c['email']) ?>"><?= htmlspecialchars($c['email']) ?></a></td>
          <td><?= htmlspecialchars($c['phone']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
<?php include 'includes/footer.php'; ?>
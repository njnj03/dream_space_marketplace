# DreamSpace Realty — Website

This PHP site satisfies the homework requirements:

- Pages: **Home**, **About**, **Properties (Products/Services)**, **News**, **Contacts**
- Contacts are stored in `data/contacts.txt` and read using PHP in `contacts.php`

## Deploy (InfinityFree quick guide)
1. Ensure hosting supports PHP 7.4+.
2. Upload files to your web root (e.g., `htdocs/`).
3. Visit `https://YOUR-SUBDOMAIN/index.php`.
4. Edit `data/contacts.txt` to update contacts.

**contacts.txt format**

```
Name | Role | email@example.com | +1 555 555 5555
```

## Marketplace integration hints (optional)

- Track visits on each property page by calling a shared function:
  `track_visit($user_id, 'dreamspace', $property_id)`
- Reviews table suggestion:
  `reviews(id, user_id, company, item_id, rating INT, comment TEXT, created_at)`
- Top 5 query example (by average rating):
  `SELECT item_id, AVG(rating) AS avg_rating FROM reviews WHERE company='dreamspace' GROUP BY item_id ORDER BY avg_rating DESC LIMIT 5;`
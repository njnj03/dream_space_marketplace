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

  ##for sftp
  - hosted on infinityfree 
  - vscode extension used to upload files to server
  - command pallete: SFTP: Config
  - SFTP: Upload Proejct
  - SFTP: Sync Local -> Remote

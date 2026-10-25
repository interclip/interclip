# Production migration procedure

Apply migrations during a maintenance window; application and Redis configuration changes must be activated together.

1. Create a database backup and verify it can be restored to a separate database.
2. Stop application writes.
3. Apply `2026-07-11-security-hardening.sql` to the configured database explicitly:

   ```sh
   mysql --database="$DB_NAME" < scripts/migrations/2026-07-11-security-hardening.sql
   ```

   The migration caps expiring legacy destination lifetimes at 48 hours from
   the migration, while preserving any earlier existing expiry. A legacy
   `NULL` expiry remains `NULL` and represents a permanent clip.

   Databases that already ran the original hardening migration must then apply
   `2026-07-14-preserve-permanent-clips.sql` before restoring any permanent
   rows from the pre-migration backup:

   ```sh
   mysql --database="$DB_NAME" < scripts/migrations/2026-07-14-preserve-permanent-clips.sql
   ```

4. Configure `AUTH_TYPE=account`, `OIDC_ISSUER`, `OIDC_CLIENT_ID`, and
   `OIDC_CLIENT_SECRET`. Verify each staff member's identity and immutable OIDC
   `sub`, then calculate the account key using the exact discovery issuer:

   ```sh
   php -r 'require "vendor/autoload.php"; require "includes/lib/oidc.php"; echo oidcAccountSubject($argv[1], $argv[2]), PHP_EOL;' 'https://auth.example.com/application/o/interclip/' 'verified-user-uuid'
   ```

   List staff rows and assign the calculated key to the intended account by
   primary key. Never infer staff identity from email alone. Replace the example
   ID and key below; set `@previous_subject` to the row's current subject (or
   `NULL` for a legacy row) so an unexpected identity change fails closed.

   ```sql
   SELECT id, email, role, subject FROM accounts WHERE role = 'staff';
   SET @previous_subject = NULL;
   UPDATE accounts
   SET subject = 'oidc|replace-with-calculated-sha256'
   WHERE id = 123 AND role = 'staff' AND subject <=> @previous_subject;
   SELECT ROW_COUNT() AS migrated_accounts;
   SELECT id, email, subject FROM accounts WHERE role = 'staff';
   ```

   Verify exactly one row changed and every intended staff account has the
   correct OIDC subject before activation. Resolve any existing visitor row for
   the same OIDC subject before migrating; the unique constraint will reject
   duplicates. Remove the old mock row only after confirming it is not a real account.

5. Verify MySQL's event scheduler is enabled so expired destinations are
   deleted. The cleanup event excludes permanent clips whose `expires_at` is
   `NULL`:

   ```sql
   SHOW VARIABLES LIKE 'event_scheduler';
   ```

6. Activate the application, passworded Redis, deployment-specific Redis prefix, non-root database credentials, trusted proxy ranges, HTTPS origin, and rotated secrets as one coordinated cutover.

7. Redeploy or retire the out-of-band Cloudflare Workers. Verify the former clip API returns the canonical `307` redirect and the facts endpoint returns `410`; an older KV-backed deployment remains vulnerable even after the PHP release changes.

8. Before enabling file uploads, confirm that anonymous presigning is intended, verify that the upload policy has the expected size and lifetime constraints, and pin the exact S3-compatible host with `FILES_UPLOAD_HOST`. Configure `FILES_API_TOKEN` only when the presigner requires Bearer authentication.

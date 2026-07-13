# Production migration procedure

Apply migrations during a maintenance window; application and Redis configuration changes must be activated together.

1. Create a database backup and verify it can be restored to a separate database.
2. Stop application writes.
3. Apply `2026-07-11-security-hardening.sql` to the configured database explicitly:

   ```sh
   mysql --database="$DB_NAME" < scripts/migrations/2026-07-11-security-hardening.sql
   ```

   The migration caps legacy destination lifetimes at 48 hours from the
   migration, while preserving any earlier existing expiry.

4. List staff rows and assign each legitimate account its verified Auth0 `sub` by primary key. Never infer staff identity from email alone.

   ```sql
   SELECT id, email, role, subject FROM accounts WHERE role = 'staff';
   UPDATE accounts
   SET subject = 'auth0|verified-subject-here'
   WHERE id = 123 AND role = 'staff' AND subject IS NULL;
   SELECT id, email FROM accounts WHERE role = 'staff' AND subject IS NULL;
   ```

   The final query must return no legitimate staff accounts before application activation. Remove the old mock row only after confirming it is not a real account.

5. Verify MySQL's event scheduler is enabled so expired destinations are deleted:

   ```sql
   SHOW VARIABLES LIKE 'event_scheduler';
   ```

6. Activate the application, passworded Redis, deployment-specific Redis prefix, non-root database credentials, trusted proxy ranges, HTTPS origin, and rotated secrets as one coordinated cutover.

7. Redeploy or retire the out-of-band Cloudflare Workers. Verify the former clip API returns the canonical `307` redirect and the facts endpoint returns `410`; an older KV-backed deployment remains vulnerable even after the PHP release changes.

8. Keep file uploads disabled until the external presigner rejects unauthenticated calls, uses a rotated server-only credential, and is constrained to the configured S3 host and policy.

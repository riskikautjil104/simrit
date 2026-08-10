# Deployment

## Environments
- Local development
- Staging (recommended)
- Production

## Production Baseline
- Linux server
- PHP 8.3+
- Web server: Nginx or Apache
- MySQL/PostgreSQL
- HTTPS
- Process manager/queue worker if queues are used

## Deployment Steps
1. Pull the approved release.
2. Install PHP dependencies with production flags.
3. Install/build frontend assets.
4. Configure `.env` on the server.
5. Run database migrations.
6. Configure public uploads using the storage instructions below.
7. Cache configuration/routes/views where appropriate.
8. Restart queue workers if used.
9. Verify health and critical routes.

## Environment
Never commit `.env`.

Required production configuration should include:
- APP_ENV=production
- APP_DEBUG=false
- APP_URL
- database credentials
- filesystem configuration
- mail configuration if mail is enabled

## hPanel Without `storage:link`

This project serves uploaded files from `/storage/...`. If hPanel does not allow
symbolic links, use a real folder instead:

1. Create `public/storage` in the Laravel public directory.
2. Upload the contents of local `storage/app/public` into `public/storage`.
3. Make `public/storage` writable by the PHP process, normally permission `755`.
4. Leave `FILESYSTEM_PUBLIC_ROOT` empty in `.env`; Laravel then uses
	`public/storage` automatically. If the application uses a different layout,
	set it to the absolute path of the public storage folder.
5. Set `APP_URL` to the real HTTPS domain, then run `php artisan config:clear` or
	`php artisan config:cache` if Artisan is available.

Do not run `php artisan storage:link` on this setup. New uploads are written
directly into `public/storage`, and existing `/storage/...` URLs continue to work.

After deployment, submit `https://your-domain.example/sitemap.xml` in Google
Search Console. Replace the placeholder with the real domain before adding a
`Sitemap:` line to `public/robots.txt`.

## Backups
Back up:
- Database
- User-uploaded media
- Important application configuration

Test restoration periodically.

## Monitoring
Monitor:
- application errors
- disk space
- database health
- queue failures
- storage usage
- HTTPS certificate

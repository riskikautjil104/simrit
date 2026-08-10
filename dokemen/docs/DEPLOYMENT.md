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
6. Configure storage links where applicable.
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

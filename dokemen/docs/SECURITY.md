# Security Requirements

## Authentication
- Use Laravel's secure authentication mechanisms.
- Hash passwords with Laravel-supported password hashing.
- Require strong passwords for admin accounts.
- Invalidate sessions appropriately on logout/password changes.

## Authorization
- Enforce role access server-side.
- Use policies/gates/middleware.
- Never trust route parameters or hidden form fields for permission decisions.

## Input
- Validate all request data.
- Use Form Requests for complex validation.
- Prevent mass-assignment vulnerabilities.
- Escape/sanitize rich text content.

## File Upload Security
- Validate MIME type and extension.
- Enforce size limits.
- Generate storage filenames.
- Do not execute uploaded files.
- Keep sensitive files outside the public web root when required.
- Do not allow arbitrary HTML/SVG uploads unless explicitly sanitized and needed.

## Web Security
- HTTPS in production.
- CSRF protection.
- Secure cookies.
- Rate-limit sensitive endpoints.
- Do not expose debug mode in production.
- Keep dependencies updated.
- Configure security headers where appropriate.

## Audit
Log:
- Login/logout/security events
- Create/update/delete content
- Publish/unpublish
- User management
- Important settings changes

Avoid logging passwords, tokens, or sensitive secrets.

## Privacy
Do not publish internal documents or personal information unless explicitly approved for public display.

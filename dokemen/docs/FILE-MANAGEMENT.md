# File & Media Management

## Storage
Use Laravel Filesystem. Keep public media and private documents separated where business requirements require it.

## Allowed Images
- jpg
- jpeg
- png
- webp

## Allowed Documents
- pdf
- doc
- docx
- xls
- xlsx
- ppt
- pptx

## Allowed Video
For MVP prefer external video embedding where practical. If uploads are enabled:
- mp4
- webm

## Validation
Every upload must validate:
- MIME type
- extension
- maximum size
- filename normalization
- image dimensions where relevant

Never trust the client-provided filename or MIME type.

## Image Handling
- Generate consistent thumbnails where needed.
- Preserve original where required.
- Store meaningful alt text for public images.
- Avoid huge original images when not needed.

## Document Handling
- Show title, category, file type, size, date.
- Public downloads must only expose documents marked public/published.
- Do not expose private filesystem paths.

## Video
Prefer trusted embed providers for MVP. Sanitize/validate embed URLs against an allowlist.

## File Naming
Generate application-controlled storage names rather than relying on user-provided names.

## Limits
Exact maximum file sizes must be confirmed before production. Use conservative defaults during development and make limits configurable.

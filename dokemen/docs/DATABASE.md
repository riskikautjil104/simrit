# Database Design

## Core Tables

### users
- id
- name
- email
- password
- role
- is_active
- last_login_at
- remember_token
- timestamps

### pages
For editable institutional pages.
- id
- title
- slug
- page_key
- excerpt
- content
- featured_image
- status
- published_at
- created_by
- updated_by
- timestamps

Suggested page keys:
- history
- vision-mission
- organization
- duties-functions
- facilities

### categories
- id
- name
- slug
- description
- timestamps

### news
- id
- category_id nullable
- title
- slug
- excerpt
- content
- cover_image
- status
- published_at
- created_by
- updated_by
- timestamps
- softDeletes

### events
- id
- title
- slug
- description
- cover_image
- location
- starts_at
- ends_at nullable
- status
- created_by
- updated_by
- timestamps
- softDeletes

### announcements
- id
- title
- slug
- content
- published_at
- status
- created_by
- updated_by
- timestamps
- softDeletes

### document_categories
- id
- name
- slug
- description
- timestamps

### documents
- id
- document_category_id
- title
- slug
- description
- file_path
- original_filename
- mime_type
- file_size
- status
- published_at
- uploaded_by
- timestamps
- softDeletes

### galleries
- id
- title
- slug
- description
- cover_image
- status
- published_at
- created_by
- updated_by
- timestamps
- softDeletes

### gallery_items
- id
- gallery_id
- file_path
- original_filename
- caption
- alt_text
- sort_order
- timestamps

### videos
- id
- title
- slug
- description
- provider nullable
- embed_url nullable
- file_path nullable
- thumbnail nullable
- status
- published_at
- created_by
- updated_by
- timestamps
- softDeletes

### services
- id
- title
- slug
- short_description
- content
- icon nullable
- image nullable
- sort_order
- status
- created_by
- updated_by
- timestamps
- softDeletes

### team_members
- id
- name
- position
- department nullable
- photo nullable
- biography nullable
- sort_order
- status
- created_by
- updated_by
- timestamps
- softDeletes

### media
Central media metadata if a media library is implemented.
- id
- disk
- path
- original_filename
- mime_type
- file_size
- collection nullable
- uploaded_by
- timestamps

### activity_logs
- id
- user_id nullable
- action
- subject_type nullable
- subject_id nullable
- description
- metadata nullable JSON
- ip_address nullable
- user_agent nullable
- created_at

### settings
- id
- key
- value
- type
- timestamps

## Relationships
- User hasMany News, Events, Announcements, Documents, etc.
- Category hasMany News.
- DocumentCategory hasMany Documents.
- Gallery hasMany GalleryItems.
- ActivityLog belongsTo User.
- Content records may reference creator/updater users.

## Database Rules
- Use foreign keys where appropriate.
- Add indexes to slugs, status, publication dates, foreign keys, and common search fields.
- Slugs must be unique within their resource.
- Avoid storing large binary files directly in the database; store files using Laravel Filesystem.

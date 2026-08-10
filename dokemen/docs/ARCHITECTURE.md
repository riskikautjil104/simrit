# Architecture

## Application Style
Server-rendered Laravel application with Livewire-enhanced interactions.

## Layers
- Routes
- Controllers / Livewire components
- Form Requests
- Policies
- Services/Actions where business logic is reusable
- Eloquent Models
- Database
- Filesystem/Media services

## Public Area
`/`

Public pages should only expose published/public content.

## Admin Area
`/admin`

Protected by authentication and role authorization.

## Suggested Laravel Structure
- `app/Models`
- `app/Http/Controllers`
- `app/Http/Requests`
- `app/Policies`
- `app/Livewire`
- `app/Services`
- `app/Actions`
- `resources/views`
- `routes/web.php`
- `database/migrations`
- `database/seeders`
- `tests/Feature`
- `tests/Unit`

## Content Publishing
Use a consistent status model such as:
- draft
- published
- archived

Only published content appears on public pages.

## Search
Start with database-backed search/filtering. Full-text search can be added later if content volume requires it.

## Caching
Cache public, low-volatility settings/pages when safe. Invalidate cache when content is published or updated.

## Future Integration
The architecture should not block future API/mobile integration, but API development is outside MVP.

# AGENTS.md — SIMRIT Development Rules

## Project Identity
Build **Sistem Informasi Manajemen Ruang IT (SIMRIT) RSUD Dr. H. Chasan Boesoirie Ternate**.

The product is an institutional information portal/CMS for the IT unit. It is NOT primarily a helpdesk, network monitoring, or IT asset management application in the first release.

## Source of Truth
Read and follow these documents before implementing:
1. `docs/PRD.md`
2. `docs/SRS.md`
3. `docs/ROLE-PERMISSION.md`
4. `docs/DATABASE.md`
5. `docs/UI-UX.md`
6. `docs/SECURITY.md`
7. `TASKS.md`

If a requirement conflicts, stop and surface the conflict instead of silently inventing behavior.

## Stack
- Laravel 13
- PHP 8.3+
- Blade
- Livewire
- Tailwind CSS
- MySQL or PostgreSQL
- Vite
- Laravel Filesystem

## Engineering Rules
1. Use Laravel conventions and clean, maintainable code.
2. Keep controllers thin; put reusable business logic in services/actions where appropriate.
3. Use Form Requests for complex validation.
4. Use policies/gates for authorization.
5. Never rely only on hiding UI elements for authorization.
6. Validate all uploaded files by MIME/type, extension, and size.
7. Use mass-assignment protection.
8. Escape user-generated output; sanitize rich text safely.
9. Never commit secrets, `.env`, credentials, production keys, or private files.
10. Do not change production configuration.
11. Destructive actions require confirmation.
12. Add database indexes where justified by search/filter patterns.
13. Use pagination for public/admin lists that can grow.
14. Prefer soft deletion only where business requirements justify recovery.
15. Use queued jobs for heavy media processing when needed.
16. Write tests for authentication, authorization, CRUD, uploads, publishing, and critical public routes.
17. Do not introduce extra user roles without explicit approval.
18. Do not introduce unrelated features.
19. Keep public URLs stable and SEO-friendly.
20. Maintain `TASKS.md` as work progresses.

## Roles
Only:
- `superadmin`
- `admin`

## Definition of Done
A feature is complete only when:
- UI is implemented
- validation exists
- authorization is enforced
- database/migrations are complete
- empty/loading/error states are handled
- relevant tests pass
- documentation/task status is updated

## Development Workflow
1. Read the relevant specification.
2. Inspect existing code before editing.
3. Implement the smallest complete slice.
4. Run formatting/static checks/tests where available.
5. Verify routes and authorization.
6. Update `TASKS.md`.
7. Summarize files changed and verification performed.

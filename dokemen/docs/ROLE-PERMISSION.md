# Roles & Permissions

## Roles
Only two roles are allowed in MVP:

1. `superadmin`
2. `admin`

## Permission Matrix

| Module | Superadmin | Admin |
|---|---|---|
| Dashboard | Full | View |
| Profile pages | CRUD | CRUD |
| News | CRUD | CRUD |
| Events | CRUD | CRUD |
| Announcements | CRUD | CRUD |
| Documents | CRUD | CRUD |
| Galleries | CRUD | CRUD |
| Videos | CRUD | CRUD |
| IT Services | CRUD | CRUD |
| IT Team | CRUD | CRUD |
| Media Library | Full | Manage content media |
| Admin Management | CRUD | No access |
| Activity Logs | View | No access |
| System Settings | CRUD | No access |

## Authorization Rules
- `superadmin` has full administrative access.
- `admin` can manage approved content modules.
- Admin management, system settings, and audit logs are restricted to Superadmin.
- Authorization must be implemented with Laravel policies/gates/middleware.
- UI hiding is not a substitute for backend authorization.

## Account Status
Users should support active/inactive status.
- Active: can authenticate.
- Inactive: authentication denied.

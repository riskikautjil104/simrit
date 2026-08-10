# SRS — Software Requirements Specification

## 1. Functional Requirements

### Authentication
- FR-AUTH-001: User can log in with email and password.
- FR-AUTH-002: Inactive users cannot log in.
- FR-AUTH-003: User can log out.
- FR-AUTH-004: Passwords must be securely hashed.
- FR-AUTH-005: Authorization must be enforced server-side.

### Public Content
- FR-PUB-001: Visitor can view homepage.
- FR-PUB-002: Visitor can view profile pages.
- FR-PUB-003: Visitor can browse published news.
- FR-PUB-004: Visitor can view event details.
- FR-PUB-005: Visitor can view announcements.
- FR-PUB-006: Visitor can browse public documents.
- FR-PUB-007: Visitor can browse photo galleries.
- FR-PUB-008: Visitor can watch/view published videos.
- FR-PUB-009: Visitor can view IT services.
- FR-PUB-010: Visitor can view IT team profiles.
- FR-PUB-011: Visitor can search public content.

### News
- FR-NEWS-001: Authorized content manager can create news.
- FR-NEWS-002: Authorized content manager can edit news.
- FR-NEWS-003: Authorized content manager can delete/archive news.
- FR-NEWS-004: News supports draft and published states.
- FR-NEWS-005: Published news has title, slug, excerpt, content, cover image, author, and publication date.

### Events
- FR-EVENT-001: Authorized content manager can create, edit, publish, and archive events.
- FR-EVENT-002: Event supports title, slug, date/time, location, description, cover image, and status.

### Announcements
- FR-ANN-001: Authorized content manager can create, edit, publish, and archive announcements.
- FR-ANN-002: Announcement supports title, content, publication date, and status.

### Documents
- FR-DOC-001: Authorized content manager can upload documents.
- FR-DOC-002: Documents have categories.
- FR-DOC-003: Public users can download published public documents.
- FR-DOC-004: Upload validation must enforce allowed MIME types and file size.
- FR-DOC-005: Admin can replace/archive a document without breaking historical references where applicable.

### Gallery
- FR-GAL-001: Authorized content manager can create galleries.
- FR-GAL-002: Gallery can contain multiple images.
- FR-GAL-003: Images have captions/alt text where applicable.
- FR-GAL-004: Public users can browse published galleries.

### Video
- FR-VID-001: Authorized content manager can create video entries.
- FR-VID-002: Video can use an approved external embed URL or uploaded media according to configured policy.
- FR-VID-003: Public users can view published videos.

### Team
- FR-TEAM-001: Authorized content manager can manage team member profiles.
- FR-TEAM-002: Team profile supports name, role/title, photo, biography/description, ordering, and publication status.

### Services
- FR-SVC-001: Authorized content manager can manage IT services.
- FR-SVC-002: Service supports title, slug, icon/image, short description, full description, ordering, and status.

### User Management
- FR-USER-001: Superadmin can create admin accounts.
- FR-USER-002: Superadmin can edit/deactivate admin accounts.
- FR-USER-003: Superadmin can manage roles only within the approved role model.
- FR-USER-004: Admin cannot manage users.

### Activity Log
- FR-AUDIT-001: System records important create/update/delete/publish/login/security actions.
- FR-AUDIT-002: Activity logs are viewable by Superadmin.
- FR-AUDIT-003: Activity log entries identify actor, action, target, timestamp, and useful metadata.

## 2. Non-Functional Requirements
- NFR-001: Responsive on desktop, tablet, and mobile.
- NFR-002: Authentication and authorization are server-side.
- NFR-003: Public pages should be cache-friendly where safe.
- NFR-004: Lists use pagination.
- NFR-005: Uploads are validated and stored using Laravel Filesystem.
- NFR-006: Application must use HTTPS in production.
- NFR-007: Error pages should not expose stack traces in production.
- NFR-008: Critical workflows must have automated tests.

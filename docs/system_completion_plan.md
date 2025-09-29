# Belqas School Management Roadmap

This document outlines a comprehensive roadmap to finish the remaining
work required to turn the Belqas School Laravel application into a
production-ready school management system. The plan is divided into
functional modules, cross-cutting concerns, and project management
milestones so that the team can iterate systematically until every
mission-critical feature is implemented, tested, and documented.

## 1. Core Functional Modules

### 1.1 Authentication & Authorization
- [ ] Review the existing user module and ensure support for guardian,
  student, teacher, staff, and admin roles with configurable
  permissions via Laravel Gates/Policies.
- [ ] Enforce password complexity, password reset flows, and optional
  two-factor authentication.
- [ ] Add activity logging and session management (forced logouts,
  session timeouts) for improved security.

### 1.2 Student Information System (SIS)
- [x] Baseline student registry with guardian contacts, classroom
  assignment, and enrollment records via JSON APIs.
- [ ] Student admission workflow: application intake, document upload,
  acceptance/rejection, and enrollment confirmation.
- [ ] Student profile management with demographics, guardians,
  academic history, health information, and custom fields per school
  policy.
- [ ] Class assignments and section transfers with automatic update of
  class rosters.

### 1.3 Academic Management
- [x] Foundational class, subject, assessment, attendance, and gradebook
  data models with CRUD endpoints.
- [ ] Class, section, and subject management with timetable support.
- [ ] Curriculum planner with unit/lesson breakdown per subject.
- [ ] Grading module: configurable grading schemes, grade entry forms,
  transcript generation, and report cards.
- [ ] Attendance tracking (daily, per session) with analytics dashboards
  for absentee patterns.

### 1.4 Staff & HR
- [ ] Teacher/staff onboarding workflows with document tracking,
  contract templates, and role assignment.
- [ ] Payroll integration: salary scales, deductions, allowances, and
  pay-slip generation.
- [ ] Performance review module and professional development tracking.

### 1.5 Fees & Finance
- [ ] Fee structure configuration (tuition, transport, activities) with
  support for scholarships and discounts.
- [ ] Invoicing and receipt management with multi-payment gateways
  (manual, bank transfer, online).
- [ ] Expense tracking and financial reporting dashboards.

### 1.6 Communication & Engagement
- [ ] Internal messaging between staff, students, and guardians.
- [ ] Announcement board with targeted audiences and push/email/SMS
  notifications.
- [ ] Parent portal/mobile app readiness for viewing grades, attendance,
  and fee statements.

### 1.7 Library & Inventory (Optional Phase)
- [ ] Cataloguing, circulation, fines, and inventory control for library
  assets.
- [ ] Asset tracking for laboratories and classrooms.

## 2. Cross-Cutting Concerns

### 2.1 Localization & Accessibility
- [ ] Ensure Arabic/English localization across all modules using the
  Laravel localization system.
- [ ] Provide RTL-compatible layouts and follow WCAG accessibility
  guidelines.

### 2.2 UI/UX Foundation
- [ ] Establish a design system (components, typography, color palette)
  using Tailwind CSS/Vite for consistency.
- [ ] Implement responsive layouts and navigation suited for desktop and
  mobile usage.

### 2.3 Integrations
- [ ] SMS gateway, email service (Mailgun/SES), and push notification
  providers.
- [ ] Optional integrations with Ministry of Education APIs or national
  ID validation services.
- [ ] Export/import tools for CSV/Excel for bulk operations.

### 2.4 Quality Assurance
- [ ] PHPUnit and Pest feature tests for critical workflows.
- [ ] Static analysis (Larastan, PHPStan) and coding standard checks
  (PHP-CS-Fixer) integrated into CI.
- [ ] Seeders and factories for realistic demo data; nightly database
  backups.

### 2.5 DevOps & Deployment
- [ ] Docker-based local development environment and production-ready
  Dockerfiles.
- [ ] CI/CD pipeline (GitHub Actions/GitLab CI) with automated tests,
  database migrations, and deployment scripts.
- [ ] Monitoring/logging stack (Laravel Telescope, Sentry, or ELK) and
  security hardening (rate limiting, CORS, headers).

## 3. Project Management Milestones

1. **Discovery & Audit** – Inventory current functionality, audit code
   quality, and gather stakeholder requirements.
2. **MVP Definition** – Prioritize must-have modules for the first
   launch and define acceptance criteria.
3. **Iterative Delivery** – Break down modules into sprints with clear
   deliverables, QA, and demo checkpoints.
4. **User Acceptance Testing** – Conduct UAT with school staff, gather
   feedback, and iterate on UX improvements.
5. **Training & Documentation** – Produce user guides, administrator
   manuals, and run training sessions.
6. **Launch & Support** – Plan rollout strategy, establish support
   channels, and schedule post-launch enhancements.

## 4. Immediate Next Steps

1. **Set Up Backlog** – Create issues in the project tracker based on
   the roadmap above. Tag each with priority and estimate.
2. **Stabilize User Module** – Complete request validation, seed roles
   and permissions, and connect UI views to controllers with tests.
3. **Design System Kickoff** – Build reusable layout components and
   confirm RTL support before scaling to new modules.
4. **Academic UI** – Wire Blade views (or SPA screens) to the new student
   and academic controllers to replace JSON placeholders and support
   bilingual UX flows.
5. **Environment Alignment** – Document environment variables, queue
   workers, scheduler tasks, and storage (S3/local) setup.
6. **Data Model Blueprint** – Extend the ERD to cover guardians, staff,
   fees, and transactions in addition to the newly implemented academic
   entities so future modules remain consistent.

Delivering on this roadmap will move the project from its current early
state to a comprehensive, production-grade school management platform.

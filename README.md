<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Belqas School Management System

This repository contains the early foundations of the Belqas School
Management System built with Laravel. The long-term goal is to deliver a
fully featured platform for managing students, staff, academics, fees,
and communications inside a K-12 institution.

The application now ships with a functional academic core that covers
student and teacher registries, classroom management, subjects,
enrolments, assessments, attendance tracking, and grade recording through
a unified JSON API surface. A detailed roadmap that describes the
remaining work and recommended implementation order is available in
[`docs/system_completion_plan.md`](docs/system_completion_plan.md).

### Key Features Implemented

- CRUD APIs for students, teachers, classrooms, subjects, assessments,
  grades, attendance records, and enrolments.
- Dashboard widgets that surface live statistics for academic records
  and highlight the latest student registrations and grade entries.
- Relational data model with migrations that link students, teachers,
  classes, and subjects together to support reporting and automation.

## Local Development

1. Copy `.env.example` to `.env` and update database credentials.
2. Install PHP dependencies using `composer install`.
3. Install JavaScript dependencies using `npm install` or `yarn`.
4. Generate an application key with `php artisan key:generate`.
5. Run database migrations with `php artisan migrate`.
6. Start the development server using `php artisan serve` and run the
   Vite dev server via `npm run dev` for asset compilation.

## Project Roadmap

Refer to [`docs/system_completion_plan.md`](docs/system_completion_plan.md)
for a breakdown of priority modules, cross-cutting concerns, and
delivery milestones required to reach a production-ready release.

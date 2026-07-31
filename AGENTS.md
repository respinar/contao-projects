# AGENTS.md

# Contao Projects Bundle

## Goal

Create a lightweight, native Contao bundle for managing projects and case studies.

The bundle should feel like a natural extension of Contao and follow the same design principles as the Contao News bundle.

Although the initial motivation comes from a real-world project (FooladGharb), the bundle must remain generic and suitable for any industry.

---

## Design Principles

- Keep the bundle simple.
- Follow Contao conventions.
- Prefer proven solutions over clever ones.
- Avoid unnecessary configuration.
- Avoid feature creep.
- Keep the learning curve low.

---

## Core Structure

The bundle consists of:

- Project Archives
- Projects

Every Project belongs to exactly one Project Archive.

Project Archives are responsible for:

- Organization
- Permissions
- Routing
- Configuration

---

## Project Fields

Start with only the fields that are useful for most projects.

Examples:

- Title
- Alias
- Summary
- Description
- Featured image
- Gallery (optional)
- Start date (optional)
- Completion date (optional)
- Featured
- Published
- Sorting

Additional fields should only be introduced when they solve a common use case.

---

## Frontend

Prefer modern Contao Content Elements for frontend output.

Examples:

- Project List
- Project Reader

Frontend Modules may be provided for backward compatibility or integration with existing websites, but Content Elements are the preferred solution for new development.

Avoid duplicating rendering logic.

---

## Categories

Use Contao's Categories where appropriate.

Do not invent new taxonomy systems unless there is a clear benefit.

---

## SEO

Integrate with Contao's built-in SEO features whenever possible.

Avoid custom SEO implementations.

---

## Extensibility

Keep the core bundle focused.

Do not add industry-specific fields or features.

If a feature is useful only for a specific business domain, it should be implemented outside the core bundle.

---

## Coding Standards

- PHP 8.3+
- Strict types
- PSR-12
- Symfony Best Practices
- Constructor Dependency Injection
- Small, focused classes
- Prefer composition over duplication

---

## Guiding Principle

When making design decisions, ask:

> "Would this feel like a natural part of Contao?"

If not, reconsider the implementation.

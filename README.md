# Contao Projects Bundle

A lightweight Contao 5 bundle for managing projects and case studies.

## Features

- **Project Archives** - Organize projects with permissions, overview page, and reader page settings
- **Projects** - Title, alias, author, start/completion dates, featured image, summary, categories, and client
- **Content Elements** - Project List and Project Reader for frontend output
- **Categories** - Integrates with `respinar/contao-company` for categorization
- **Client** - Integrates with `respinar/contao-clients` to link projects to clients
- **SEO** - Meta title, robots, and description fields built-in

## Installation

```bash
composer require respinar/contao-projects-bundle
```

## Configuration

1. Create a Project Archive in the Contao back end
2. Set the overview page (returns to list) and reader page (project detail) in the archive
3. Create projects under the archive
4. Add Project List or Project Reader content elements to your pages

## Usage

### Project List Content Element

Display a list of projects with optional filtering by archive, featured status, or categories.

### Project Reader Content Element

Display a single project with its content elements.

## Requirements

- PHP 8.3+
- Contao 5.x
- `respinar/contao-company` (for categories)
- `respinar/contao-clients` (for client field)

## License

MIT
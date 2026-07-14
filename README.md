# Bruce Flett Portfolio

This project is a Laravel 12 + Vue 3 portfolio experience that renders its content from JSON files instead of a database. The interface is designed around a dark, editorial-style layout with a filtered timeline, hover-reveal cards, and a compact about section.

## How the project is structured

- Backend: Laravel serves the portfolio payload from the JSON files in storage/timeline.
- Frontend: Vue 3 and Pinia fetch the data from /api/timeline and drive the interactive UI.
- Content sources:
  - storage/timeline/events.json
  - storage/timeline/aboutme.json
  - storage/timeline/aboutthissite.json

## Key files

- app/Http/Controllers/TimelineController.php: loads and returns the JSON-backed payload.
- resources/js/stores/timelineStore.js: fetches the payload and prepares it for the Vue components.
- resources/js/components/: Vue components for the about, timeline, and site sections.
- resources/css/app.css: theme tokens and visual styling for the portfolio shell.

## Editing content

Update the JSON files in storage/timeline whenever you want to change the portfolio copy, metadata, or timeline entries.

## Local development

```bash
composer install
npm install
php artisan serve
npm run dev
```

## Verification

```bash
php artisan test --filter=TimelineApiTest
npm run build
```

## Notes

The public portfolio experience is intentionally JSON-driven for simplicity, portability, and easy content updates. The database layer is no longer used for the timeline experience.

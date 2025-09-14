# Copilot Instructions for phptypist.me

## Project Overview
- **phptypist.me** is a PHP library for generating PDF ebooks from Markdown content, using a modular, event-driven architecture.
- The core entry point is `src/Typist.php`, which orchestrates configuration, Markdown conversion, event dispatching, and rendering.
- Content and themes are configured via arrays or YAML, processed by `ConfigurationLoader` (`src/Configuration/ConfigurationLoader.php`).
- Rendering is handled by implementations of `RendererInterface` (default: `MpdfRenderer`).
- Event listeners (see `src/EventListener/`) can modify chapters during generation (e.g., add CSS classes, insert page breaks).

## Key Components
- **src/Typist.php**: Main orchestrator. Use `generate()` to produce output. Add listeners/subscribers for custom processing.
- **src/Configuration/**: Handles config loading/validation. See `ConfigurationLoader` and `Configuration` for supported options.
- **src/Renderer/**: Rendering logic. `MpdfRenderer` is the default, using [mpdf/mpdf].
- **src/EventListener/**: Plug-in listeners for chapter events. Example: `BreakToPageBreakListener` replaces `{BREAK}` tokens with page breaks.
- **src/Model/Chapter.php**: Represents a single chapter, with helpers for metadata and position.

## Developer Workflows
- **Install dependencies:** `composer install`
- **Run tests:** `composer test` (uses PHPUnit, config in `phpunit.xml`)
- **Static analysis:** `composer phpstan`
- **Code style:** `composer phpcs` (PSR-12, see `phpcs.xml`)
- **CI:** `composer ci` runs all checks.
- **Example usage:** See `docs/examples/01-basic.php` for a minimal script.

## Project Conventions
- **Strict types** and PSR-12 code style enforced.
- **Event-driven extension:** Add listeners/subscribers to `Typist` for custom chapter processing.
- **Configuration:** Use array or YAML, see `Configuration` for schema.
- **Content files:** Markdown (`.md`, `.markdown`), configured in `content` array.
- **Themes:** HTML/CSS assets in `assets/data/theme` or similar.
- **Tests:** Extend `Tests\TestCase` for unit/functional tests.

## Integration & External Dependencies
- **mpdf/mpdf**: Required for PDF rendering. Install via Composer.
- **league/commonmark**: Markdown parsing.
- **spatie/commonmark-highlighter**: Code highlighting in Markdown.
- **symfony/config, event-dispatcher, finder, yaml**: Config, events, file discovery.

## Examples
- See `docs/examples/01-basic.php` for a full workflow: config, listeners, generation, and file output.
- Event listeners can be added like:
  ```php
  $typist->addListener(ChapterEvent::class, [new BreakToPageBreakListener(), 'parsed']);
  ```

## Directory Structure
- `src/` — Main library code
- `tests/` — PHPUnit tests
- `assets/data/` — Example themes and content
- `docs/` — Jekyll site (unrelated to PHP library)

## Tips
- Always process configuration before calling `generate()`.
- Listeners can be chained for complex transformations.
- Use `composer ci` before submitting changes.

---
For more, see `README.md` and `docs/examples/01-basic.php`.

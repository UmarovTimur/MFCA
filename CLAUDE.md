# MFCA

WordPress site: a free library of books, audiobooks and videos in the languages of Central Asia and the Caucasus (started 14.11.2022). The repo is the full local dev environment (Docker) plus the custom theme.

## Layout

- `wp-content/themes/sydney.2.13/` — the theme and the only code that ships to production. Stock **Sydney 2.13** (aThemes) with MFCA customisations kept separate (see below). Theme name in `style.css` is "MFCA".
- `docker-compose.yml`, `php.ini`, `setup.sh`, `wp-config.php`, `.env` — local dev only. WordPress 6.8.1, MySQL 5.7, phpMyAdmin. Site on http://localhost:8080, phpMyAdmin on :8081. `docker compose up -d` (run `./setup.sh` first time).
- `wp-content/plugins/*` and `wp-content/uploads/*` are git-ignored. Known plugins: Sonaar MP3 player (audio posts), FIFU (featured image from URL).
- `backups/*.wpress` — All-in-One WP Migration export of the site. Don't edit.

## Content model

- Each **language** is a top-level category whose slug is the language code: `az kz ka kg ce ru tj tk uz ug`. Slugs are site-specific: `ka` = Karakalpak, `kz` Kazakh, `kg` Kyrgyz, `tj` Tajik.
- Each language has subcategories per content type: `{code}-book`, `{code}-audio`, `{code}-video` (`-story` is disabled).
- URLs: categories at `/c/{code}/` and `/c/{code}/{code}-{type}/` (old `/category/...` redirects), posts at `/{code}/{id}/`.
- Admin and comments are Russian/Uzbek-facing; UI strings in templates are hardcoded Russian/English.

## MFCA code (don't mix with stock Sydney code)

All custom PHP is in `inc/mfca/`, loaded from the end of `functions.php`:

| File | Purpose |
| --- | --- |
| `languages.php` | `mfca_languages()` (single source of truth for language codes), `mfca_language_tags()` (BCP 47 / og locale), `mfca_current_language()`, `mfca_translate()` |
| `menu-flags.php` | Flag icon instead of the label (kept as screen-reader text) for language categories in nav menus |
| `post-types-menu.php` | Book / Audio / Video switcher |
| `post-grid.php` | "More from this category" grid under posts |
| `breadcrumbs.php` | Dimox breadcrumbs |
| `redirects.php` | `/category/` → `/c/` |
| `setup.php` | Post formats, FIFU REST meta |
| `assets.php` | Enqueues `css/mfca/*.css` per page type and `js/mfca/main.js` |
| `seo.php` | Meta, canonical, Open Graph, JSON-LD, `<html lang>`, robots, `/llms.txt`; disabled when Yoast / Rank Math / AIOSEO / SEOPress is active |

Templates: `archive.php` (language category → `part-templates/archive-main-category.php`, everything else → `archive-book-list.php`), `single.php`, `post-templates/page_video.php`, `page-templates/home-page.php`, `footer.php`.

Conventions:
- Prefix new functions with `mfca_`. Add new features as a module in `inc/mfca/` instead of growing `functions.php`.
- No inline `<style>`/`<script>` in templates; put them in `css/mfca/` / `js/mfca/` and enqueue in `assets.php`.
- Add a language by editing `mfca_languages()` and `mfca_language_tags()` and adding `images/flags/{code}.png`.
- Leave stock Sydney files alone unless necessary, so upstream updates stay possible.
- Escape all output (`esc_html`, `esc_attr`, `esc_url`).

## Checks

No test suite or build step. Lint PHP with `php -l <file>`. Verify visually on localhost:8080 (home, a post, a language category, a type category, a paginated category).

## Branches and deploy

- `develop` — main working branch (full WordPress + Docker structure).
- `release` — **pushing to it deploys the theme folder to the production FTP server** (`.github/workflows/deploy-theme.yml`, uploads `wp-content/themes/sydney.2.13/`, also deletes removed files there). Never push or merge to `release` without being asked.
- `main` — legacy theme-only layout (`sydney.2.13/` at the root); not the working branch. `restore` is an old experiment.
- Work on feature branches and open PRs into `develop`.

## Known issues

- `.env` and `wp-config.php` (with salts) are committed; values are local-dev only. Do not put real secrets in them.
- Stock Sydney extras (WooCommerce, SiteOrigin, Elementor, theme dashboard, upsell) are still present; not yet confirmed unused on the live site.

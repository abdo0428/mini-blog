# Mini CMS

A lightweight Laravel CMS for blogging with a modern public UI and a clean admin dashboard.

**Features**
- Public blog with fast search, category/tag filters, AJAX pagination, and custom 404.
- Admin dashboard with stats, quick actions, and DataTables filters.
- Posts, categories, and tags management with quick create modals.
- Rich text editor (Quill) without API keys.
- Site settings (name/logo), RSS feed, sitemap, and robots.txt.
- Roles: `admin` and `editor` (editors can manage only their posts).

**Tech Stack**
- Laravel
- Bootstrap 5
- jQuery + DataTables
- SweetAlert2
- Quill Editor

**Requirements**
- PHP 8.1+
- Composer
- Node.js + npm
- MySQL (or compatible database)

**Setup**
1. Install dependencies:
```bash
composer install
npm install
```
2. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```
3. Update `.env` with your database credentials.
4. Run migrations and seed admin:
```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder
```
5. Create storage symlink:
```bash
php artisan storage:link
```
6. Build assets:
```bash
npm run dev
```

**Run**
```bash
php artisan serve
```
Open the site at `http://127.0.0.1:8000`.

**Default Admin Login**
- Email: `admin@test.com`
- Password: `123456789`

**Useful Routes**
- Public index: `/`
- Post: `/post/{slug}`
- Category: `/category/{slug}`
- Tag: `/tag/{slug}`
- RSS: `/feed`
- Sitemap: `/sitemap.xml`
- Robots: `/robots.txt`

**Notes**
- Homepage caching is enabled for 60 seconds and cleared on post create/update/delete.
- Rich text content is stored as HTML. The public post view renders HTML as-is.

**Troubleshooting**
- If images are not showing, run `php artisan storage:link`.
- If styles are missing, make sure `npm run dev` is running or use `npm run build`.




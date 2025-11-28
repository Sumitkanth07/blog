# Simple Blog Website (Laravel)

This is a simple blog application built using **Laravel** where users can register, log in, and create blog posts.  
The website includes Google login, image upload, and a rich text editor to write styled blog content.

---

## ✨ Features
- User Login & Register System
- Google Sign-in
- Create / Edit / Delete Blog Posts
- Upload Featured Images
- Rich Text Editor (TinyMCE)
- Public can read all posts
- Responsive UI (Bootstrap 5)
- SEO friendly slug URLs

---

## 🛠 Technology Used
- Laravel 10+
- PHP 8+
- MySQL
- TinyMCE Editor
- Bootstrap 5
- Laravel Socialite (Google Login)

---

## 🚀 Setup Instructions (Local)
```bash
git clone https://github.com/YOUR_USERNAME/blog-project.git
cd blog-project
composer install
npm install
npm run dev
cp .env.example .env
php artisan key:generate

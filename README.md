# ⚓ IALA Buoyage Quiz — CodeIgniter 4

A full-featured drag-and-drop maritime quiz application built with **CodeIgniter 4**, **Bootstrap 4**, and **CSS animations**, featuring a public quiz website and a complete admin panel — all in one folder.

---

## 📁 Folder Structure

```
quiz-ci4-final/
│
├── app/                          ← CI4 application code
│   ├── Config/
│   │   ├── App.php               ← Base URL, session, CSRF settings
│   │   ├── Paths.php             ← System/app/writable paths
│   │   └── Routes.php            ← All URL routes (website + admin)
│   │
│   ├── Controllers/
│   │   ├── WebsiteController.php ← Public: home, quiz, submit, results, about
│   │   └── AdminController.php   ← Admin: dashboard, questions CRUD, results, settings
│   │
│   ├── Models/
│   │   └── QuizModel.php         ← All data access (JSON file storage; swap for DB)
│   │
│   └── Views/
│       ├── admin/
│       │   ├── layouts/main.php  ← Admin master layout (sidebar + topbar)
│       │   ├── dashboard.php     ← Stats, recent results, quick actions
│       │   ├── questions/
│       │   │   ├── index.php     ← Questions list with toggle/delete
│       │   │   └── form.php      ← Create / Edit question form
│       │   ├── results/
│       │   │   ├── index.php     ← All submissions table
│       │   │   └── detail.php    ← Per-submission breakdown
│       │   └── settings/
│       │       └── index.php     ← Quiz settings form
│       │
│       └── website/
│           ├── home.php          ← Landing page with hero + features
│           ├── quiz.php          ← Drag-and-drop quiz interface
│           ├── results.php       ← Public result page (by token)
│           └── about.php         ← About page
│
├── public/                       ← Web root (point your server here)
│   ├── index.php                 ← CI4 front controller
│   ├── .htaccess                 ← Rewrite rules
│   ├── assets/
│   │   ├── questions/            ← q1.png … q16.png
│   │   └── options/              ← q1_a.png … q16_d.png (64 images)
│   ├── css/
│   │   ├── website.css           ← Public site styles + ocean animation
│   │   └── quiz.css              ← Drag-drop quiz styles
│   └── admin/
│       ├── css/admin.css         ← Admin panel dark theme
│       └── js/admin.js           ← Sidebar toggle, clock, toast
│
├── writable/                     ← CI4 writable (auto-created)
│   ├── session/
│   ├── cache/
│   ├── logs/
│   ├── quiz_data.json            ← Auto-seeded question store
│   ├── quiz_results.json         ← Submission history
│   └── quiz_settings.json        ← Site settings
│
├── composer.json
├── .htaccess                     ← Root redirect → public/
└── env                           ← Environment template
```

---

## 🚀 Quick Setup (XAMPP / WAMP / Laragon)

### 1. Install Dependencies
```bash
cd quiz-ci4-final
composer install
```

### 2. Copy env file
```bash
cp env .env
```

Edit `.env` and set:
```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost/quiz-ci4-final/'
```

### 3. Set Permissions
```bash
chmod -R 777 writable/
```
(Windows: just make sure `writable/` is not read-only)

### 4. Point your server
- **XAMPP**: Drop `quiz-ci4-final/` in `htdocs/` and browse to `http://localhost/quiz-ci4-final/`
- **Laragon**: Same — drop in `www/` folder
- **Nginx**: Point `root` to `quiz-ci4-final/public/`

---

## 🌐 URLs

| URL | Description |
|-----|-------------|
| `http://localhost/quiz-ci4-final/` | Home page |
| `http://localhost/quiz-ci4-final/quiz` | Take the quiz |
| `http://localhost/quiz-ci4-final/about` | About page |
| `http://localhost/quiz-ci4-final/admin` | Admin dashboard |
| `http://localhost/quiz-ci4-final/admin/questions` | Manage questions |
| `http://localhost/quiz-ci4-final/admin/results` | View submissions |
| `http://localhost/quiz-ci4-final/admin/settings` | Quiz settings |

---

## ✨ Features

### Website (Public)
- 🌊 Animated ocean background with layered SVG waves
- 🎯 Drag-and-drop top mark quiz (desktop pointer events)
- 👆 Tap-to-select then tap-to-drop (touch / mobile)
- 🔊 Voice read-aloud of correct answers (Web Speech API)
- 📋 Per-question answer card with highlighted colour words
- ⏱️ Live quiz timer
- 📊 Live stats bar (Total / Answered / Pending / Correct / Wrong)
- 💾 Score submitted via AJAX — generates a shareable result token
- 🎉 Result page with question-by-question breakdown

### Admin Panel
- 📊 Dashboard with animated stat cards and recent attempts
- ❓ Questions CRUD — add, edit, delete, toggle active/hidden
- 👁️ Image path preview in question form
- 📈 Results table with per-submission drill-down
- ⚙️ Settings — title, subtitle, voice, answer card, retry, passing score, theme, background
- 🎨 Dark sidebar layout with animations, live clock, toast notifications
- 📱 Responsive — collapsible sidebar on mobile

---

## 🗄️ Data Storage

By default the app uses **JSON file storage** (no database needed):

| File | Contents |
|------|----------|
| `writable/quiz_data.json` | All 16 questions (auto-seeded on first run) |
| `writable/quiz_results.json` | Submission history |
| `writable/quiz_settings.json` | Site settings |

To switch to **MySQL**, add your DB credentials to `.env` and replace `QuizModel`'s `loadData()`/`saveQuestions()` with CI4's Query Builder.

---

## 🛠️ Customising Questions

1. Add your own images to `public/assets/questions/` and `public/assets/options/`
2. Go to **Admin → Questions → Add Question**
3. Enter the image paths (e.g. `assets/questions/q17.png`) and option paths
4. Mark the correct option and save

---

## 📦 Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | CodeIgniter 4 |
| CSS Framework | Bootstrap 4.6 |
| Icons | Font Awesome 6 |
| Animations | Pure CSS keyframe animations |
| Drag & Drop | Vanilla JS Pointer Events API |
| Voice | Web Speech API (SpeechSynthesisUtterance) |
| Storage | JSON flat files (no DB required) |

---

## 🔒 Adding Authentication

The admin panel currently has no login. To add basic CI4 auth:

1. Create a `LoginController` and `AdminMiddleware`
2. Register the middleware in `app/Config/Filters.php` for the `admin/*` routes
3. Or use a package like `myth/auth` via Composer

---

*Built with CodeIgniter 4 · Bootstrap 4 · CSS Animations*

# ClaudeTest

A retro-style social network page inspired by the classic VKontakte (VK) UI from the 2008–2011 era. Built as a test project with Claude, it replicates the look and feel of an early VK profile page — complete with a live post wall, friends list, photos, and groups sidebar.

🌐 **Live Demo:** [https://claude-test-swart.vercel.app](https://claude-test-swart.vercel.app)

---

## Features

- **Profile page** — avatar, name, city, age, and university info
- **Interactive post wall** — write and display posts stored in a SQLite database
- **CSRF protection** on all form submissions
- **Friends, Photos & Groups** sidebars
- **Like button** with client-side toggle
- Classic VK navigation header with notification badges

## Tech Stack

| Layer     | Technology          |
|-----------|---------------------|
| Backend   | PHP 8 (no framework) |
| Database  | SQLite3 (via PHP's `SQLite3` class) |
| Frontend  | HTML5, CSS3 (vanilla) |
| Hosting   | Vercel |

## Project Structure

```
├── index.php       # Main profile/wall page
├── post_wall.php   # Handles new post form submissions
├── db.php          # Database helper (creates SQLite table on first run)
├── style.css       # All styles — classic VK look
└── wall.db         # SQLite database (auto-created on first request)
```

## Getting Started

### Prerequisites

- PHP 8.0+ with the `sqlite3` extension enabled

### Running Locally

```bash
# Clone the repository
git clone https://github.com/f1oating/ClaudeTest.git
cd ClaudeTest

# Start the built-in PHP development server
php -S localhost:8000
```

Then open [http://localhost:8000](http://localhost:8000) in your browser.

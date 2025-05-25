# Vue + WordPress Headless Assignment

This is a demo project featuring a **Vue 3 + Vite** frontend communicating with a **headless WordPress** backend through a custom REST API.

The WordPress backend is used to authenticate users and provide access to blog posts, while the Vue frontend displays the content and provides a login-protected UI.

---

## 🧩 Features

- ✅ JWT-like authentication via custom token logic
- ✅ Custom REST API endpoints in WordPress
- ✅ Vue 3 + Vite frontend using Naive UI
- ✅ Route protection and role-based UI (Admin/Editor can delete posts)
- ✅ Dockerized setup with WordPress + MySQL + Vite dev server

---

## 🚀 Tech Stack

- **Vue 3 + Vite** frontend (`/frontend`)
- **Naive UI** component library
- **WordPress 6.5** backend with custom plugin (`/backend/jwt-api-plugin`)
- **MySQL 5.7** database
- **Docker Compose** for orchestration

---

## ⚙️ Getting Started

### 1. Clone the Repo

```bash
git clone https://github.com/your-username/hantec-vue-wordpress-assignment.git
cd hantec-vue-wordpress-assignment
```

### 2. Start the Stack

```bash
docker-compose up --build
```

- WordPress Admin: [http://localhost:8080](http://localhost:8080)
- Vue Frontend: [http://localhost:3000](http://localhost:3000)

### 3. Configure WordPress

- Go to [http://localhost:8080](http://localhost:8080)
- Complete the WordPress install wizard (Site Title, Admin user)
- Log in and go to **Settings → Permalinks**
  - Change the permalink structure to **“Post name”**
  - Click **Save Changes**

### 4. Activate the Plugin

- Go to **Plugins** in the admin dashboard
- Activate **JWT API Plugin**

---

## 👤 Users (To Create Manually)

In **WP Admin → Users**, add:

| Username     | Password     | Role        |
|--------------|--------------|-------------|
| `admin`      | `admin`      | Administrator |
| `editor`     | `editor`     | Editor        |
| `subscriber1`| `subscriber1`| Subscriber    |
| `subscriber2`| `subscriber2`| Subscriber    |

---

## 📝 Notes

- The Vue frontend communicates with the WP backend using relative URLs (via Vite proxy).
- Authenticated users get access to post listings and individual posts.
- Admins/Editors see a **Delete** button next to each post.
- The JWT is simulated using secure tokens stored in user meta.

---

## 🔒 Why JWT-like Auth?

This project uses a simplified token mechanism to simulate JWTs, stored in WordPress user meta. In a production setup, you'd replace this with real signed JWTs using a secret or key pair for enhanced security and scalability.

---

## 🎨 Why a UI Framework?

Naive UI was chosen to keep the interface clean and consistent with minimal effort. Using a Vue-based component library allows fast prototyping and consistent UX without manually handling styles or form controls.

---

## 📦 Folder Structure

```
.
├── backend/              # WordPress plugin directory
│   └── jwt-api-plugin/   # Contains jwt-api-plugin.php
├── frontend/             # Vue + Vite frontend app
├── docker-compose.yml
```

---

## 🧼 Optional Cleanup

You can safely delete:

- `hello.php` and `akismet` from `/backend` (plugins installed by default on WP install)

---

---

### 🚀 Potential Improvements

While the current implementation fulfills all assignment requirements, there are a lot of potential improvement were this a real project, for example:

- **Real JWT Support:** Replace the token-based simulation with actual signed JWTs for better security and standardization.
- **User Registration Flow:** Add UI and API support for registering new users directly from the Vue frontend.
- **Token Expiry Handling:** Automatically log out users when their token expires and redirect them to the login screen.
- **Role-Based Routing:** Guard frontend routes more strictly based on user roles, hiding or disabling entire views if unauthorized.
- **Post Creation/Edit:** Extend the UI to allow creating or editing posts for editor/admin roles.
- **Improved Navigation:** Add a global navigation bar with links to the main sections (e.g. Posts, Login, Logout), and breadcrumb support for better UX. Or actual UX for that matter, as it is currently as basic as almost non-existent 😂
- **Related Content & Filters:** Show related articles on single post pages, and allow filtering posts by category, date, or author.
- **WP-CLI Integration:** Bundle WP-CLI in the Docker image and automate WordPress installation and configuration fully.
- **Testing & CI/CD:** Add tests for backend endpoints and Vue components, and set up a basic GitHub Actions pipeline for automated testing.

---

---

## 🧑‍💻 Author

Built by **Stefan Minev** as a technical assignment.

# TechNews - Tech News Platform

> **Live Website:** [technews-j6sa.onrender.com](https://technews-j6sa.onrender.com)

---

## 📱 What is TechNews?

A full-stack content management system (CMS) for publishing and managing technology news articles.

---

## 👤 User Features

### Homepage

![Homepage Screenshot](./screenshots/homepage.png)
_Displays the latest technology news with featured images and categories_

### Article Page

![Article Page Screenshot](./screenshots/article-page.png)
_Article view with formatted content, author information, and publish date_

### Search & Filtering

![Search Feature Screenshot](./screenshots/search.png)
_Search functionality to find articles by keywords, authors, or categories_

---

## 🔧 Admin Control Panel

### Content Management

![Admin Dashboard Screenshot](./screenshots/admin-dashboard.png)
_Admin panel for managing all posts, categories, and users in one place_

### Create New Posts

![Add Post Screenshot](./screenshots/add-post.png)
_Editor for creating new articles with its corresponding information_

### Category Management

![Categories Screenshot](./screenshots/categories.png)
_Organize content with custom categories and corresponding query for News API_

### User Management

![User Management Screenshot](./screenshots/user-management.png)
_Admin tools to manage user accounts, roles, and permissions_

---

## 🔐 Authentication System

### Secure Login

![Login Page Screenshot](./screenshots/login.png)
_Authentication system with session management_

### User Registration

![Registration Screenshot](./screenshots/register.png)
_Registration process_

---

## 🛠️ Technical Highlights

### Architecture

```
Frontend: HTML5, CSS3, Bootstrap
Backend: PHP 7.4+
Database: MySQL
Hosting: Render & Aiven (Production)
```

### Key Technical Features

- **RESTful Architecture** - Clean separation of client and server
- **Session Management** - Secure user authentication
- **API Integration** - NewsAPI for automated content updates
- **SMS Notifications** - ClickSend integration for user alerts
- **Database Optimization** - Efficient queries with pagination

### Security Implementations

- SQL Injection Protection (mysqli_real_escape_string)
- Session Security (proper session handling)
- Password Hashing (bcrypt)
- Role-Based Access Control (Admin/User separation)
- Input Validation & Sanitization

### Technical Achievement

- Built complete CRUD operations for 3 data models (Posts, Categories, Users)
- Integrated 2 third-party APIs (NewsAPI, ClickSend)
- Deployed production-ready application with SSL

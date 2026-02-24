# TechNews - Modern Tech News Platform

> **Live Demo:** [technews-j6sa.onrender.com](https://technews-j6sa.onrender.com)

---

## 📱 What is TechNews?

A full-stack content management system (CMS) for publishing and managing technology news articles. Built with a focus on user experience, real-time updates, and mobile responsiveness.

---

## 🖼️ Platform Overview

### Homepage - Latest Tech Stories
![Homepage Screenshot](./screenshots/homepage.png)
*Clean, modern interface displaying the latest technology news with featured images, categories, and easy navigation*

---

### Article Page - Rich Content Display
![Article Page Screenshot](./screenshots/article-page.png)
*Full article view with formatted content, author information, publish date, and related articles*

---

## 👤 User Features

### User Dashboard
![User Dashboard Screenshot](./screenshots/user-dashboard.png)
*Personalized dashboard where users can browse articles, search content, and filter by categories*

### Smart Search & Filtering
![Search Feature Screenshot](./screenshots/search.png)
*Powerful search functionality to find articles by keywords, authors, or categories*

---

## 🔧 Admin Control Panel

### Content Management
![Admin Dashboard Screenshot](./screenshots/admin-dashboard.png)
*Comprehensive admin panel for managing all posts, categories, and users from one place*

### Create New Posts
![Add Post Screenshot](./screenshots/add-post.png)
*Intuitive editor for creating new articles with:*
- Title and content editor
- Image URL integration
- Category assignment
- Author attribution
- Scheduled publishing

### Category Management
![Categories Screenshot](./screenshots/categories.png)
*Organize content with custom categories - create, edit, or delete as needed*

### User Management
![User Management Screenshot](./screenshots/user-management.png)
*Admin tools to manage user accounts, roles, and permissions*

---

## 🔐 Authentication System

### Secure Login
![Login Page Screenshot](./screenshots/login.png)
*Secure authentication system with session management*

### User Registration
![Registration Screenshot](./screenshots/register.png)
*Simple registration process with phone number verification*

---


## 🛠️ Technical Highlights

### Architecture
```
Frontend: HTML5, CSS3, Bootstrap, JavaScript
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

---

## 📝 How to Use (Quick Guide)

### For Administrators:
1. Login with admin credentials
2. Click "Add Post" to create new articles
3. Fill in title, content, select category, add image URL
4. Publish immediately or schedule for later
5. Manage users and categories from the admin panel

### For Readers:
1. Visit the homepage to see latest articles
2. Use search bar to find specific topics
3. Click categories to browse by subject
4. Click any article to read full content

---

## 📸 Instructions for Adding Screenshots

To complete this README, please add screenshots to a `screenshots` folder:

1. **homepage.png** - Main landing page showing article list
2. **article-page.png** - Full article view
3. **user-dashboard.png** - User's main interface
4. **search.png** - Search functionality in action
5. **admin-dashboard.png** - Admin panel overview
6. **add-post.png** - Create new post interface
7. **categories.png** - Category management
8. **user-management.png** - User administration
9. **login.png** - Login screen
10. **register.png** - Registration form

### How to Capture Screenshots:
1. Visit your live site: https://technews-j6sa.onrender.com
2. Use browser screenshot tools or Snipping Tool (Windows) / Screenshot (Mac)
3. Capture clean, full-page screenshots
4. Save with the exact filenames listed above
5. Upload to `screenshots/` folder in your repository
6. Screenshots will automatically display in this README

---
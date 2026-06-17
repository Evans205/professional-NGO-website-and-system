

> **A modern, dynamic website for an NGO focused on agricultural transformation, youth empowerment, and community development in Africa.**

---

## 📌 Project Overview

It is a fully functional, responsive website built for an NGO that empowers rural communities through sustainable agriculture, agribusiness training, and youth entrepreneurship. The site features a **dynamic news/stories management system**, **role‑based admin dashboard**, **multi‑media carousels**, and a **donation portal** – all styled with a modern, professional design to attract donors, partners, and youth participants.

---

## ✨ Key Features

### 🔹 Public Frontend
- **Homepage** – Hero slider, mission statement, core values, focus areas, team overview, testimonials, and a dynamic "Latest Updates" section showing the 3 most recent news/stories with image/video carousels.
- **About Page** – Extracted from the organization’s constitution, showcasing mission, objectives, governance structure, and SDG alignment.
- **Team Page** – Detailed profiles of board members and staff, with expandable modals for full bios, education, and expertise.
- **News & Stories (whasnew.php)** – Full grid of all published posts, each with a media carousel (images/videos), category badges, share buttons, and "Read Full Story" links.
- **Single Post (post.php)** – Dedicated page for each news/story with full content, gallery, and social share buttons.
- **Donate Page** – Clear call‑to‑action with M‑Pesa Paybill (522533 / 8099396) and bank transfer details, plus impact statements to encourage giving.
- **Contact Section** – Integrated contact form and organisation details (phone, email, address).

### 🔹 Admin Dashboard (Role‑Based)
- **Secure Login** – Password‑protected access with session management.
- **Role‑Based Access** – Currently supports `web_support` (Web & Systems Lead) with full CRUD permissions. Easily extendable for Chairperson, Secretary, Treasurer, etc.
- **Dashboard** – Quick stats (total posts, published, drafts) and action buttons.
- **News/Story Manager** – Add, edit, delete, and preview posts.
  - Choose type: **News** or **Story**
  - Upload up to **4 images** per post (with captions)
  - Support for **video** files (MP4, WebM, etc.)
  - Set status: **Draft** or **Published**
  - **Preview** before publishing
  - Inline **"Read More"** toggle on the homepage for excerpts
- **Image Upload** – Automatic file handling with organised storage.

### 🔹 Technical Highlights
- **PHP + MySQL** – Dynamic content with secure database queries.
- **Tailwind CSS** – Utility‑first framework for rapid, responsive UI.
- **Font Awesome Icons** – Consistent, professional iconography.
- **Carousel System** – Auto‑playing, touch‑enabled, multi‑media sliders on posts.
- **Smooth Animations** – Fade‑in, scale, and scroll‑reveal effects.
- **Responsive Design** – Fully optimised for desktop, tablet, and mobile.
- **SEO‑Friendly** – Semantic HTML, meta descriptions, and clean URLs.

---

## 🛠️ Technology Stack

| Component | Technology |
|-----------|------------|
| Backend | PHP 7.4+ |
| Database | MySQL 5.7+ |
| Frontend | HTML5, CSS3, Tailwind CSS 3.x |
| JavaScript | Vanilla JS (no external libraries) |
| Icons | Font Awesome 6 |
| Fonts | Google Fonts (Inter) |
| Server | Apache / Nginx (any PHP‑compatible server) |



## 🚀 Installation & Setup (Step‑by‑Step)

### 1. Prerequisites
- Web server with PHP 7.4+ and MySQL 5.7+
- phpMyAdmin or MySQL CLI for database management
- FTP/SFTP access or file manager to upload files

### 2. Clone / Download the Project
Place all files in your web root (e.g., `public_html/`, `htdocs/`, or a subfolder).

### 3. Create the Database
- Open phpMyAdmin or MySQL CLI.
- Create a new database (e.g., `growafrica_db`).
- Import the schema from `/sql/schema.sql` (provided below).


### Adding a News / Story
1. Log in to the admin panel (`admin/login.php`).
2. Click **"Add New"** under News & Stories.
3. Fill in:
   - **Title** – The headline.
   - **Type** – Choose "News" or "Story".
   - **Excerpt** – Short summary (appears on cards).
   - **Full Content** – The complete article (HTML supported).
   - **Status** – Draft (hidden) or Published (visible).
   - **Images** – Up to 4 files; captions optional.
4. Click **Save**.
5. Use **Preview** to see how it will look on the public site.
6. To publish, edit and change status to "Published".

### Editing / Deleting
- From the News Manager list, click **Edit** to modify any post.
- Click **Delete** to permanently remove a post (images are also deleted from the server).

### Preview
- Click **Preview** to open a clean, front‑end view of the post – perfect for checking formatting before publishing.

---

## 🌐 Public Pages Navigation

| Page | File | Purpose |
|------|------|---------|
| Home | `index.php` | Landing page with hero slider, mission, pillars, values, latest news, team, testimonials, contact |
| About | `about.php` | Organisation mission, objectives, SDG alignment, governance structure (extracted from constitution) |
| Team | `team.php` | Board members and staff with detailed modals (bios, education, expertise) |
| News | `whasnew.php` | Complete grid of all published news and stories with media carousels |
| Post | `post.php` | Single post with full content, gallery, and share buttons |
| Donate | `donate.php` | Donation call‑to‑action with Paybill (522533 / 8099396) and bank details |
| Contact | (part of home) | Contact form and organisation details |

---

## 📱 Responsive Design

The website is fully responsive and works seamlessly on:
- **Desktop** (≥ 1024px)
- **Tablet** (768px – 1023px)
- **Mobile** (≤ 767px)

Tailwind CSS breakpoints ensure optimal layout, typography, and touch interactions on all devices.

---

## 🔧 Customisation Tips

### Changing the Admin Password
- In `admin/login.php`, locate the password check:
  ```php
  $admin_password = 'GrowAfrica2025'; // Change this to your own password
  ```
- Or, if using the database method (recommended), update the `password_hash` in the `admin_users` table using a bcrypt generator.

### Changing the Logo
- Replace `images/logo.png` with your own file (keep the same filename for consistency).
- Update fallback text in `<img>` tags if needed.

### Modifying Colours
- The primary brand colour is Amber (`#d97706` / `#f59e0b`).
- To change, search for `amber` in the CSS classes and replace with your preferred Tailwind colour.

### Adding More Admin Roles
- Insert new roles into `admin_users.role` (e.g., `chairperson`, `secretary`).
- In `admin/index.php`, add conditional menu items based on `$_SESSION['role']`.

### Extending the News Manager
- The single‑file `admin/news.php` handles all CRUD. You can duplicate it for other content types (e.g., events, projects).

---

## 🐛 Troubleshooting

### "Database connection failed"
- Check your credentials in `db.php` (both root and admin folders).
- Ensure MySQL is running and the database name is correct.

### Images not uploading
- Verify that `admin/uploads/` exists and has write permissions (CHMOD 755 or 777).
- Check PHP `upload_max_filesize` and `post_max_size` in `php.ini` – increase if uploading large files.

### "404 Not Found" on links
- If you installed the site in a subfolder (e.g., `http://yoursite.com/growafrica/`), ensure all links use relative paths correctly.
- Update `href` and `src` attributes to include the subfolder if needed.

### Login fails
- Use the default credentials: username `webadmin`, password `admin123`.
- If you changed the password, ensure the bcrypt hash is valid.
- Check that `session_start()` is working and sessions are enabled.

### Carousel not auto‑playing
- The carousel uses `setInterval`; ensure no JavaScript errors are breaking the script.
- Check the browser console for any error messages.

---

## 🤝 Contributing

This project is maintained by the Grow Africa Futures team. To suggest improvements or report issues:

1. Fork the repository (if using version control).
2. Make your changes in a new branch.
3. Submit a pull request with a clear description of changes.

---

## 📄 License

This project is proprietary to **Grow Africa Futures** and is intended for internal use. Redistribution or commercial use requires explicit permission from the organisation.

---

## 📞 Support

For technical support, questions, or partnership inquiries:
0748945594


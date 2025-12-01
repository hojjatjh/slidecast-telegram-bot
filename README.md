# SlideCastBot – Telegram Slide Presentation Bot
A powerful and fully automated slide‑based presentation system for Telegram channels, designed for:

- Create multi-slide presentations
- Navigate between slides with inline buttons
- Store slides and metadata in MySQL
- Edit slides after publishing

SlideCastBot allows administrators to upload slides, which are automatically formatted into a clean, interactive, paginated slideshow inside a Telegram channel.


## 🚀Features
⭐️ Presentation Management:

- Create unlimited slide presentations
- Automatic creation of beautiful inline slide navigation
- Next / Previous buttons
- Delete presentation
- Multi‑presentation admin panel
- Pagination support

📤 Slide Upload System:

- Upload slides one-by-one
- Automatically numbered
- Auto-save using MariaDB / MySQL
- Supports Telegram file_id for instant loading

🌐 Multi‑Language System :

- Persian (FA)
- English (EN)
- Arabic (AR)

🛡 Security :

- Telegram IP validation
- Admin whitelist
- Input validation
- Protected callback routing

🔄 Fully Dynamic Message Editing :

- Slides switch using editMessageMedia
- Captions auto-update to display progress
- Smart keyboard states: Start, Middle, End(with delete button)

⚡️ Additional Cool Features :

- Auto-generated slugs
- Beautiful inline UI
- Metadata saving
- Full logging of user steps

## 📦 Installation
#### 1️⃣ Upload Bot Files
Place all files on your server/host.
#### 2️⃣ Create a database
Create a **MariaDB**/**MySQL** database.
#### 3️⃣ Information recording
Place the robot token, database information, and admin numeric ID in the **core/config.php** file.
#### 4️⃣ Set Webhook
Run:
```bash
https://api.telegram.org/bot<API_TOKEN>/setWebhook?url=https://YOURDOMAIN/bot.php
```
⚠️ Webhook must be set on the **bot.php** file.
## 🧪 Usage
- Send **/start** to bot
- Choose language
- Open Admin Panel
- Create a new presentation
- Upload slides
- Use **/finish**
- Bot automatically posts slideshow in target channel
## 🗃 Database Structure (presentation)

| Column             | Description                                                                |
| ----------------- | ------------------------------------------------------------------ |
| presentation_id | Unique ID |
| slug | Slug |
| title | Title of presentation |
| channel_id | Target channel |
| message_id | First slide post |
| slide_count | Total slides |
| created_by | Admin user |
| created_at | Timestamp |

## 🎯 TODO (Future Features)
- Audio narration for each slide
- Auto‑publish scheduler
- PDF → slide converter
- Cloud backup for slides

## 📜 License
This project is licensed under the MIT License.
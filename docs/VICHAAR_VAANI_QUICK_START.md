# Vichaar Vaani API - Quick Reference

## 🚀 Setup (5 minutes)

```bash
# 1. Install
composer install

# 2. Configure
cp .env.example .env
php artisan key:generate

# Edit .env with your DB credentials
nano .env

# 3. Migrate
php artisan migrate

# 4. Run
php artisan serve
```

**API Base URL**: `http://localhost:8000/api`

---

## 📋 All 27 Endpoints

| # | Method | Endpoint | Auth | Purpose |
|----|--------|----------|------|---------|
| 1 | POST | `/auth/send-otp` | ❌ | Send OTP |
| 2 | POST | `/auth/verify-otp` | ❌ | Verify OTP |
| 3 | POST | `/auth/complete-profile` | ✅ | Complete profile |
| 4 | GET | `/user/profile` | ✅ | Get own profile |
| 5 | PUT | `/user/profile` | ✅ | Update profile |
| 6 | GET | `/feed/home` | ✅ | Get feed |
| 7 | POST | `/posts/create` | ✅ | Create post |
| 8 | POST | `/posts/{id}/like` | ✅ | Like post |
| 9 | POST | `/posts/{id}/bookmark` | ✅ | Bookmark post |
| 10 | POST | `/posts/{id}/share` | ✅ | Share post |
| 11 | GET | `/posts/{id}/comments` | ✅ | Get comments |
| 12 | POST | `/posts/{id}/comments` | ✅ | Create comment |
| 13 | DELETE | `/comments/{id}` | ✅ | Delete comment |
| 14 | GET | `/notifications` | ✅ | Get notifications |
| 15 | PUT | `/notifications/{id}/read` | ✅ | Mark as read |
| 16 | PUT | `/notifications/read-all` | ✅ | Mark all read |
| 17 | GET | `/messages/conversations` | ✅ | Get conversations |
| 18 | GET | `/messages/conversations/{id}` | ✅ | Get messages |
| 19 | POST | `/messages/send` | ✅ | Send message |
| 20 | PUT | `/messages/conversations/{id}/read` | ✅ | Mark read |
| 21 | GET | `/search` | ✅ | Search |
| 22 | POST | `/users/{id}/follow` | ✅ | Follow user |
| 23 | DELETE | `/users/{id}/follow` | ✅ | Unfollow user |
| 24 | GET | `/users/{id}/followers` | ✅ | Get followers |
| 25 | GET | `/users/{id}/following` | ✅ | Get following |
| 26 | POST | `/auth/refresh-token` | ❌ | Refresh token |
| 27 | POST | `/auth/logout` | ✅ | Logout |

---

## 🔐 Authentication

### Get Token (3 steps)

```bash
# 1. Send OTP
curl -X POST http://localhost:8000/api/auth/send-otp \
  -H "Content-Type: application/json" \
  -d '{"mobile_number":"9451282000","country_code":"+91"}'

# Response: save "otp_id"

# 2. Verify OTP (check logs for OTP in dev mode)
curl -X POST http://localhost:8000/api/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d '{"mobile_number":"9451282000","country_code":"+91","otp":"123456","otp_id":"saved_otp_id"}'

# Response: For new user, save "temp_token". For existing, save "access_token" & "refresh_token"

# 3. Complete Profile (new users only)
curl -X POST http://localhost:8000/api/auth/complete-profile \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer temp_token" \
  -d '{"full_name":"John","username":"john123","bio":"Hi!"}'

# Response: save "access_token" & "refresh_token"
```

### Use Token

```bash
curl -X GET http://localhost:8000/api/user/profile \
  -H "Authorization: Bearer access_token_here"
```

### Refresh Token

```bash
curl -X POST http://localhost:8000/api/auth/refresh-token \
  -H "Content-Type: application/json" \
  -d '{"refresh_token":"refresh_token_here"}'
```

---

## 📱 Most Used Endpoints

### Create Post
```bash
curl -X POST http://localhost:8000/api/posts/create \
  -H "Authorization: Bearer token" \
  -d '{"content":"Hello World!","visibility":"public"}'
```

### Get Feed
```bash
curl "http://localhost:8000/api/feed/home?feed_type=for_you" \
  -H "Authorization: Bearer token"
```

### Like/Comment
```bash
# Like
curl -X POST http://localhost:8000/api/posts/{id}/like \
  -H "Authorization: Bearer token"

# Comment
curl -X POST http://localhost:8000/api/posts/{id}/comments \
  -H "Authorization: Bearer token" \
  -d '{"content":"Nice!"}'
```

### Follow User
```bash
curl -X POST http://localhost:8000/api/users/{id}/follow \
  -H "Authorization: Bearer token"
```

### Send Message
```bash
curl -X POST http://localhost:8000/api/messages/send \
  -H "Authorization: Bearer token" \
  -d '{"receiver_id":"user_id","content":"Hi!","type":"text"}'
```

### Search
```bash
curl "http://localhost:8000/api/search?query=krishna&type=all" \
  -H "Authorization: Bearer token"
```

---

## 🗄️ Database Tables

```
users (id, full_name, username, email, phone_number, bio, ...)
posts (id, user_id, content, media_urls, visibility, ...)
comments (id, post_id, user_id, parent_comment_id, content, ...)
notifications (id, user_id, actor_id, type, post_id, ...)
messages (id, sender_id, receiver_id, conversation_id, content, ...)
follows (id, follower_id, following_id)
likes (id, user_id, post_id, comment_id)
bookmarks (id, user_id, post_id)
otps (id, phone_number, otp, expires_at, ...)
```

---

## ⚙️ Key Config (.env)

```env
# Database
DB_HOST=127.0.0.1
DB_NAME=vichaar_vaani

# JWT
JWT_SECRET=your_secret_key
JWT_ACCESS_EXPIRY=15m

# OTP
OTP_EXPIRY_MINUTES=5
OTP_MAX_ATTEMPTS=5

# SMS (optional)
TWILIO_ACCOUNT_SID=xxx
TWILIO_AUTH_TOKEN=xxx
```

---

## 🧪 Test Data

**Test Phone**: 9451282000  
**Test OTP**: 123456 (in debug mode, check logs)  
**Test Username**: johndoe123  
**Test Password**: (none - uses OTP)

---

## 📂 File Locations

| File | Purpose |
|------|---------|
| `app/Http/Controllers/Api/*` | API logic |
| `app/Models/*` | Database models |
| `database/migrations/*` | Schemas |
| `routes/api.php` | Routes |
| `app/Http/Middleware/JwtMiddleware.php` | Auth |

---

## 🆘 Common Errors

| Error | Solution |
|-------|----------|
| 404 endpoint | Check route in routes/api.php |
| Invalid token | Add Authorization header |
| Table not found | Run php artisan migrate |
| OTP mismatch | Check logs for actual OTP |
| Username taken | Use unique username |

---

## 📖 Full Docs

- `VICHAAR_VAANI_API_GUIDE.md` - Complete guide
- `VICHAAR_VAANI_API_EXAMPLES.md` - All 27 examples
- `docs/Vichaar_Vaani_API.md` - Specification
- `postman-collection.json` - Postman import

---

## 🎯 Next Steps

1. ✅ Run migrations
2. ✅ Start server
3. ✅ Test with Postman
4. 🔲 Add WebSocket
5. 🔲 Deploy to production

---

**Questions?** Check the full documentation files.

**Happy Coding! 🚀**

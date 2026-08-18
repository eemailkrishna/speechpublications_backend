# Vichaar Vaani — API Documentation

App Name: Vichaar Vaani
Tagline: Share Your Thoughts
Primary Color: `#7B3FF2` (Purple)
Country Code: `+91` (India)

---

**Purpose**

This document is the canonical API specification for the Vichaar Vaani mobile app backend. It contains endpoints, request/response examples, validation rules, database schema requirements, environment variables, error format, and Postman collection structure.

Audience: backend engineers, mobile/frontend clients, QA, and API integrators.

---

**Version**: 1.0

**Base URL**: `https://api.vichaarvaani.app` (replace for staging/dev)

Authentication: JWT (Bearer). Temporary token used during initial OTP flow for new users.

Rate limiting: endpoints should enforce limits; see environment variables section.


**1. Authentication Flow**

**1.1 Send OTP**

- Endpoint: `POST /api/auth/send-otp`
- Purpose: Generate and send an OTP to the given mobile number.

Request body (JSON):

```json
{
  "mobile_number": "9451282000",
  "country_code": "+91"
}
```

Response (success):

```json
{
  "success": true,
  "message": "OTP sent successfully",
  "otp_id": "unique_otp_id",
  "expires_in": 300
}
```

Validation rules:
- `mobile_number`: required, exactly 10 digits (India). Accept only numeric strings.
- `country_code`: required (e.g., `+91`).
- Rate limit: max 3 OTPs per mobile number per hour.

Error examples:

```json
{
  "success": false,
  "error": {
    "code": "RATE_LIMIT_EXCEEDED",
    "message": "Too many OTP requests. Try after 1 hour.",
    "field": "mobile_number"
  }
}
```

---

**1.2 Verify OTP**

- Endpoint: `POST /api/auth/verify-otp`
- Purpose: Verify the OTP user entered.

Request body (JSON):

```json
{
  "mobile_number": "9451282000",
  "country_code": "+91",
  "otp": "123456",
  "otp_id": "unique_otp_id"
}
```

Response (new user):

```json
{
  "success": true,
  "is_new_user": true,
  "temp_token": "temporary_auth_token",
  "message": "OTP verified. Please complete profile setup"
}
```

Response (existing user):

```json
{
  "success": true,
  "is_new_user": false,
  "access_token": "jwt_access_token",
  "refresh_token": "jwt_refresh_token",
  "user": { ... }
}
```

Validation rules & behaviour:
- `otp`: required, 6 digits.
- OTP expires after 5 minutes (`OTP_EXPIRY_MINUTES` env var).
- Maximum 5 wrong attempts (`OTP_MAX_ATTEMPTS` env var); after that, block verification for a cooldown period and return `RATE_LIMIT_EXCEEDED` or a specific `OTP_BLOCKED` error.
- Client should display a countdown (e.g., "Resend OTP in 29s").

Error codes:
- `INVALID_OTP` — incorrect OTP
- `OTP_EXPIRED` — OTP expired
- `RATE_LIMIT_EXCEEDED` — too many attempts/requests

---

**1.3 Complete Profile Setup (New Users)**

- Endpoint: `POST /api/auth/complete-profile`
- Header: `Authorization: Bearer {temp_token}`
- Purpose: Collect name, username and optional profile photo for new users.

Request body (JSON):

```json
{
  "full_name": "John Doe",
  "username": "johndoe123",
  "bio": "Digital creator | Tech enthusiast",
  "profile_photo": "base64_encoded_image_or_url"
}
```

Response (success):

```json
{
  "success": true,
  "access_token": "jwt_access_token",
  "refresh_token": "jwt_refresh_token",
  "user": { ... }
}
```

Validation rules:
- `full_name`: required, 2–50 chars
- `username`: required, 3–30 chars, lowercase, alphanumeric and underscore only, unique
- `bio`: optional, max 150 chars
- `profile_photo`: optional (base64 or URL). If base64, validate file type and size server-side.

Error example for username collision:

```json
{
  "success": false,
  "error": {
    "code": "USERNAME_TAKEN",
    "message": "Username johndoe123 is already in use",
    "field": "username"
  }
}
```

---

**2. User Profile Management**

**2.1 Get User Profile (Own)**

- Endpoint: `GET /api/user/profile`
- Header: `Authorization: Bearer {access_token}`

Response:

```json
{
  "success": true,
  "user": {
    "id": "user_id",
    "full_name": "Your Name",
    "username": "username",
    "email": "your.email@example.com",
    "bio": "...",
    "phone_number": "+919451282000",
    "profile_photo": "url_to_photo",
    "followers_count": 1200,
    "following_count": 340,
    "posts_count": 24,
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

**2.2 Update Profile**

- Endpoint: `PUT /api/user/profile`
- Header: `Authorization: Bearer {access_token}`

Request body (JSON):

```json
{
  "full_name": "Your Name",
  "username": "username",
  "bio": "...",
  "email": "your.email@example.com",
  "phone_number": "+919451282000",
  "profile_photo": "base64_encoded_image_or_url"
}
```

Response (success):

```json
{
  "success": true,
  "message": "Profile updated successfully",
  "user": { ... }
}
```

Notes:
- `phone_number` is read-only.
- Validate `email` for format; if changed, require a verification flow (optional).

---

**3. Home Feed**

**3.1 Get Home Feed**

- Endpoint: `GET /api/feed/home`
- Header: `Authorization: Bearer {access_token}`
- Query params:
  - `page` (default `1`)
  - `limit` (default `20`)
  - `feed_type`: `for_you|following|trending` (default `for_you`)

Response:

```json
{
  "success": true,
  "posts": [ /* array of post objects */ ],
  "pagination": {
    "current_page": 1,
    "total_pages": 10,
    "has_next": true
  }
}
```

Feed behaviour:
- `for_you` — personalized ranking algorithm
- `following` — chronological or ranked posts from followed users
- `trending` — top posts by engagement metrics

Pagination: always include page and limit; expose cursor-based pagination later for scale.

---

**4. Post Management**

**4.1 Create Post**

- Endpoint: `POST /api/posts/create`
- Header: `Authorization: Bearer {access_token}`
- Content-Type: `multipart/form-data`

Form data fields:
- `content` (string)
- `media_type`: `photo|video|camera|location`
- `media[]`: file uploads (0..N)
- `location`: optional `lat,lng`
- `visibility`: `public|private`

Success response:

```json
{
  "success": true,
  "message": "Post created successfully",
  "post": { /* post object */ }
}
```

Server-side notes:
- Validate files: allowed types and max file size (`MAX_FILE_SIZE` env var).
- For videos: generate thumbnail and store metadata.
- Store media urls in `media_urls` JSON array on the `posts` table.

**4.2 Toggle Like**

- Endpoint: `POST /api/posts/{post_id}/like`
- Header: `Authorization`

Response:

```json
{
  "success": true,
  "is_liked": true,
  "likes_count": 246
}
```

**4.3 Toggle Bookmark**

- Endpoint: `POST /api/posts/{post_id}/bookmark`

Response:

```json
{
  "success": true,
  "is_bookmarked": true
}
```

**4.4 Share Post**

- Endpoint: `POST /api/posts/{post_id}/share`

Response:

```json
{
  "success": true,
  "shares_count": 19,
  "share_url": "https://vichaarvaani.app/post/{post_id}"
}
```

---

**5. Comments System**

**5.1 Get Post Comments**

- Endpoint: `GET /api/posts/{post_id}/comments`
- Query params: `page`, `limit`

Response:

```json
{
  "success": true,
  "comments": [ /* comments */ ],
  "pagination": { "current_page":1, "total_comments":4 }
}
```

**5.2 Create Comment**

- Endpoint: `POST /api/posts/{post_id}/comments`
- Body (JSON):

```json
{
  "content": "Great post! 👍",
  "parent_comment_id": null
}
```

Response (success): returns created comment object.

**5.3 Delete Comment**

- Endpoint: `DELETE /api/comments/{comment_id}`

Response:

```json
{ "success": true, "message": "Comment deleted successfully" }
```

---

**6. Notifications**

**6.1 Get Notifications**

- Endpoint: `GET /api/notifications`
- Query params: `page`, `limit`, `filter` (`all|likes|comments|followers`)

Response includes `unread_count` and list of notification objects.

**6.2 Mark Notification as Read**

- Endpoint: `PUT /api/notifications/{notification_id}/read`

Response:

```json
{ "success": true, "message": "Notification marked as read" }
```

**6.3 Mark All as Read**

- Endpoint: `PUT /api/notifications/read-all`

Response: success message.

---

**7. Messaging System**

**7.1 Get Conversations**

- Endpoint: `GET /api/messages/conversations`
- Params: `page`, `limit`

Response: list of conversations with `last_message`, `unread_count` and user preview.

**7.2 Get Messages for a Conversation**

- Endpoint: `GET /api/messages/conversations/{conversation_id}`
- Params: `page`, `limit`

Response: conversation messages (ordered oldest → newest or vice-versa depending on client preference). Limit default: 50.

**7.3 Send Message**

- Endpoint: `POST /api/messages/send`

Request body:

```json
{
  "receiver_id": "user_id",
  "content": "Hello!",
  "type": "text"
}
```

Response: returns the saved message object.

**7.4 Mark Messages as Read**

- Endpoint: `PUT /api/messages/conversations/{conversation_id}/read`

Response: success message.

Real-time considerations: implement socket (WebSocket/Socket.IO) or Redis + pub/sub for live messages and notifications. Store read receipts and online presence in Redis.

---

**8. Search System**

- Endpoint: `GET /api/search`
- Query params:
  - `query` (required)
  - `type`: `all|users|posts|hashtags`
  - `page`, `limit`

Response: grouped `results` object with `users`, `posts`, and `hashtags` arrays.

Implementations notes: use text indexing (Postgres full-text, ElasticSearch, or Algolia) for performance.

---

**9. Follow System**

**9.1 Follow User**
- `POST /api/users/{user_id}/follow` → returns `is_following` and `followers_count`

**9.2 Unfollow User**
- `DELETE /api/users/{user_id}/follow`

**9.3 Get Followers**
- `GET /api/users/{user_id}/followers` (paginated)

**9.4 Get Following**
- `GET /api/users/{user_id}/following` (paginated)

---

**10. Authentication Tokens**

**Refresh Token**
- Endpoint: `POST /api/auth/refresh-token`

Request:

```json
{ "refresh_token": "jwt_refresh_token" }
```

Response:

```json
{ "success": true, "access_token": "new_jwt_access_token", "refresh_token": "new_jwt_refresh_token" }
```

**Logout**
- Endpoint: `POST /api/auth/logout`
- Header: `Authorization: Bearer {access_token}`

Response: success message.

Token policy recommendation:
- Access token expiry: 15 minutes
- Refresh token expiry: 7 days
- Revoke refresh tokens on logout and rotate refresh token on use.

---

**Error Response Format**

All errors use a consistent structure:

```json
{
  "success": false,
  "error": {
    "code": "ERROR_CODE",
    "message": "Human readable error message",
    "field": "field_name"
  }
}
```

Common error codes:
- `INVALID_OTP`, `OTP_EXPIRED`, `INVALID_TOKEN`, `USER_NOT_FOUND`, `USERNAME_TAKEN`, `UNAUTHORIZED`, `VALIDATION_ERROR`, `RATE_LIMIT_EXCEEDED`.

---

**Database Schema Requirements (DDL outline)**

Below are the key tables and fields required. Use UUIDs as primary keys.

**Users table** (Postgres example):

```sql
CREATE TABLE users (
  id UUID PRIMARY KEY,
  full_name VARCHAR(50) NOT NULL,
  username VARCHAR(30) UNIQUE NOT NULL,
  email VARCHAR(100) UNIQUE,
  phone_number VARCHAR(15) UNIQUE NOT NULL,
  country_code VARCHAR(5) NOT NULL,
  bio TEXT,
  profile_photo TEXT,
  followers_count INT DEFAULT 0,
  following_count INT DEFAULT 0,
  posts_count INT DEFAULT 0,
  is_verified BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);
```

**Posts table**:

```sql
CREATE TABLE posts (
  id UUID PRIMARY KEY,
  user_id UUID REFERENCES users(id) ON DELETE CASCADE,
  content TEXT,
  media_urls JSONB,
  location VARCHAR(255),
  visibility VARCHAR(10) CHECK (visibility IN ('public','private')),
  likes_count INT DEFAULT 0,
  comments_count INT DEFAULT 0,
  shares_count INT DEFAULT 0,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);
```

**Comments table**:

```sql
CREATE TABLE comments (
  id UUID PRIMARY KEY,
  post_id UUID REFERENCES posts(id) ON DELETE CASCADE,
  user_id UUID REFERENCES users(id) ON DELETE CASCADE,
  parent_comment_id UUID,
  content TEXT,
  likes_count INT DEFAULT 0,
  replies_count INT DEFAULT 0,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);
```

**Notifications table**:

```sql
CREATE TABLE notifications (
  id UUID PRIMARY KEY,
  user_id UUID REFERENCES users(id) ON DELETE CASCADE,
  actor_id UUID REFERENCES users(id),
  type VARCHAR(20),
  post_id UUID,
  comment_id UUID,
  is_read BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);
```

**Messages table**:

```sql
CREATE TABLE messages (
  id UUID PRIMARY KEY,
  sender_id UUID REFERENCES users(id),
  receiver_id UUID REFERENCES users(id),
  conversation_id UUID,
  content TEXT,
  type VARCHAR(10) CHECK (type IN ('text','image','video','audio')),
  media_url TEXT,
  is_read BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);
```

**Follows, Likes, Bookmarks, OTP tables** follow similar patterns; ensure indexes on foreign keys and commonly filtered columns.

---

**Environment Variables**

Put these in your `.env` (example):

```
# Server
PORT=3000
NODE_ENV=production

# Database
DB_HOST=localhost
DB_PORT=5432
DB_NAME=vichaar_vaani
DB_USER=your_db_user
DB_PASSWORD=your_db_password

# JWT
JWT_SECRET=your_jwt_secret_key
JWT_ACCESS_EXPIRY=15m
JWT_REFRESH_EXPIRY=7d

# OTP Service
OTP_EXPIRY_MINUTES=5
OTP_MAX_ATTEMPTS=5
SMS_API_KEY=your_sms_provider_key
SMS_API_URL=https://sms-provider.com/api

# File Upload
MAX_FILE_SIZE=10485760
ALLOWED_FILE_TYPES=image/jpeg,image/png,image/gif,video/mp4
UPLOAD_PATH=/uploads

# AWS S3
AWS_ACCESS_KEY_ID=your_aws_key
AWS_SECRET_ACCESS_KEY=your_aws_secret
AWS_REGION=ap-south-1
S3_BUCKET_NAME=vichaar-vaani-media

# Redis
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_PASSWORD=your_redis_password

# Rate Limiting
RATE_LIMIT_WINDOW_MS=900000
RATE_LIMIT_MAX_REQUESTS=100
```

---

**Postman Collection Structure**

Create these folders in Postman and add requests matching endpoints above:

- Authentication
  - Send OTP
  - Verify OTP
  - Complete Profile
  - Refresh Token
  - Logout
- User Profile
  - Get Profile
  - Update Profile
- Posts
  - Create Post
  - Get Feed
  - Like Post
  - Bookmark Post
  - Share Post
- Comments
  - Get Comments
  - Add Comment
  - Delete Comment
- Notifications
  - Get Notifications
  - Mark as Read
  - Mark All Read
- Messages
  - Get Conversations
  - Get Messages
  - Send Message
  - Mark as Read
- Search
  - Search All
  - Search Users
  - Search Posts
- Follow System
  - Follow User
  - Unfollow User
  - Get Followers
  - Get Following

Include environment variables in Postman for `base_url`, `access_token`, and `refresh_token`.

---

**Testing Checklist**

- [x] OTP generation and expiration
- [x] OTP verification (new vs existing user)
- [x] Token issuance and refresh flows
- [x] File uploads (limits & types)
- [x] Pagination edge-cases
- [x] Rate limiting enforcement
- [x] Error format consistency
- [ ] Real-time delivery (manual QA for websockets)

---

**Additional Considerations**

- Real-time: WebSockets or Redis pub/sub for live notifications and messages
- Media: server-side compression, thumbnail generation
- Security: input sanitization, rate limiting, JWT rotation, refresh token blacklisting
- Performance: indexing, caching, image CDN, background workers for heavy tasks

---

**Implementation Roadmap (recommended order)**

1. Authentication (Send OTP, Verify OTP, Complete Profile, Refresh, Logout)
2. User Profile (Get/Update)
3. Posts and feed (Create, Get, Like, Bookmark)
4. Comments and Follow system
5. Notifications and Messaging
6. Search and Analytics

---

If you want, I can:
- Generate an OpenAPI (Swagger) YAML file from this spec
- Create a Postman collection JSON scaffold
- Generate initial Express / Node.js or Laravel controller stubs for these endpoints

Open the generated file `docs/Vichaar_Vaani_API.md` in your workspace to review and ask for changes.

Happy building! 🎉

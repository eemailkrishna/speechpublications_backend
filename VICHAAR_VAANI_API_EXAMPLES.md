# Vichaar Vaani API - Complete Examples

All 27 APIs with real request/response examples and cURL commands.

---

## PHASE 1: AUTHENTICATION (APIs 1-3, 26-27)

### API 1: Send OTP

**Endpoint**: `POST /api/auth/send-otp`

**Request**:
```bash
curl -X POST http://localhost:8000/api/auth/send-otp \
  -H "Content-Type: application/json" \
  -d '{
    "mobile_number": "9451282000",
    "country_code": "+91"
  }'
```

**Response** (200):
```json
{
  "success": true,
  "message": "OTP sent successfully",
  "otp_id": "550e8400-e29b-41d4-a716-446655440000",
  "expires_in": 300
}
```

**Response** (429):
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

### API 2: Verify OTP

**Endpoint**: `POST /api/auth/verify-otp`

**Request** (New User):
```bash
curl -X POST http://localhost:8000/api/auth/verify-otp \
  -H "Content-Type: application/json" \
  -d '{
    "mobile_number": "9451282000",
    "country_code": "+91",
    "otp": "123456",
    "otp_id": "550e8400-e29b-41d4-a716-446655440000"
  }'
```

**Response** (New User - 200):
```json
{
  "success": true,
  "is_new_user": true,
  "temp_token": "random_temporary_token_string",
  "message": "OTP verified. Please complete profile setup"
}
```

**Response** (Existing User - 200):
```json
{
  "success": true,
  "is_new_user": false,
  "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "refresh_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "user": {
    "id": "550e8400-e29b-41d4-a716-446655440001",
    "full_name": "Rajesh Kumar",
    "username": "rajesh_k",
    "email": "rajesh@example.com",
    "bio": "Tech enthusiast",
    "phone_number": "+919451282000",
    "profile_photo": "https://cdn.example.com/photo.jpg",
    "followers_count": 1200,
    "following_count": 340,
    "posts_count": 24,
    "is_verified": false,
    "created_at": "2024-01-15T10:30:00Z"
  }
}
```

---

### API 3: Complete Profile

**Endpoint**: `POST /api/auth/complete-profile`

**Request**:
```bash
curl -X POST http://localhost:8000/api/auth/complete-profile \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer random_temporary_token_string" \
  -d '{
    "full_name": "John Doe",
    "username": "johndoe123",
    "bio": "Digital creator | Tech enthusiast",
    "profile_photo": "https://cdn.example.com/profile.jpg"
  }'
```

**Response** (201):
```json
{
  "success": true,
  "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "refresh_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "user": {
    "id": "550e8400-e29b-41d4-a716-446655440002",
    "full_name": "John Doe",
    "username": "johndoe123",
    "email": null,
    "bio": "Digital creator | Tech enthusiast",
    "phone_number": "+919451282000",
    "profile_photo": "https://cdn.example.com/profile.jpg",
    "followers_count": 0,
    "following_count": 0,
    "posts_count": 0,
    "is_verified": false,
    "created_at": "2024-12-14T15:30:00Z"
  }
}
```

**Error** (400 - Username taken):
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

### API 26: Refresh Token

**Endpoint**: `POST /api/auth/refresh-token`

**Request**:
```bash
curl -X POST http://localhost:8000/api/auth/refresh-token \
  -H "Content-Type: application/json" \
  -d '{
    "refresh_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
  }'
```

**Response** (200):
```json
{
  "success": true,
  "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "refresh_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

---

### API 27: Logout

**Endpoint**: `POST /api/auth/logout`

**Request**:
```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

## PHASE 2: USER PROFILE (APIs 4-5)

### API 4: Get User Profile

**Endpoint**: `GET /api/user/profile`

**Request**:
```bash
curl -X GET http://localhost:8000/api/user/profile \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "user": {
    "id": "550e8400-e29b-41d4-a716-446655440002",
    "full_name": "John Doe",
    "username": "johndoe123",
    "email": "john@example.com",
    "bio": "Digital creator | Tech enthusiast",
    "phone_number": "+919451282000",
    "profile_photo": "https://cdn.example.com/profile.jpg",
    "followers_count": 150,
    "following_count": 200,
    "posts_count": 25,
    "is_verified": false,
    "created_at": "2024-12-14T15:30:00Z"
  }
}
```

---

### API 5: Update Profile

**Endpoint**: `PUT /api/user/profile`

**Request**:
```bash
curl -X PUT http://localhost:8000/api/user/profile \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -d '{
    "full_name": "John Doe Updated",
    "bio": "Updated bio ☕",
    "email": "newemail@example.com"
  }'
```

**Response** (200):
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "user": {
    "id": "550e8400-e29b-41d4-a716-446655440002",
    "full_name": "John Doe Updated",
    "username": "johndoe123",
    "email": "newemail@example.com",
    "bio": "Updated bio ☕",
    "phone_number": "+919451282000",
    "profile_photo": "https://cdn.example.com/profile.jpg"
  }
}
```

---

## PHASE 3: POSTS & FEED (APIs 6-10)

### API 6: Get Home Feed

**Endpoint**: `GET /api/feed/home?page=1&limit=20&feed_type=for_you`

**Request**:
```bash
curl -X GET "http://localhost:8000/api/feed/home?page=1&limit=20&feed_type=for_you" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Query Parameters**:
- `page`: 1 (optional)
- `limit`: 20 (optional, max 100)
- `feed_type`: for_you|following|trending (optional)

**Response** (200):
```json
{
  "success": true,
  "posts": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440003",
      "user": {
        "id": "550e8400-e29b-41d4-a716-446655440001",
        "full_name": "Rajesh Kumar",
        "username": "rajesh_k",
        "profile_photo": "https://cdn.example.com/rajesh.jpg"
      },
      "content": "Just finished reading an amazing book on personal growth. Highly recommend \"Atomic Habits\" to everyone! 📚",
      "media": [
        {
          "type": "image",
          "url": "https://cdn.example.com/post1.jpg",
          "thumbnail": "https://cdn.example.com/post1_thumb.jpg"
        }
      ],
      "location": null,
      "likes_count": 245,
      "comments_count": 32,
      "shares_count": 18,
      "is_liked": false,
      "is_bookmarked": false,
      "created_at": "2h ago"
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 10,
    "has_next": true
  }
}
```

---

### API 7: Create Post

**Endpoint**: `POST /api/posts/create`

**Request** (with media):
```bash
curl -X POST http://localhost:8000/api/posts/create \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -F "content=Check out this amazing sunset! 🌅" \
  -F "visibility=public" \
  -F "media=@/path/to/image.jpg" \
  -F "media=@/path/to/image2.jpg"
```

**Request** (text only):
```bash
curl -X POST http://localhost:8000/api/posts/create \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -d '{
    "content": "What a beautiful day! ☀️",
    "visibility": "public"
  }'
```

**Response** (201):
```json
{
  "success": true,
  "message": "Post created successfully",
  "post": {
    "id": "550e8400-e29b-41d4-a716-446655440004",
    "user": {
      "id": "550e8400-e29b-41d4-a716-446655440002",
      "full_name": "John Doe",
      "username": "johndoe123",
      "profile_photo": "https://cdn.example.com/john.jpg"
    },
    "content": "What a beautiful day! ☀️",
    "media": [],
    "likes_count": 0,
    "comments_count": 0,
    "shares_count": 0,
    "visibility": "public",
    "created_at": "just now"
  }
}
```

---

### API 8: Toggle Like on Post

**Endpoint**: `POST /api/posts/{postId}/like`

**Request**:
```bash
curl -X POST http://localhost:8000/api/posts/550e8400-e29b-41d4-a716-446655440003/like \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "is_liked": true,
  "likes_count": 246
}
```

**Response** (unlike):
```json
{
  "success": true,
  "is_liked": false,
  "likes_count": 245
}
```

---

### API 9: Toggle Bookmark

**Endpoint**: `POST /api/posts/{postId}/bookmark`

**Request**:
```bash
curl -X POST http://localhost:8000/api/posts/550e8400-e29b-41d4-a716-446655440003/bookmark \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "is_bookmarked": true
}
```

---

### API 10: Share Post

**Endpoint**: `POST /api/posts/{postId}/share`

**Request**:
```bash
curl -X POST http://localhost:8000/api/posts/550e8400-e29b-41d4-a716-446655440003/share \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "shares_count": 19,
  "share_url": "https://vichaarvaani.app/post/550e8400-e29b-41d4-a716-446655440003"
}
```

---

## PHASE 4: COMMENTS (APIs 11-13)

### API 11: Get Post Comments

**Endpoint**: `GET /api/posts/{postId}/comments?page=1&limit=20`

**Request**:
```bash
curl -X GET "http://localhost:8000/api/posts/550e8400-e29b-41d4-a716-446655440003/comments?page=1&limit=20" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "comments": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440005",
      "user": {
        "id": "550e8400-e29b-41d4-a716-446655440006",
        "full_name": "Priya Sharma",
        "username": "priya_s",
        "profile_photo": "https://cdn.example.com/priya.jpg"
      },
      "content": "This is amazing! Love the content 🔥",
      "likes_count": 12,
      "replies_count": 2,
      "is_liked": false,
      "created_at": "2 min ago"
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_comments": 4,
    "has_next": false
  }
}
```

---

### API 12: Create Comment

**Endpoint**: `POST /api/posts/{postId}/comments`

**Request** (top-level comment):
```bash
curl -X POST http://localhost:8000/api/posts/550e8400-e29b-41d4-a716-446655440003/comments \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -d '{
    "content": "Great post! 👍"
  }'
```

**Request** (reply to comment):
```bash
curl -X POST http://localhost:8000/api/posts/550e8400-e29b-41d4-a716-446655440003/comments \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -d '{
    "content": "Thanks for the reply!",
    "parent_comment_id": "550e8400-e29b-41d4-a716-446655440005"
  }'
```

**Response** (201):
```json
{
  "success": true,
  "comment": {
    "id": "550e8400-e29b-41d4-a716-446655440007",
    "user": {
      "id": "550e8400-e29b-41d4-a716-446655440002",
      "full_name": "John Doe",
      "username": "johndoe123",
      "profile_photo": "https://cdn.example.com/john.jpg"
    },
    "content": "Great post! 👍",
    "likes_count": 0,
    "replies_count": 0,
    "created_at": "just now"
  }
}
```

---

### API 13: Delete Comment

**Endpoint**: `DELETE /api/comments/{commentId}`

**Request**:
```bash
curl -X DELETE http://localhost:8000/api/comments/550e8400-e29b-41d4-a716-446655440007 \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "message": "Comment deleted successfully"
}
```

---

## PHASE 5: FOLLOW SYSTEM (APIs 22-25)

### API 22: Follow User

**Endpoint**: `POST /api/users/{userId}/follow`

**Request**:
```bash
curl -X POST http://localhost:8000/api/users/550e8400-e29b-41d4-a716-446655440001/follow \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "is_following": true,
  "followers_count": 2501
}
```

---

### API 23: Unfollow User

**Endpoint**: `DELETE /api/users/{userId}/follow`

**Request**:
```bash
curl -X DELETE http://localhost:8000/api/users/550e8400-e29b-41d4-a716-446655440001/follow \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "is_following": false,
  "followers_count": 2500
}
```

---

### API 24: Get Followers

**Endpoint**: `GET /api/users/{userId}/followers?page=1&limit=20`

**Request**:
```bash
curl -X GET "http://localhost:8000/api/users/550e8400-e29b-41d4-a716-446655440001/followers?page=1" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "followers": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440002",
      "full_name": "John Doe",
      "username": "johndoe123",
      "profile_photo": "https://cdn.example.com/john.jpg",
      "is_following": true
    }
  ],
  "total_count": 1200
}
```

---

### API 25: Get Following

**Endpoint**: `GET /api/users/{userId}/following?page=1&limit=20`

**Request**:
```bash
curl -X GET "http://localhost:8000/api/users/550e8400-e29b-41d4-a716-446655440001/following?page=1" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "following": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440003",
      "full_name": "Priya Sharma",
      "username": "priya_s",
      "profile_photo": "https://cdn.example.com/priya.jpg",
      "is_following": false
    }
  ],
  "total_count": 340
}
```

---

## PHASE 6: NOTIFICATIONS (APIs 14-16)

### API 14: Get Notifications

**Endpoint**: `GET /api/notifications?page=1&limit=20&filter=all`

**Request**:
```bash
curl -X GET "http://localhost:8000/api/notifications?filter=likes" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "unread_count": 3,
  "notifications": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440008",
      "type": "like",
      "actor": {
        "id": "550e8400-e29b-41d4-a716-446655440006",
        "full_name": "Priya Sharma",
        "username": "priya_s",
        "profile_photo": "https://cdn.example.com/priya.jpg"
      },
      "action": "liked your post",
      "post": {
        "id": "550e8400-e29b-41d4-a716-446655440003",
        "thumbnail": "https://cdn.example.com/post1_thumb.jpg"
      },
      "is_read": false,
      "created_at": "2 min ago"
    }
  ]
}
```

---

### API 15: Mark Notification as Read

**Endpoint**: `PUT /api/notifications/{notificationId}/read`

**Request**:
```bash
curl -X PUT http://localhost:8000/api/notifications/550e8400-e29b-41d4-a716-446655440008/read \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "message": "Notification marked as read"
}
```

---

### API 16: Mark All as Read

**Endpoint**: `PUT /api/notifications/read-all`

**Request**:
```bash
curl -X PUT http://localhost:8000/api/notifications/read-all \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "message": "All notifications marked as read"
}
```

---

## PHASE 7: MESSAGING (APIs 17-20)

### API 17: Get Conversations

**Endpoint**: `GET /api/messages/conversations?page=1&limit=20`

**Request**:
```bash
curl -X GET "http://localhost:8000/api/messages/conversations" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "conversations": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440009",
      "user": {
        "id": "550e8400-e29b-41d4-a716-446655440006",
        "full_name": "Priya Sharma",
        "username": "priya_s",
        "profile_photo": "https://cdn.example.com/priya.jpg",
        "is_online": true
      },
      "last_message": {
        "content": "Hey! How are you doing?",
        "sender_id": "550e8400-e29b-41d4-a716-446655440006",
        "is_read": false,
        "created_at": "2m ago"
      },
      "unread_count": 3,
      "updated_at": "2m ago"
    }
  ]
}
```

---

### API 18: Get Messages in Conversation

**Endpoint**: `GET /api/messages/conversations/{conversationId}?page=1&limit=50`

**Request**:
```bash
curl -X GET "http://localhost:8000/api/messages/conversations/550e8400-e29b-41d4-a716-446655440009" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "user": {
    "id": "550e8400-e29b-41d4-a716-446655440006",
    "full_name": "Priya Sharma",
    "username": "priya_s",
    "profile_photo": "https://cdn.example.com/priya.jpg",
    "is_online": true
  },
  "messages": [
    {
      "id": "550e8400-e29b-41d4-a716-446655440010",
      "sender_id": "550e8400-e29b-41d4-a716-446655440006",
      "content": "Hello! 👋",
      "type": "text",
      "is_read": true,
      "created_at": "10:30 AM"
    },
    {
      "id": "550e8400-e29b-41d4-a716-446655440011",
      "sender_id": "550e8400-e29b-41d4-a716-446655440002",
      "content": "Hi! How are you?",
      "type": "text",
      "is_read": true,
      "created_at": "10:31 AM"
    }
  ]
}
```

---

### API 19: Send Message

**Endpoint**: `POST /api/messages/send`

**Request**:
```bash
curl -X POST http://localhost:8000/api/messages/send \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -d '{
    "receiver_id": "550e8400-e29b-41d4-a716-446655440006",
    "content": "Hello! How are you?",
    "type": "text"
  }'
```

**Response** (201):
```json
{
  "success": true,
  "message": {
    "id": "550e8400-e29b-41d4-a716-446655440012",
    "sender_id": "550e8400-e29b-41d4-a716-446655440002",
    "receiver_id": "550e8400-e29b-41d4-a716-446655440006",
    "content": "Hello! How are you?",
    "type": "text",
    "is_read": false,
    "created_at": "10:35 AM"
  }
}
```

---

### API 20: Mark Messages as Read

**Endpoint**: `PUT /api/messages/conversations/{conversationId}/read`

**Request**:
```bash
curl -X PUT http://localhost:8000/api/messages/conversations/550e8400-e29b-41d4-a716-446655440009/read \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "message": "Messages marked as read"
}
```

---

## SEARCH (API 21)

### API 21: Search

**Endpoint**: `GET /api/search?query=krishna&type=all&page=1&limit=20`

**Request** (All types):
```bash
curl -X GET "http://localhost:8000/api/search?query=krishna&type=all" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Request** (Users only):
```bash
curl -X GET "http://localhost:8000/api/search?query=krishna&type=users" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Response** (200):
```json
{
  "success": true,
  "results": {
    "users": [
      {
        "id": "550e8400-e29b-41d4-a716-446655440013",
        "full_name": "Priya Sharma",
        "username": "priya_s",
        "profile_photo": "https://cdn.example.com/priya.jpg",
        "followers_count": 2500,
        "is_following": false
      }
    ],
    "posts": [
      {
        "id": "550e8400-e29b-41d4-a716-446655440003",
        "user": {
          "full_name": "Amit Patel",
          "username": "amit_p"
        },
        "content": "Amazing sunset at the beach today! 🌅",
        "thumbnail": "https://cdn.example.com/post.jpg",
        "likes_count": 245
      }
    ],
    "hashtags": [
      {
        "tag": "#krishna",
        "posts_count": 1234
      }
    ]
  }
}
```

---

## Error Responses

### INVALID_OTP
```json
{
  "success": false,
  "error": {
    "code": "INVALID_OTP",
    "message": "OTP is incorrect"
  }
}
```

### UNAUTHORIZED
```json
{
  "success": false,
  "error": {
    "code": "UNAUTHORIZED",
    "message": "Not authorized to perform this action"
  }
}
```

### VALIDATION_ERROR
```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Validation failed",
    "field": "email"
  }
}
```

---

## Summary

- **Total APIs**: 27
- **Authenticated**: 24 (require access_token)
- **Public**: 3 (auth endpoints, no token needed)
- **HTTP Methods**:
  - GET: 11 endpoints
  - POST: 12 endpoints
  - PUT: 2 endpoints
  - DELETE: 2 endpoints

All responses follow standard JSON format with `success` boolean and either `data` or `error` object.

---

**Happy Testing! 🚀**

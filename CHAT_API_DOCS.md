# Speech Chat — Complete API & Flow Specification (for Flutter implementation)

This document is a **complete implementation spec**. A Flutter agent given this document must be able to build the full chat app (login → sidebar → chat → real-time messaging) with no other reference.

**Reference implementation (web):** Next.js app at `/Applications/XAMPP/xamppfiles/htdocs/speech-chat`
**Backend (Laravel):** `/Applications/XAMPP/xamppfiles/htdocs/speech-publication`

---

## 0. Base configuration

```
Base URL:  http://localhost/speech-publication/api
```

**IMPORTANT — path convention:** In this document every endpoint is written **relative to the Base URL**.
Example: `GET /users/all` ⇒ full URL `http://localhost/speech-publication/api/users/all`.
Do NOT append `/api` twice.

### Headers used on every protected call

```
Authorization: Bearer <jwt>
```

JSON endpoints also require:

```
Content-Type: application/json
```

Image upload uses `multipart/form-data` (let the HTTP client set the boundary automatically).

### Error responses

401 (expired/missing/invalid token):

```json
{
  "success": false,
  "error": {
    "code": "INVALID_TOKEN",
    "message": "Token has expired"
  }
}
```

Codes: `Token not provided` | `Token has expired` | `Invalid token`.
**Required behavior:** on HTTP 401 → discard stored token → return to login screen.

### Suggested Flutter packages

```yaml
dependencies:
  http: ^1.2.0                 # REST calls (or dio)
  pusher_websocket_flutter: ^1.0.0   # Pusher (or `pusher_channels_flutter`)
  shared_preferences: ^2.2.0   # persist token + user id
  image_picker: ^1.0.0         # pick image for send
```

---

## 1. Data models (JSON shapes)

### User

```json
{ "id": 33, "name": "Sanni Prajapati", "profile_photo": "698db97204c761.82384440.jpg" }
```

`profile_photo` is a **filename or null** — build URL as:
`https://speech-vichaar-vaani-s3-bucket.s3.ap-south-1.amazonaws.com/{profile_photo}` is NOT guaranteed;
web UI shows a placeholder when null. Treat as nullable.

### Message

```json
{
  "id": 119,
  "conversation_id": 1,
  "sender_id": 42,
  "receiver_id": 43,
  "text": "hello",
  "image": "https://... or null",
  "status": "sent",
  "created_at": "2026-09-24T04:25:16.000000Z",
  "sender": { "id": 42, "name": "Krishna test", "avatar": null }
}
```

- `status` ∈ `"sent"` | `"delivered"` | `"read"`
- `receiver_id` **may arrive as String or int** → always parse with `num.parse`-safe helper.
- `sender.avatar` may be null; some responses use `sender.profile_photo` instead → accept both.

### Conversation

```json
{
  "id": 1,
  "user": { "id": 43, "name": "Krishna Bhardwaj", "avatar": null },
  "last_message": "kaise ho?",
  "last_message_at": "2026-09-24T03:32:17.000000Z",
  "unread_count": 2
}
```

---

## 2. Full API call sequence (execute in this exact order)

### PHASE A — Login screen (app start)

| Step | Call | Auth | When |
|---|---|---|---|
| A1 | `GET /users/all` | none | screen mount (refresh user list) |
| A2 | `GET /test/token/{userId}` | none | user taps a user to "log in" |

**A1 response**

```json
{ "success": true, "data": [ { "id": 1, "name": "krishna bhardwaj", "profile_photo": null } ] }
```

**A2 response**

```json
{ "token": "eyJhbGciOiJIUzI1NiJ9...", "user": { "id": 1, "name": "krishna bhardwaj" } }
```

Persist `token`, `userId`, `userName` (SharedPreferences). Then navigate to Chat screen.

> Note: the JWT is valid for 24 hours (exp claim). There is no refresh endpoint in use — on 401, go back to Phase A.

### PHASE B — Chat screen init (all four fire in parallel on screen mount)

| Step | Call | Auth | Repeat |
|---|---|---|---|
| B1 | `GET /get-chat-users` | Bearer | once |
| B2 | `GET /users/online` | Bearer | **every 5 s** (forever) |
| B3 | `GET /message/unread-by-sender` | Bearer | **every 3 s** (forever) |
| B4 | `GET /conversations` | Bearer | once (+ refresh when returning to screen) |

**B1 response** — sidebar list (excludes self):

```json
{ "success": true, "data": [ { "id": 2, "name": "Speech Publications", "profile_photo": null } ] }
```

**B2 response:**

```json
{ "success": true, "data": [ { "user_id": 42, "name": "Krishna test", "profile_photo": null } ], "total_online": 2 }
```

Store as `Set<int>` of `user_id` → drives green online dot in sidebar.

**B3 response:**

```json
{ "success": true, "data": { "33": 3, "43": 39 } }
```

Keys are **strings** → convert to `Map<int,int>` (senderId → unread count) → unread badge on sidebar row.

**B4 response** (Laravel paginator):

```json
{
  "data": [
    { "id": 1, "user": { "id": 43, "name": "Krishna Bhardwaj", "avatar": null },
      "last_message": "kaise ho?", "last_message_at": "2026-09-24T03:32:17.000000Z", "unread_count": 2 }
  ],
  "current_page": 1, "last_page": 1, "total": 1
}
```

Each conversation `id` is needed for Pusher channel subscription (PHASE C).

### PHASE C — Pusher WebSocket (parallel with Phase B)

**Config:**

```
Key:       43e9e4dad16b21729655
Cluster:   ap2
Auth URL:  http://localhost/speech-publication/api/broadcasting/auth
Transport: wss only (force TLS)
```

**C1. Connect** and capture `socket_id` from the connection.

**C2. Subscribe `presence-online`** (presence channel ⇒ Pusher will request auth):

```
POST /broadcasting/auth
Headers: Authorization: Bearer <token>
Body (application/x-www-form-urlencoded):  socket_id=<socket_id>&channel_name=presence-online
```

(JSON body with the same two fields also works — Laravel reads both.)

**Response:**

```json
{
  "auth": "43e9e4dad16b21729655:signature",
  "channel_data": "{\"user_id\":\"43\",\"user_info\":{\"name\":\"...\",\"profile_photo\":null}}"
}
```

**C3. Bind presence events:**

| Event | Action |
|---|---|
| `pusher:subscription_succeeded` | payload `presence.ids` (list of user id strings) → set online set |
| `pusher:member_added` | `member.id` (string) → add to online set |
| `pusher:member_removed` | `member.id` → remove from online set |

**C4. For every conversation id from B4** subscribe public channel `conversation.{id}`
(public channels need **no auth call**) and bind two events (handler logic in Section 3):

- `message.sent`
- `message.read`

Keep a `Set<String>` of already-subscribed channel names; never subscribe twice.
If a **new** conversation appears later (first message ever between two users), subscribe to it when you learn its `conversation_id` (from a `message/send` response or `message.sent` payload).

### PHASE D — Open a chat (user taps a sidebar row)

| Step | Call | Auth |
|---|---|---|
| D1 | `GET /message/get?sender_id={myId}&receiver_id={peerId}&limit=50` | Bearer |
| D2 | `POST /message/read-all` body `{"conversation_id": <data[0].conversation_id>}` | Bearer — **only if D1 returned ≥1 message** |

**D1 response:**

```json
{
  "success": true,
  "data": [ { "id": 117, "conversation_id": 1, "sender_id": 42, "receiver_id": 43,
              "text": "hello", "image": null, "status": "read",
              "created_at": "2026-09-24T04:25:16.000000Z",
              "sender": { "id": 42, "name": "Krishna test", "profile_photo": null } } ],
  "pagination": { "current_page": 1, "last_page": 2, "per_page": 50, "total": 86 }
}
```

- API returns messages **DESC (latest first)** → **reverse the list** for chat UI (oldest at top).
- No messages ⇒ empty chat; conversation may not exist yet (it is auto-created on first `message/send`).

**D2 response:**

```json
{ "success": true, "message": "3 messages marked as read", "data": { "conversation_id": 1, "messages_updated": 3 } }
```

Side effect: backend broadcasts `message.read` to the peer ⇒ their ticks become ✓✓.

### PHASE E — Send message

**Text:**

```
POST /message/send
Headers: Authorization: Bearer <token>, Content-Type: application/json
Body: { "receiver_id": 43, "text": "hello" }
```

**Image (≤ 5MB; jpg/png/gif/webp):**

```
POST /message/send
Headers: Authorization: Bearer <token>   (Content-Type: multipart/form-data — auto)
Body (form fields): receiver_id=43, text="optional caption", image=<file bytes>
```

**Response — HTTP 201:**

```json
{
  "success": true,
  "message": "Message sent successfully",
  "data": {
    "id": 119, "conversation_id": 1, "sender_id": 42, "receiver_id": "43",
    "text": "hello", "image": null, "status": "sent",
    "created_at": "2026-09-24T04:25:16.000000Z",
    "sender": { "id": 42, "name": "Krishna test", "avatar": null }
  }
}
```

Client behavior:
1. Optimistically append `data` to the message list (or wait for response — web waits for response).
2. If `conversation_id` channel not yet subscribed → subscribe now (Section 3.6).
3. Image messages without caption get server-side `text` = `"📷 Image"`.
4. On network failure → restore the unsent text in the input box.

There is **no polling for new messages** — all incoming messages arrive via Pusher (Phase F).

### PHASE F — Receiving messages/read receipts (real-time)

Handled entirely by the `message.sent` / `message.read` listeners bound in C4 — exact rules in Section 3.

### PHASE G — Steady state loops

- every **5 s**: `GET /users/online`
- every **3 s**: `GET /message/unread-by-sender`
- Pusher: `message.sent`, `message.read`, presence `member_added` / `member_removed`

On chat screen dispose → cancel both timers and disconnect Pusher.

---

## 3. Client-side logic rules (must implement exactly)

### 3.1 `message.sent` handler (channel `conversation.{id}`)

Payload:

```json
{ "id": 119, "conversation_id": 1, "sender_id": 42, "receiver_id": 43,
  "text": "hello", "image": null, "status": "sent",
  "created_at": "...", "sender": { "id": 42, "name": "...", "avatar": null } }
```

```
if (payload.sender_id == myId)            → IGNORE (own echo; already shown from send response)
if (no chat currently open)               → increment unread badge for payload.sender_id; return
if (payload.sender_id != openPeerId
    && payload.receiver_id != openPeerId) → IGNORE (not the open chat); bump unread badge
// otherwise: message belongs to the open chat
if (message with same id already in list) → IGNORE (dedupe)
append payload to message list
if (payload.sender_id == openPeerId)      → clear unread badge for that peer
                                            AND POST /message/read-all {conversation_id: payload.conversation_id}
else                                      → (shouldn't happen; own messages filtered above)
```

### 3.2 `message.read` handler (channel `conversation.{id}`)

Payload:

```json
{ "id": 119, "sender_id": 42, "receiver_id": 43, "status": "read", "read_at": "..." }
```

```
if (payload.sender_id == myId)  → find message with id == payload.id in list
                                   and set status = "read"   // my message was read by peer
```

### 3.3 Send-side triggers of read receipts

- Opening a chat with history → `POST /message/read-all` (D2)
- Receiving `message.sent` while that chat is open → `POST /message/read-all`

### 3.4 Status ticks UI

```
sent        → single tick  ✓
delivered   → double tick  ✓✓
read        → double tick  ✓✓ (blue/bold)
```

### 3.5 Sidebar unread badge

- Source of truth: `GET /message/unread-by-sender` polled every 3 s.
- Local adjustments: clear when chat with that sender is open or a `message.sent` from them arrives while open; +1 when `message.sent` from them arrives while their chat is NOT open (poll will correct within 3 s).
- Note: keys come as **strings** from JSON → parse to int.
- Opening a chat clears the badge server-side via `read-all`.

### 3.6 Channel subscription lifecycle

```
subscribed = Set<String>          // e.g. "conversation.1"
subscribe(convId):
    if ("conversation.$convId" in subscribed) return
    pusher.subscribe("conversation.$convId")   // public → no auth
    bind message.sent + message.read handlers
    subscribed.add(...)
```

Subscribe to all `conversation.id` from `GET /conversations` at Phase C; subscribe additionally whenever a new `conversation_id` is discovered (send response / incoming payload).

### 3.7 Online dot

- Combine BOTH sources: 5 s polling (`/users/online`) as base + presence events for instant updates.
- `onlineSet` = `Set<int>` of user ids; sidebar row shows green dot if `peer.id in onlineSet`.

---

## 4. Endpoint quick reference

| # | Method | Path (relative to Base URL) | Auth | Body / Query | Purpose |
|---|---|---|---|---|---|
| 1 | GET | `/users/all` | no | — | login user list |
| 2 | GET | `/test/token/{userId}` | no | — | get JWT |
| 3 | GET | `/get-chat-users` | Bearer | — | sidebar users |
| 4 | GET | `/users/online` | Bearer | — | online list (5 s poll) |
| 5 | GET | `/message/unread-by-sender` | Bearer | — | badges (3 s poll) |
| 6 | GET | `/conversations` | Bearer | — | conversation list |
| 7 | POST | `/broadcasting/auth` | Bearer | form: `socket_id`, `channel_name` | Pusher channel auth |
| 8 | GET | `/message/get` | Bearer | `sender_id`, `receiver_id`, `limit?` | chat history |
| 9 | POST | `/message/read-all` | Bearer | JSON `{conversation_id}` | mark read |
| 10 | POST | `/message/send` (JSON) | Bearer | `{receiver_id, text}` | send text |
| 11 | POST | `/message/send` (multipart) | Bearer | `receiver_id`, `text?`, `image` | send image |
| 12 | GET | `/message/unread-count` | Bearer | — | optional total unread |

Unused-but-available: `POST /conversations {user_id}`, `GET /user/{id}/status`,
`GET /users/online-status?ids=1,2,3`.

---

## 5. Implementation checklist for the Flutter agent

### Screens

- [ ] **LoginScreen** — grid/list of users (`GET /users/all`), tap → `GET /test/token/{userId}` → save token → push ChatScreen
- [ ] **ChatListScreen (sidebar)** — users (`GET /get-chat-users`) with online dot, unread badge, last conversation preview (`GET /conversations`); tap → push ChatScreen(peer)
- [ ] **ChatScreen** — message history, input + send button, image picker, ticks, incoming real-time messages

### Wiring

- [ ] HTTP helper that injects `Authorization: Bearer` and maps 401 → logout
- [ ] Timers: online 5 s, unread 3 s — started on chat list mount, cancelled on dispose
- [ ] Pusher connect on chat list mount; subscribe `presence-online` (with auth call) + all `conversation.{id}`; disconnect on dispose
- [ ] Implement `message.sent` / `message.read` handlers exactly per Section 3
- [ ] On opening a chat: `GET /message/get` → reverse order → `POST /message/read-all` if non-empty
- [ ] Send: JSON for text, multipart for image; append response `data` to list; subscribe new conversation channel
- [ ] Reverse DESC → ASC before rendering history
- [ ] Ticks: sent / delivered / read per Section 3.4
- [ ] Handle `receiver_id` string-or-int and `avatar`/`profile_photo` field variance

### Gotchas (from the reference web app)

1. Token in the web app is memory-only; in Flutter **do** persist it (SharedPreferences).
2. `unread-by-sender` keys are JSON strings → convert.
3. Own-sent messages arrive back on `message.sent` — must be filtered by `sender_id == myId`.
4. Messages for chats other than the open one are dropped by the web app; badge relies on the 3 s poll — do at least the same.
5. A conversation created after app start (first-ever message) has no channel yet → subscribe when its `conversation_id` is first seen.
6. No message polling exists — if you skip Pusher, you will never receive replies.

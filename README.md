# Laravel Blog Posts API

A simple RESTful Blog Posts API built with Laravel as part of a Laravel Intern technical assessment.

The API provides complete CRUD functionality for blog posts, including request validation, Eloquent relationships, pagination, API resources, and feature testing.

## Features

- Create blog posts
- Retrieve all blog posts
- Retrieve a single blog post
- Update blog posts
- Delete blog posts
- Request validation
- User and Post Eloquent relationships
- Pagination
- API Resources
- Proper HTTP status codes
- 404 handling for non-existent posts
- Feature tests
- MySQL database support

## Tech Stack

- Laravel
- PHP
- MySQL
- Eloquent ORM
- REST API
- PHPUnit

## Requirements

Before running the project, make sure you have:

- PHP 8.2+
- Composer
- MySQL
- Laravel
- Git

Check your installed versions:

```bash
php -v
composer -V
laravel --version
```

## Installation

### 1. Clone the Repository

```bash
git clone <your-github-repository-url>
```

Move into the project directory:

```bash
cd blog-api
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Create Environment File

Copy the example environment file.

Linux / macOS:

```bash
cp .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

## Database Configuration

Create a MySQL database:

```sql
CREATE DATABASE blog_api;
```

Then configure your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_api
DB_USERNAME=root
DB_PASSWORD=
```

Update the username and password according to your local MySQL configuration.

## Run Migrations

Run the following command:

```bash
php artisan migrate
```

This will create the required Laravel tables, including the `users` and `posts` tables.

### Posts Table

| Field | Type | Description |
|---|---|---|
| id | BIGINT | Primary key |
| title | VARCHAR(255) | Post title |
| body | TEXT | Post content |
| user_id | BIGINT | Related user |
| created_at | TIMESTAMP | Creation time |
| updated_at | TIMESTAMP | Last update time |

## Create a Test User

The API requires a valid `user_id` when creating a post.

Open Laravel Tinker:

```bash
php artisan tinker
```

Create a test user:

```php
$user = App\Models\User::factory()->create();
```

Check the user's ID:

```php
$user->id;
```

For example:

```text
1
```

Use this ID when creating a post.

Exit Tinker:

```php
exit
```

## Run the Application

Start the Laravel development server:

```bash
php artisan serve
```

The application will be available at:

```text
http://127.0.0.1:8000
```

## API Endpoints

Base URL:

```text
http://127.0.0.1:8000/api
```

| Method | Endpoint | Description |
|---|---|---|
| POST | `/posts` | Create a post |
| GET | `/posts` | Get all posts |
| GET | `/posts/{id}` | Get a single post |
| PUT | `/posts/{id}` | Update a post |
| PATCH | `/posts/{id}` | Partially update a post |
| DELETE | `/posts/{id}` | Delete a post |

## Create a Post

### Request

```http
POST /api/posts
Content-Type: application/json
```

### Request Body

```json
{
    "title": "My First Laravel Post",
    "body": "This is my first Laravel API post.",
    "user_id": 1
}
```

### Response

```json
{
    "data": {
        "id": 1,
        "title": "My First Laravel Post",
        "body": "This is my first Laravel API post.",
        "user_id": 1,
        "created_at": "2026-09-25T00:00:00.000000Z",
        "updated_at": "2026-09-25T00:00:00.000000Z"
    }
}
```

### Status Code

```text
201 Created
```

## Get All Posts

### Request

```http
GET /api/posts
```

### Status Code

```text
200 OK
```

The endpoint supports pagination.

Example:

```http
GET /api/posts?page=2
```

The response includes the post data along with pagination metadata and links.

## Get a Single Post

### Request

```http
GET /api/posts/1
```

### Status Code

```text
200 OK
```

If the post does not exist:

```text
404 Not Found
```

## Update a Post

### Request

```http
PUT /api/posts/1
Content-Type: application/json
```

### Request Body

```json
{
    "title": "Updated Laravel Post",
    "body": "This post has been updated.",
    "user_id": 1
}
```

### Status Code

```text
200 OK
```

## Delete a Post

### Request

```http
DELETE /api/posts/1
```

### Status Code

```text
204 No Content
```

## Validation

The API validates incoming post data using Laravel Form Request classes.

### Title

- Required
- String
- Maximum 255 characters

Validation rule:

```text
required|string|max:255
```

### Body

- Required
- String

Validation rule:

```text
required|string
```

### User ID

- Required
- Must exist in the users table

Validation rule:

```text
required|exists:users,id
```

### Example Invalid Request

```json
{
    "title": "",
    "body": "",
    "user_id": 99999
}
```

The API returns:

```text
422 Unprocessable Entity
```

with validation error details.

## Eloquent Relationships

The project implements a User and Post relationship.

### User Model

A user can have many posts:

```php
public function posts(): HasMany
{
    return $this->hasMany(Post::class);
}
```

### Post Model

A post belongs to a user:

```php
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

The `posts.user_id` column is a foreign key referencing `users.id`.

## Pagination

The post listing endpoint uses Laravel pagination:

```php
$posts = Post::latest()->paginate(10);
```

Pagination information is included in the API response.

## API Resource

The project uses a Laravel API Resource to keep API responses organized:

```text
app/Http/Resources/PostResource.php
```

The resource controls the fields returned by the API.

## Project Structure

```text
blog-api/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       └── PostController.php
│   │   │
│   │   ├── Requests/
│   │   │   ├── StorePostRequest.php
│   │   │   └── UpdatePostRequest.php
│   │   │
│   │   └── Resources/
│   │       └── PostResource.php
│   │
│   └── Models/
│       ├── Post.php
│       └── User.php
│
├── database/
│   ├── factories/
│   ├── migrations/
│   │   └── xxxx_xx_xx_create_posts_table.php
│   └── seeders/
│
├── routes/
│   ├── api.php
│   ├── console.php
│   └── web.php
│
├── tests/
│   ├── Feature/
│   │   └── PostApiTest.php
│   └── Unit/
│
├── .env.example
├── artisan
├── composer.json
└── README.md
```

## Testing

Run the Laravel test suite:

```bash
php artisan test
```

The feature tests cover the main API functionality, including:

- Creating posts
- Retrieving posts
- Retrieving a single post
- Updating posts
- Deleting posts
- Validation
- Non-existent post handling

## Testing with Postman

The API can be tested using Postman, Insomnia, cURL, or another API client.

Start the Laravel server:

```bash
php artisan serve
```

Then use:

```text
http://127.0.0.1:8000/api/posts
```

For POST and PUT requests, select:

```text
Body → raw → JSON
```

Example request:

```json
{
    "title": "My First Laravel Post",
    "body": "Learning Laravel REST API development.",
    "user_id": 1
}
```

Make sure the request contains:

```text
Content-Type: application/json
```

## HTTP Status Codes

| Status Code | Meaning |
|---|---|
| 200 | Successful request |
| 201 | Resource successfully created |
| 204 | Resource successfully deleted |
| 404 | Post not found |
| 422 | Validation error |

## Technical Assessment Requirements

### Core Requirements

- [x] Laravel migration
- [x] Posts database table
- [x] Post Eloquent model
- [x] API controller
- [x] API routes
- [x] CRUD operations
- [x] Request validation
- [x] Eloquent database operations
- [x] JSON responses
- [x] 404 handling
- [x] Appropriate HTTP status codes

### Bonus Requirements

- [x] Form Request validation
- [x] Pagination
- [x] User and Post relationships
- [x] API Resource
- [x] API feature tests

## Author

**Md. Sanyul Islam**

Full Stack / Frontend Developer

## License

This project was created for a Laravel technical assessment and learning purposes.
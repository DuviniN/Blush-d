# Blush-d Controllers Documentation

This document provides a comprehensive overview of all controllers in the Blush-d application's server architecture.

## Table of Contents
- [Architecture Overview](#architecture-overview)
- [Controller Summaries](#controller-summaries)
  - [BaseController](#basecontroller)
  - [API Router (api.php)](#api-router-apiphp)
  - [ManagerController](#managercontroller)
  - [ProductController](#productcontroller)
  - [DashboardController](#dashboardcontroller)
  - [ProfileController](#profilecontroller)
- [API Endpoints](#api-endpoints)

## Architecture Overview

The Blush-d application follows a modular controller-based architecture where:
- All controllers extend the `BaseController` class for common functionality
- The main `api.php` serves as the central router
- Each controller handles specific business logic domains
- RESTful HTTP methods are used (GET, POST, PUT, DELETE)
- JSON responses are standardized across all controllers

## Controller Summaries

### BaseController
**File:** `server/BaseController.php`

The foundation class that all other controllers inherit from.

**Key Features:**
- Database connection management
- Standardized JSON response format via `sendResponse()`
- Input validation with `validateRequired()`
- Input sanitization with `sanitizeInput()`
- Error handling and HTTP status code management

**Methods:**
- `sendResponse($success, $message, $data, $statusCode)` - Sends formatted JSON responses
- `validateRequired($data, $required)` - Validates required fields
- `sanitizeInput($input)` - Sanitizes user input for security

---

### API Router (api.php)
**File:** `server/api.php`

Central routing system that directs requests to appropriate controllers.

**Supported Endpoints:**
- `/api.php?endpoint=profile` → ProfileController
- `/api.php?endpoint=products` → ProductController  
- `/api.php?endpoint=dashboard` → DashboardController
- `/api.php?endpoint=manager` → ManagerController

**Features:**
- CORS headers configuration
- Preflight request handling
- Error handling and routing
- Content-Type management

---

### ManagerController
**File:** `server/ManagerController.php`

Orchestrates multiple controllers and handles manager-specific operations.

**Dependencies:**
- ProductController
- DashboardController
- ProfileController

**Key Responsibilities:**
- Route delegation to specialized controllers
- Order management (get orders, order details, process orders)
- User management (get users, add users)
- Review management
- Legacy action handling

**Main Actions:**
- `orders` - Retrieve all orders
- `order_details` - Get specific order details
- `users` - Get all users
- `reviews` - Get all reviews
- `process_order` - Process new orders
- `add_user` - Add new users

---

### ProductController
**File:** `server/ProductController.php`

Handles all product and category-related operations.

**HTTP Methods Supported:**
- GET: Retrieve products and categories
- POST: Add new products and categories
- PUT: Update products and stock
- DELETE: Remove products

**Key Features:**
- Product CRUD operations
- Category management
- Stock level tracking
- Low stock alerts

**Main Actions:**
- `products` - Get all products with category information
- `product_by_id` - Get specific product details
- `categories` - Get all product categories
- `add_product` - Create new products
- `update_product` - Modify existing products
- `delete_product` - Remove products
- `update_stock` - Update stock levels
- `low_stock_products` - Get products with low inventory

---

### DashboardController
**File:** `server/DashboardController.php`

Provides analytics and reporting functionality for business intelligence.

**HTTP Methods Supported:**
- GET: Retrieve various reports and statistics

**Key Features:**
- Real-time dashboard statistics
- Sales reporting with different time periods
- Revenue trend analysis
- Customer insights
- Inventory reporting

**Main Actions:**
- `dashboard_stats` - Core business metrics (products, orders, revenue, users)
- `sales_report` - Sales data by period (week/month/year)
- `popular_products` - Best-selling products analysis
- `inventory_report` - Stock levels by category
- `revenue_trends` - Revenue analysis over time
- `customer_insights` - Customer behavior analytics

**Statistical Data Provided:**
- Total products count
- Low stock alerts (< 20 items)
- Monthly orders and revenue
- Total users and categories

---

### ProfileController
**File:** `server/ProfileController.php`

Manages user profile operations and account management.

**Dependencies:**
- temp_session.php (session management)

**HTTP Methods Supported:**
- GET: Retrieve profile information
- PUT: Update profile data

**Key Features:**
- Manager profile management
- User profile operations
- Session-based authentication
- Secure profile updates

**Main Actions:**
- `manager_profile` - Get manager's profile information
- `user_profile` - Get specific user's profile
- `update_manager_profile` - Update manager account details
- `update_user` - Update user account information

**Security Features:**
- Session validation
- Role-based access control (MANAGER role verification)
- Input sanitization and validation

---

## API Endpoints

### URL Structure
All API calls follow this pattern:
```
/server/api.php?endpoint={controller}&action={action}
```

### Example Requests

#### Get Dashboard Statistics
```
GET /server/api.php?endpoint=dashboard&action=dashboard_stats
```

#### Add New Product
```
POST /server/api.php?endpoint=products&action=add_product
Content-Type: application/json

{
    "product_name": "Lipstick Red",
    "description": "Matte finish lipstick",
    "price": 25.99,
    "stock": 100,
    "category_id": 1
}
```

#### Update Manager Profile
```
PUT /server/api.php?endpoint=profile&action=update_manager_profile
Content-Type: application/json

{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone_number": "123-456-7890"
}
```

## Response Format

All controllers return standardized JSON responses:

```json
{
    "success": true|false,
    "message": "Descriptive message",
    "data": {} // Optional data payload
}
```

## Error Handling

- **400**: Bad Request (validation errors)
- **401**: Unauthorized (authentication required)
- **404**: Not Found (resource/action not found)
- **405**: Method Not Allowed
- **500**: Internal Server Error

## Security Features

- Input sanitization on all controllers
- SQL injection prevention using prepared statements
- XSS protection through HTML entity encoding
- Session-based authentication
- Role-based access control
- CORS configuration for cross-origin requests

---

*Last updated: September 2025*

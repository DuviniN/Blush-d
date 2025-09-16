# Blush-d

Blush'd is a responsive cosmetic e-commerce website built with HTML, CSS, PHP, and MySQL. Customers can browse beauty products, view details, add items to cart, and place orders. The admin panel manages products, inventory, and orders, ensuring a smooth, user-friendly shopping experience with dynamic content and CRUD functionality.

## Features

### Customer Features
- **Product Browsing**: Browse and search through BLUSH-D cosmetic products
- **Product Details**: View detailed product information with images and descriptions
- **Shopping Cart**: Add products to cart and manage quantities
- **User Reviews**: View customer reviews and ratings for products
- **Responsive Design**: Mobile-friendly interface with modern pink theme
- **User Authentication**: Secure login and registration system

### Admin/Manager Features
- **Product Management**: Add, edit, and delete products with category management
- **Inventory Control**: Track and manage product stock levels
- **Order Management**: View and process customer orders
- **Reports**: Generate sales and inventory reports
- **User Management**: Manage customer accounts and access levels

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 8.2
- **Database**: MySQL
- **Design**: Responsive design with pink theme (#d81b60, #f48fb1)
- **Icons**: Font Awesome for professional iconography

## Project Structure

```
Blush-d/
├── assets/                 # Static assets (CSS, JS, Images)
├── components/            # Reusable components
│   ├── footer/           # Footer component
│   ├── navigation/       # Navigation component
│   └── manager/          # Manager dashboard components
├── pages/                # Application pages
│   ├── admin/           # Admin dashboard
│   ├── customer/        # Customer pages
│   ├── manager/         # Manager dashboard
│   ├── login/           # Authentication pages
│   └── register/
├── server/               # Backend API controllers
└── database files       # SQL initialization files
```

## Installation

1. **Prerequisites**
   - XAMPP or similar local server environment
   - PHP 8.2 or higher
   - MySQL database

2. **Setup**
   ```bash
   # Clone the repository
   git clone <repository-url>
   
   # Move to XAMPP htdocs
   cp -r Blush-d c:\xampp\htdocs\
   ```

3. **Database Setup**
   - Import `init.sql` to create the database structure
   - Import `database_password_tables.sql` for initial data

4. **Configuration**
   - Update database connection settings in `server/config/db.php`
   - Start XAMPP services (Apache, MySQL)

5. **Access**
   - Open `http://localhost/Blush-d` in your browser

## API Endpoints

- **Products**: `GET/POST/PUT/DELETE /server/api.php?endpoint=products`
- **Categories**: `GET/POST/PUT/DELETE /server/api.php?endpoint=categories`
- **Reviews**: `GET /server/api.php?endpoint=reviews`
- **Reports**: `GET /server/api.php?endpoint=reports`

## Color Theme

The website uses a modern pink color scheme:
- Primary: `#d81b60`
- Secondary: `#f48fb1`
- Accent colors: `#ec407a`, `#d63384`
- Text: `#000000`, `#222222`, `#ffffff`

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is developed for educational and commercial purposes.Blush-d

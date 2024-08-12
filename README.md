---

### Short Description

**Parking Reservation System** is a web-based application developed using PHP, MySQL, HTML, CSS, and JavaScript. The system allows users to book parking spots online, manage reservations, and handle administrative tasks, providing a complete solution for parking management.

---

### README

# Parking Reservation System

**Parking Reservation System** is a comprehensive web application designed to manage parking reservations for users and administrators. This project utilizes PHP for server-side processing, MySQL for database management, and HTML/CSS/JavaScript for the frontend interface. The system supports user registration, parking spot booking, and administration of reservations.

## Project Overview

The project is structured into various folders and files that handle different aspects of the parking reservation system. It includes features such as user authentication, booking management, payment processing, and administrative functions for managing users and parking spots.

### Key Features

- **User Registration and Login**: Users can register and log in to book parking spots.
- **Parking Spot Booking**: Allows users to search for available parking spots and make reservations.
- **Payment Processing**: Supports payment for reserved parking spots.
- **Admin Management**: Admins can add, edit, or delete users and parking spots. They can also view and confirm bookings.
- **Responsive Design**: The application is designed to work seamlessly on various devices.

## Repository Structure

- **CSS**: Contains the CSS files for styling the web pages.
- **database**: Includes SQL files for setting up the database schema and tables.
- **img**: Contains image assets used in the application.
- **js**: JavaScript files for client-side functionalities like form validation and dynamic content.
- **recycle bin**: Possibly includes backup or archived files.
- **txt files**: Contains text files with documentation or other relevant information.
- **uploaded_img**: Directory for storing images uploaded by users, such as profile pictures or vehicle images.
- **PHP Files**: The root directory contains PHP scripts that handle different functionalities:
  - `config.php`: Handles database connection settings.
  - `register.php`, `login.php`, `logout.php`: Manage user authentication.
  - `bookingpage.php`, `paymentpage.php`, `paymentsuccess.php`: Handle the booking process and payment flow.
  - `admin_page.php`, `add_admin.php`, `delete_admin.php`, `edit_user.php`: Admin functionalities for managing users and bookings.
  - `homepage.php`, `contactpage.php`, `aboutpage.php`: Frontend pages for user interaction.

## Getting Started

### Prerequisites

To run this project, you will need:

- **PHP**: A server running PHP 7.4 or later.
- **MySQL**: MySQL database for storing user and booking data.
- **Apache**: Recommended web server, typically included with XAMPP or WAMP.
- **Browser**: A modern web browser to view and interact with the application.

### Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/MatasT-uni/parking-reserved-project-Y3-T1.git
   cd parking-reserved-project-Y3-T1
   ```

2. **Set Up the Database**:
   - Import the SQL files from the `database` folder into your MySQL database to set up the necessary tables and schema.
   - Update the `config.php` file with your database credentials.

3. **Run the Application**:
   - Place the project folder in your web server's root directory (e.g., `htdocs` for XAMPP).
   - Open a web browser and navigate to `http://localhost/parking-reserved-project-Y3-T1`.

### Usage

- **User Registration**: New users can register through the registration form.
- **Booking Parking Spots**: Once logged in, users can search for available spots and make reservations.
- **Admin Panel**: Admins can log in to manage users, bookings, and parking spots.


## Contributors

- Matas Thanamee - Repository owner and main developer.

---

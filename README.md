# User Account Management and Moderation System (Web Application)

## Project Description

This web application provides robust user account management with an integrated moderation system tailored for scenarios involving offline app usage control and service access limitation. It facilitates user registration, login, account creation, moderation, and extensive user list management, primarily designed for handling license key distribution and offline app usage with online moderation.

## Key Features

- **User Registration and Login:**
  - Effortlessly allows users to register for new accounts and securely log in using their credentials.
- **Account Creation:**
  - Administrators can generate multiple user accounts in bulk, specifying the number of accounts needed.
- **Moderation System:**
  - Empowers administrators to designate certain users as moderators, granting them additional privileges and responsibilities.
- **User List Management:**
  - Offers a comprehensive view of all user accounts, including pertinent details such as username, email, usage statistics, and moderation status.
- **Service Usage Control:**
  - Enables moderators to adjust an arithmetic value associated with each user account, representing the number of times the account can access a specific service. This functionality is crucial for scenarios involving offline app usage control, where the value is passed to the app for usage tracking.
- **Offline Usage Tracking:**
  - Maintains a record of the total number of service uses per account, along with the remaining offline usage count after each usage exchange.
- **Real-time Updates:**
  - Provides real-time updates on usage adjustments, ensuring accurate tracking of service usage and offline access control.
- **User Removal:**
  - Allows administrators to remove user accounts from the system as necessary, maintaining efficient management of the user base.

## Technologies Used

- **PHP:**
  - Utilized for backend scripting, encompassing server-side logic and seamless database interactions.
- **HTML/CSS:**
  - Employed for frontend markup and styling, ensuring an intuitive and visually appealing user interface.
- **MySQL:**
  - Serves as the relational database management system for storing user account information, moderation data, and usage statistics.

## Usage Scenario

Designed specifically for scenarios involving license key distribution and offline app usage control, the application streamlines the process of managing user accounts, regulating service access, and ensuring effective moderation through online mechanisms.

## Getting Started

To get started with the project:

1. Clone or download the project repository.
2. Set up a web server environment with PHP and MySQL support.
3. Import the provided database schema into your MySQL database.
4. Configure the database connection settings in the PHP files accordingly.
5. Deploy the project to your web server.
6. Access the application through your web browser and start managing user accounts and moderation.

## Contributions

Contributions to this project are currently not accepted.


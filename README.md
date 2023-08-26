
## This Project is Under Development.


## Invextry

Inventory Management System With Laravel & Vue JS

![Open Source Income Expense Tracker](https://raw.githubusercontent.com/efthakhar/invextry/master/public/img/invextry-ss1.png)


# Installation Guide

Follow these steps to set up and run the project locally.

### Prerequisites

Make sure you have the following installed:

- Git
- Composer
- Node.js and npm
- PHP >= 8.1
- MySQL 

### Installation Steps

1. **Clone the GitHub repository**
   ```sh
   git clone https://github.com/efthakhar/invextry.git
   
2. **Install Composer packages:**
   ```sh
   composer install
   
3. **Install npm packages**
   ```sh
   npm install
   
4. **Create .env file**

   Duplicate .env.example and rename it to .env.
   
5. **Generate encryption key**

    Run the following command to generate a unique application key.
    
      ```sh
    php artisan key:generate

6. **Update APP_URL in .env**

    Open .env file and update the APP_URL value according to your application's domain path. For local development, it will typically be:
    
      ```sh
    APP_URL=http://127.0.0.1:8000

7. **Run migrations**

      ```sh
    php artisan migrate

8. **Start the development server**

      ```sh
    php artisan serve

9. **Enable development mode**

      ```sh
    npm run dev

Now you're all set up! Access your application through the specified APP_URL.
   

## Contributor

- [Efthakhar Bin Alam](https://github.com/efthakhar). (efthakharbin@gmail.com)
   

## License
- Copyright © 2023 Invextry
- **Invextry is open-sourced software licensed under the MIT license**

## Acknowledgments

This project wouldn't be possible without the amazing contributions of the following open-source projects:


- [voler](https://github.com/zuramai/voler): Bootstrap5 Admin Template 
- [apexcharts/vue3-apexcharts](https://github.com/apexcharts/vue3-apexcharts): Vue-3 component for ApexCharts 
- [RemixIcon](https://github.com/Remix-Design/RemixIcon): Open source neutral style icon system 
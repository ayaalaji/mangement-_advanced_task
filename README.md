# the name of project
Advanced Task Management System
# step to run the project 
1- add data base name in phpmyadmin
2- add the database name in .env
3- run the project in termial using this step:
   1 . php artisan config:cache
   2. php artisan config:clear
   //we put this step above (1+2) because in my project i put database name but you want to add new data so this is to clear .env and allow you to put your database
   3. php artisan migrate
   4. php artisan db:seed --class=PermissionSeeder
   4. php artisan db:seed --class=UserSeeder
   5. php artisan cache:table
# Features
i use JWT package for Api   
# what about this project
This project is about advanced task management.
 there are three roles: admin, manager, and user. 
 I have three managers, each overseeing a specific type of task. 
 Additionally, there are tasks added by the admin, but the status of the task is managed by the user to whom the task is assigned. The manager can only see the tasks they supervise. There is also a soft delete feature, allowing us to recover or permanently delete tasks. 
 Furthermore, attachments can be added, where a manager can attach a file to a task, provided that they are the one supervising that task. 
 There is also a dependency feature, meaning that a task depends on another task and cannot be executed until the primary task is completed. 
 The admin is responsible for adding users to the system, and I also have middleware for security.
# doc of postman is 
https://documenter.getpostman.com/view/34555205/2sAXxV7WTh
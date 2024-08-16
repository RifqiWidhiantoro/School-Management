Project Structure and File Descriptions

This project is a student profile management system built with PHP and MySQL. Below is a breakdown of the project structure and the purpose of each file:

1. Asset/
   Purpose: This folder contains assets such as images  and other resources used in the project. The original_image/ and thumbnail_image/ subdirectories store the original and resized images uploaded by users.

2. Connection.php
Purpose: Handles the database connection. It establishes a connection to the MySQL database using mysqli_connect().

3. Data.php: Empty description

4. Database.php
Purpose: Contains functions or scripts related to database initialization or migrations. This file may create tables or manage schema updates.

5. Delete.php
Purpose: Handles the deletion of a student's profile. It receives the student ID via a GET request, deletes the associated records from the database, and redirects the user to the student list.

6. Edit.php 
Purpose: Displays a form for editing a student's profile. It pre-fills the form with the current data from the database and allows users to update the profile, including uploading a new profile picture.

7. Index.php
Purpose: The main entry point of the application. This file could be the landing page or the login page, depending on your application flow.

8. Profil.php
Purpose: Displays the detailed profile of a student. It shows all the information stored about the student, including their name, gender, religion, class, major, hobbies, and profile picture.

9. simpan.php
Purpose: Handles the saving of new student data to the database. This file processes the data submitted from the student creation form and inserts it into the database.

10. siswa.php
Purpose: Lists all students in the database. It might display a table with students' names and offer options to view, edit, or delete each profile.

11.update.php
Purpose: Processes the data submitted from the edit.php form. It updates the student's information in the database, handles the uploading of new profile pictures, and redirects the user back to the updated profile.

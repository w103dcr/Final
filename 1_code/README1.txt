1. Download the correct version of XAMPP for your operating system (Windows, Linux, or iOS) from here https://www.apachefriends.org/download.html

2. Install XAMPP with default/all setup options

3. Choose your install directory and remember this path for later (I use C:\xampp)

4. Copy the EPMS folder from my repository into the htdocs folder located inside of your XAMPP location (Mine is C:\xampp\htdocs making the full file path C:\xampp\htdocs\epms)

5. Launch the program that was installed called XAMPP Control Panel

6. Click "Start" next to Apache (wait 5-10 seconds for it to start, a green box will appear around the word Apache)

7. Click "Start" next to MySQL (wait 5-10 seconds for it to start, a green box will appear around the word MySQL)

8. Navigate to http://localhost/phpmyadmin/

9. Click new in the left-hand column to create a new database

10. Where it says "database name" type epms and click create

11. Ensure the epms database is selected on the left and then click Import from the ribbon at the top of the screen

12. From within the import tab click the "Browse" button and navigate to the epms.sql file that is in the epms folder you copied (mine is C:\xampp\htdocs\epms\epms.sql)

13. Then click the "Open" button, scroll to the bottom of the import tab and click the "Import" button

14. After the import is done you can now navigate to http://localhost/epms/ and the system should be at a login page

15. Users can log in with a non-editing user 
	username: User
	password: User

    or with an editing user
	username: Admin
	password: Edit
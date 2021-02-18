Name: Hung Jie Song
Github Link: https://github.com/hungjsong/Flight-Regisitry


ACCOUNT CREDENTIALS:
Username - admin
Password - pass123


DATABASE INSTRUCTIONS:
The database file name is "id13928633_admin.sql". The name of the database is id13928633_admin.
When imported there should be 5 tables: airline, country, crew, flight, and flight_crew. To import the database,
make sure you have xampp up and running (see other instructions point 2). Once that's done, 
type localhost/phpmyadmin into the url of your browser. On the top left, there should be a button that says new. 
Click on that and name the database "id13928633_admin". After that, click on the database, click the import tab at the tab, 
choose file, and select the database file "id13928633_admin.sql"


OTHER INSTRUCTIONS:
1. The folder containing all the files is called "FlightRegistry". Please drag this file and drop it 
into the file titled "htdocs" within the folder "xampp".

2. You will need to turn on the modules Apache and MySQL, which can be accessed via the XAMPP Control Panel 
(launched via the executable "xampp-control.exe" within the folder "xampp".

3. Type localhost into your browser's address bar, and click on the folder "FlightRegistry" to open the website.

4. Just as a pre-caution, make sure to have an internet connection when running the website on an incognito window.
Without the internet, it can cause one of the CDNs to give an error, which is absent on a non-incognito window.


GENERAL WALKTHROUGH:
Here's a general walkthrough you can take to testing the website's functionalities:
1. Login with username admin (password-> pass123)
2. Access either the flight or crew member list by clicking the respective navigation bar item at the top left.
3. To register a new flight or crew member, access the view actions at the top right corner to the left of the search bar.
4. Editing or deleting a flight or crew member can be done via pressing the buttons associated to the row it is on.
5. Assigning a crew member to or removing them from a flight can be done via the assign/remove button.
6. The list for flights/crew members updates as the search term is being typed in. Matching rows will be highlighted.


OTHER NOTES:
Each flight can only have a maximum of 5 crew members assigned to it.

If you have any questions, please let me know. I hope you enjoy testing out the system.
- Hung Jie Song

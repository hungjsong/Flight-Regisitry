# Flight Registry

### Description

The flight registry is an app that allows users to add flights, add crew members, and assign crew members to flights. Each flight can only have a maximum of 5 crew members assigned to it. There are three views: summary, flight list, and crew member list.

In the summary view users can view a list of all flights and their respective crew members.

In the flight list view users can view the details of each flight, which includes info such as the airline name, flight number, number of seats, country (where the airline is based), the flight's call sign, and crew count. Users can also update the details of the flight or delete the flight from the registry.

In the crew member list view users can view a list the details of each crew member, which includes info such as their name, age, gender, nationality, and their assigned flight (if they are assigned one). Similar to flights, users can update a crew member's details or remove delete them from the registry. Users can assign to, remove from, or reassign crew members to flights.

A list of the flights and crew members can be downloaded as an .xlsx file under the actions tab above. Users may search through either list with keywords, and matching details will be highlighted in returned entries.

### Demo

### Instructions to Run Locally

#### ACCOUNT CREDENTIALS:

Username - admin
Password - pass123

#### DATABASE INSTRUCTIONS:

The database file name is "id13928633_admin.sql". The name of the database is id13928633_admin.
When imported there should be 5 tables: airline, country, crew, flight, and flight_crew. To import the database,
make sure you have xampp up and running (see other instructions point 2). Once that's done,
type localhost/phpmyadmin into the url of your browser. On the top left, there should be a button that says new.
Click on that and name the database "id13928633_admin". After that, click on the database, click the import tab at the tab,
choose file, and select the database file "id13928633_admin.sql"

#### OTHER INSTRUCTIONS:

1. The folder containing all the files is called "FlightRegistry". Please drag this file and drop it
   into the file titled "htdocs" within the folder "xampp".

2. You will need to turn on the modules Apache and MySQL, which can be accessed via the XAMPP Control Panel
   (launched via the executable "xampp-control.exe" within the folder "xampp".

3. Type localhost into your browser's address bar, and click on the folder "FlightRegistry" to open the website.

4. Just as a pre-caution, make sure to have an internet connection when running the website on an incognito window.
   Without the internet, it can cause one of the CDNs to give an error, which is absent on a non-incognito window.

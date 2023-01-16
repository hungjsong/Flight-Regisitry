# Flight Registry

## Description
The flight registry is an app that allows users to add flights, add crew members, and assign crew members to flights. Each flight can only have a maximum of 5 crew members assigned to it. There are three views: summary, flight list, and crew member list.

In the summary view users can view a list of all flights and their respective crew members.

In the flight list view users can view the details of each flight, which includes info such as the airline name, flight number, number of seats, country (where the airline is based), the flight's call sign, and crew count. Users can also update the details of the flight or delete the flight from the registry.

In the crew member list view users can view a list the details of each crew member, which includes info such as their name, age, gender, nationality, and their assigned flight (if they are assigned one). Similar to flights, users can update a crew member's details or remove delete them from the registry. Users can assign to, remove from, or reassign crew members to flights.

A list of the flights and crew members can be downloaded as an .xlsx file under the actions tab above. Users may search through either list with keywords, and matching details will be highlighted in returned entries.

## Demo

### Summary View
![summaryView](https://user-images.githubusercontent.com/75509901/212735813-9c6579a3-7d0a-4149-81bd-7ad668c2380d.png)

### Flight List View
![flightListView](https://user-images.githubusercontent.com/75509901/212736118-bee0e037-3aba-47d7-8779-fbf026710001.png)

#### Add New Flight
##### New flights must have more than 100 seats and cannot share the same flight number as another flight of the same airline.
<img src="https://user-images.githubusercontent.com/75509901/212740792-6011a133-8dea-4521-83ee-a1cb74a475dd.gif" height="370">

##### Attempting to use the same flight number as another flight of the same airline
<img src="https://user-images.githubusercontent.com/75509901/212740968-fa017ca0-0ad8-4778-aced-bd4c12a3805a.gif" height="370">

#### Update Flight
<img src="https://user-images.githubusercontent.com/75509901/212741149-ffba5aeb-4f95-4084-b6d6-fa4b8ae33154.gif" height="370">

#### Delete Flight
##### Deletion message changes depending on how many members there are (or lack thereof)
<img src="https://user-images.githubusercontent.com/75509901/212741315-5095ae74-6eac-473c-bd24-73afa15369a6.gif" height="370">

#### Search Flight List
<img src="https://user-images.githubusercontent.com/75509901/212741615-45e825e0-e230-4bd2-8551-54a5245db38a.gif" height="370">

#### Download Flight List
<img src="https://user-images.githubusercontent.com/75509901/212744156-4ae50128-cf6b-4c35-9c34-21fa908bcee1.gif" height="370">

##### Flight List Excel File
<img src="https://user-images.githubusercontent.com/75509901/212743570-4f9107ad-584e-43ff-a541-0a6d6444206d.PNG" height="370">

### Crew Member List View
![crewMemberListView](https://user-images.githubusercontent.com/75509901/212736138-02359200-eb6a-42fd-b66c-449016d15ce3.PNG)

#### Add Crew Member
<img src="https://user-images.githubusercontent.com/75509901/212741631-6f2c6545-6af6-4159-b6c8-861e0ef092c7.gif" height="370">

#### Update Crew Member
<img src="https://user-images.githubusercontent.com/75509901/212741644-e56c430e-391b-4264-be77-f40779a05ae0.gif" height="370">

#### Delete Crew Member
<img src="https://user-images.githubusercontent.com/75509901/212741659-16210de7-9040-42d5-8ec3-d302dbaecba9.gif" height="370">

#### Assign Crew Member to Flight
##### Changes to crew member flight assignment are reflected in the summary view
<img src="https://user-images.githubusercontent.com/75509901/212741670-876faef1-f6bb-4c4d-b107-f616dfec8e79.gif" height="370">

#### Remove Crew Member from Flight
<img src="https://user-images.githubusercontent.com/75509901/212741682-1c9cea60-517c-427f-a595-831da4507e72.gif" height="370">

#### Search Crew Member List
<img src="https://user-images.githubusercontent.com/75509901/212741700-3685b240-e5c8-446e-9ba5-479940eafcbf.gif" height="370">

#### Download Crew Member List
<img src="https://user-images.githubusercontent.com/75509901/212742578-38637da0-aa24-481d-b9c0-af2794a10670.gif" height="370">

##### Crew Member List Excel File
<img src="https://user-images.githubusercontent.com/75509901/212742734-e45d3a91-f0f1-4915-bafa-bc1ebf6b6ced.PNG" height="370">

## Instructions to Run Locally

### ACCOUNT CREDENTIALS:

Username - admin
Password - pass123

### DATABASE INSTRUCTIONS:

The database file name is "id13928633_admin.sql". The name of the database is id13928633_admin.
When imported there should be 5 tables: airline, country, crew, flight, and flight_crew. To import the database,
make sure you have xampp up and running (see other instructions point 2). Once that's done,
type localhost/phpmyadmin into the url of your browser. On the top left, there should be a button that says new.
Click on that and name the database "id13928633_admin". After that, click on the database, click the import tab at the tab,
choose file, and select the database file "id13928633_admin.sql"

### OTHER INSTRUCTIONS:

1. The folder containing all the files is called "FlightRegistry". Please drag this file and drop it
   into the file titled "htdocs" within the folder "xampp".

2. You will need to turn on the modules Apache and MySQL, which can be accessed via the XAMPP Control Panel
   (launched via the executable "xampp-control.exe" within the folder "xampp".

3. Type localhost into your browser's address bar, and click on the folder "FlightRegistry" to open the website.

4. Just as a pre-caution, make sure to have an internet connection when running the website on an incognito window.
   Without the internet, it can cause one of the CDNs to give an error, which is absent on a non-incognito window.

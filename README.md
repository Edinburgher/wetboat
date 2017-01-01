# Welcome to WETBoat
This is a school project for HTBLuVA (or HTL) Salzburg.  

WETBoat ('Water Evaluation and Transmission Boat') is a boat that measures data and takes pictures every few seconds so it acts as a weather station and a lake webcam at the same time.
It swims automatically and you can set the route on the website if you're logged in.  

The measurement data is displayed in the Highcharts.js live updating line graph.  
You can see the boat's position in the map at the bottom.  

You can find the implementation of the website [here](http://www.wetboat.at). 

# What you need
To run it you need [XAMPP](https://www.apachefriends.org/download.html) or any other webserver with MySQL and PHP.  

# Usage
## Method using XAMPP
Put all the files into (assuming Windows) C:\xampp\htdocs\wetboat and in XAMPP start the Apache and MySQL server.  
Go to [phpmyadmin](http://localhost/phpmyadmin/) -> SQL and copy and paste [wetboat.sql](wetboat/wetboat.sql) and click OK.  
After that, go to [localhost/wetboat](http://localhost/wetboat) and you should see index.php.  
Log in (top right) with:  
* Username: admin
* Password: admin

You can change the password in admin.php at the bottom.  

If you want to see the graph in index.php moving, you have to put new data in the table measurements every few seconds.  

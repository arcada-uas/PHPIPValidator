# IP Validation in PHP

### This is a helper class for ip validation written in php. 

#### Requirements 
* Written and tested on PHP 7.4. 
* Mellon auth is required.

#### HOWTO 
1. To use this project make a copy of cidr.ini_template named cidr.ini
2. Enter the CIDR:s you want to allow in the cidr.ini 
3. Configure either as a helper class or as a direct output for testing
4. Set debug flag

Intended use is to use this as a helper class for a webpage that you want to filter by users location.
Fairly easy to copy the ipValidator class and use the provided index.php as a template for your own project.


Code by Johannes Edgren (2022). 
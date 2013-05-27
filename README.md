This is the source code behind http://data.vehiclefits.com - Open Source Automotive Data.

Install
---------------------
To install, first clone our repo. Then install the dependencies with composer. From there, use the vf tool to create the year/make/model tables, and to import sample data for testing (or production). Lastly, create additional non year/make/model (user, etc..) tables with the provided DLL (SQL file).

    git clone git@github.com:vehiclefits/data.vehiclefits.com.git
    composer update --dev
    vendor/bin/vf schema --levels="country,category1,category2,make,model,option,year"
    vendor/bin/vf importvehicles path/to/sample-data.csv
    mysql vfdata < vfdata.sql
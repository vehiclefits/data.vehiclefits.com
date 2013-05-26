This is the source code behind http://data.vehiclefits.com - Open Source Automotive Data.

Install
---------------------

    git clone git@github.com:vehiclefits/data.vehiclefits.com.git
    composer update --dev
    vendor/bin/vf schema --levels="country,category1,category2,make,model,option,year"
    vendor/bin/vf importvehicles path/to/sample-data.csv
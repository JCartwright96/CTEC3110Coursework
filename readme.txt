Database tables can be setup by running /create_db.sql inside php. Please create/use your preferred database before running.

To run the application please update database settings in phpappfolder/includes/m2m/app/settings.php

Please also ensure that all the composer dependencies have been installed.

Ensure that files all have the appropriate permissions and ownership.

To send a message to the application please use the following format.

{ "18-3110-AF" : { "switch_1": "1", "switch_2": "1", "switch_3": "0", "switch_4": "1", "fan": "forward", "heater": "29", "keypad": "3" }}
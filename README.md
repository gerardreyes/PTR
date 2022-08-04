# PTR
This accepts a CSV of Name and TIN. Process it and outputs a PTR Excel File with the Name, TIN, and address filled up ready for printing.

## Specs
* Windows OS only
* php_com_dotnet.dll PHP extension enabled

### Database Setup
``` bash
# No migration for this. Need to create one.
Manually create a new table called ptr under ptr database.
Columns:
* name - varchar(255)
* tin - varchar(255)
```

### Application Setup
``` bash
# Prepare file for processing
* Fill up Process_me.csv with Name and TIN.
* Make sure that there are no commas in the name.

# If you are using Laravel Valet, park the directory.
valet park
```

### Application Run
Open your browser and go to: 
```
https://ptr.test/ptr.php
```

### Important Files
* Process_me.csv - The file for processing.
* PTR_output.xlsx - The template for output.
* PTR.php - The program.

## Project Details
Feel free to email `gerardreyes112@gmail.com` for any inquiries regarding this project.

## License
* GNU GENERAL PUBLIC LICENSE Version 3
* https://github.com/gerardreyes
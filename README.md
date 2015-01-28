magento
=======

Mondido Payments plugin for Magento  
Version 1.0  

**Instructions**

Run the setup script below. It copies the files from the downloaded module to their corresponding locations within your Magento installation.

```sh
#!/bin/bash
mondido_dir="magento-master"
magento_dir="/var/www/magento"

wget https://github.com/Mondido/magento/archive/master.zip -O mondido.zip
unzip mondido.zip

cp -a $mondido_dir/mondido/* $magento_dir
rm -rf $mondido_dir
```

In order to make the module "LIVE", follow the instructions below:  

1. Login to the Magento Administrator console  
2. Using the main menu, navigate to System > Configuration  
3. Using the left menu, navigate to Sales > Payment Methods  
4. Under the "Mondido" heading, update the settings from your merchant account.  
    4.1 In "Enabled", select "Yes"   
    4.2 In "Merchant ID", type your merchant ID, e.g., 140  
    4.3 In "Merchant Secret", type your secret for hash generation, e.g., $2b$30$fAJfajudaojJFSUI  
    4.4 In "Checkout Image URL", type the URL of the image to appear in the Payment Information Checkout Step
    4.5 In "Checkout Text", type the text to appear in the Payment Information Checkout Step   
5. Click "Save Config"

You may want to disable caching if you run into troubleshooting during the installation.  
To disable cache, access System > Cache Management, select all boxes, mark disable and save.
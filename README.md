magento
=======

Mondido Payments plugin for Magento  
Version 1.2  

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
    ![System Configuration](/installation_screenshots/system_configuration.png?raw=true)  
3. Using the left menu, navigate to Sales > Payment Methods  
    ![Payment Methods](/installation_screenshots/configuration_sales_payment_methods.png?raw=true)  
4. Under the "Mondido" heading, update the settings from your merchant account.  
    ![Mondido Settings](/installation_screenshots/mondido_settings.png?raw=true)  

    4.1 In "Enabled", select "Yes"   
    4.2 In "Merchant ID", type your merchant ID, e.g., 140  
    4.3 In "Merchant Secret", type your secret for hash generation, e.g., $2b$30$fAJfajudaojJFSUI  
    4.4 In "Checkout Image URL" and "Checkout Text" will appear in this checkout page:  
        ![Checkout](/installation_screenshots/checkout.png?raw=true)  

      4.4.1 The Image URL and the text are optional, but you need to fill at least one. Otherwise the Mondido Payment Information will be empty.  

      4.4.2 The text accepts HTML code if you want to customize.  

      4.4.3 If you fill the image and the text fields, there will be two "&lt;br/&gt;" (break line tags)  between them, as shown above.  

5. Click "Save Config" in the top right corner of the screen  
    ![Save Settings](/installation_screenshots/save_config.png?raw=true)  

You may want to disable caching if you run into troubleshooting during the installation.  
To disable cache, access System > Cache Management, select all boxes, mark disable and save.

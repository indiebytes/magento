magento
=======

Mondido Payments plugin for Magento  
Version 1.0  

**Instructions**

1. Run the setup script below. It copies the files from the downloaded module to their corresponding locations within your Magento installation.

```sh
#!/bin/bash
magento_branch="magento-master"
magento_dir="/var/www/magento"

wget https://github.com/Mondido/magento/archive/master.zip -O mondido.zip
unzip mondido.zip

cp -a $magento_branch/mondido/* $magento_dir/
rm -rf $magento_branch
```

In order to make the module "LIVE", follow the instructions below:  

1. Login to the Magento Administrator console  
2. Using the main menu, navigate to System > Configuration  
3. Using the left menu, navigate to Sales > Payment Methods  
4. Under the "Mondido" heading, update the settings from your merchant account.  
    4.1 In "Enabled", select "Yes"  
    4.2 In "Title", type the name of your company  
    4.3 In "Merchant ID", type your merchant ID, e.g., 140  
    4.4 In "Merchant Secret", type your secret for hash generation, e.g., $2b$30$fAJfajudaojJFSUI  
5. Click "Save Config"

You may want to disable caching if you run into troubleshooting during the installation.  
To disable cache, access System > Cache Management, select all boxes, mark disable and save.
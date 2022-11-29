# magento2-create-new-module-template

Type:

``git clone git@github.com:dawidofed/magento2-create-new-module-template.git ModuleCreator``

in app/code/DawidoFed catalog

And now:

``bin/magento module:enable DawidoFed_ModuleCreator``
``bin/magento setup:upgrade``
``bin/magento setup:di:compile``
``bin/magento c:c && bin/magento c:f``

Now you will be able to create a basic template for a new module with the command:

``bin/magento module:create --name DawidoFed_Test``

A new module will be created in the app/code/DawidoFed/Test directory. There you will find the registration.php and etc/module.xml files.

**ATTENTION!**

Do not use this module in production. You can also freely develop it and give a push if you do something cool. :)


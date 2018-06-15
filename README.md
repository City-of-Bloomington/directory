Directory
=======

This web application serves as the web interface for serving contact information about staff.  All the organization information comes from ActiveDirectory or LDAP.  Users can log in and update a limited set of information about themselves.

It also provides a web service API for other city applications to consume.  For instance, our Drupal module uses this web service to show phone numbers for people and departments on the city website.

https://github.com/City-of-Bloomington/drupal-module-directory

It integrates with New World ERP system (now owned by Tyler), so that new employees added to the HR system can be synchronized with ActiveDirectory.

https://www.tylertech.com/solutions-products/new-world-erp-product-suite

It can export emergency contact information for users into the Everbridge Ciritcal Event Management Platform.

https://www.everbridge.com/

Better integration is planned for both Tyler's New World and the Everbridge notification system.

### Requirements
* linux
* apache
* mysql >= 5.5
* php >= 7.0
* unixodbc  (for connecting to MS Sql Server)


### Development requirements
* composer
* gettext
* sass

We use pysassc in our Makefile, but any SASS compiler will be fine.

# Mage2 Module Parc CorrectUrlKeys

    ``parc/module-correcturlkeys``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Searches for products in different stores and corrects the url key if needed

## Installation
1. Install via composer
   ```composer require parcnetwork/magento2-update-urlkeys```

### Type 1: Zip file

 - Unzip the zip file in `app/code/Parc`
 - Enable the module by running `php bin/magento module:enable Parc_CorrectUrlKeys`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require parc/module-correcturlkeys`
 - enable the module by running `php bin/magento module:enable Parc_CorrectUrlKeys`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration




## Specifications




## Attributes



=======
# magento2-update-urlkeys

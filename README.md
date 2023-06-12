# Magento 2 Update URL Keys

The Magento 2 Update URL Keys module allows you to update URL keys for products, categories, and CMS pages in Magento 2. It provides an easy way to update the URL keys based on the current name or custom rules.

## Features

- Update URL keys for products
- Updates are done via Cron or CLI
- Based on the current products name
- Supports bulk updating for multiple items at once
- Easy to install and use

## Requirements

- Magento 2.x

## Installation

Run the following Composer command to install the module:

```shell
composer require parc/update-urlkeys
```
Enable the module by running the following commands:

```shell
php bin/magento module:enable Parc_UpdateUrlKeys

php bin/magento setup:upgrade
```
Flush the Magento cache by running the following command:

```shell
php bin/magento cache:flush
```

## Usage

**1. Backend**

Log in to your Magento admin panel.
Go to **Stores > Configuration > Parc Network > Update Url Keys**.
Select the store views and the desired update method, 
which can differ between multiple store views
Configure your own cronjob interval and save the configuration


**2. CLI**
    
Inside your Magento2 root, hit **bin/magento parc:updateurlkeys**

A list of all possible parameters can be found via **-h / --help** or below:

![Screenshot 2023-06-12 at 12.22.00 PM.png](..%2F..%2F..%2F..%2F..%2F..%2FDesktop%2FScreenshot%202023-06-12%20at%2012.22.00%20PM.png)

I would only recommend using the CLI **when**:
- you don't want to wait until the cron would be executed the next time.
- you want to create an overview via CSV, which changes are made, regarding the url keys
- you want to update the url key for a single product only

## Support
If you encounter any issues or have any questions, please create an issue on the GitHub repository.

## Contributing
Contributions are welcome! If you would like to contribute to the project, please fork the repository and submit a pull request.

## License
This module is licensed under the MIT License.

# Magento 2 Update URL Keys

The Magento 2 Update URL Keys module allows you to update URL keys for products in Magento 2. It provides an easy way to update the URL keys based on the current name.

When the name of a product changes, the URL key associated with that product should be updated accordingly. Changes to the product name happen frequently, and it is important to keep the URL key in sync to ensure consistent and accurate URLs for the product.

The URL key is a unique identifier used in the URL of a product page. It is generated based on the product name and is used to create a user-friendly and search engine-friendly URL. When the product name is modified, the URL key should be updated to reflect the new name.

This process can be automated by implementing a custom functionality that listens for product name changes and automatically updates the URL key. Whenever the name of a product is modified, the system should trigger the update of the URL key to match the new name.

By keeping the URL key up to date with the product name, you ensure that customers can access the product page using a meaningful and relevant URL, and search engines can properly index and rank the product in search results.

Implementing this functionality can help maintain consistency and improve the overall user experience of your online store.

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

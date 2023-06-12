# Magento 2 Update URL Keys

The Magento 2 Update URL Keys module allows you to update URL keys for products, categories, and CMS pages in Magento 2. It provides an easy way to update the URL keys based on the current name or custom rules.

## Features

- Update URL keys for products
- Based on the current products name
- Supports bulk updating for multiple items at once
- Easy to install and use

## Requirements

- Magento 2.x

## Installation

Run the following Composer command to install the module:

```shell
composer require parc/update-url-keys
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
Log in to your Magento admin panel.

Go to Stores > Configuration > Catalog > URL Rewrites.

Configure the module settings according to your requirements.

Save the configuration and the module will start updating the URL keys based on the configured rules.

## Support
If you encounter any issues or have any questions, please create an issue on the GitHub repository.

## Contributing
Contributions are welcome! If you would like to contribute to the project, please fork the repository and submit a pull request.

## License
This module is licensed under the MIT License.

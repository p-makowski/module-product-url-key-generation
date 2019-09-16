# ProductUrlKeyGeneration

A somewhat smarter approach for auto-generating URL keys for products

## Installation

Install using composer ([Packagist details](https://packagist.org/packages/marcuspi/module-product-url-key-generation))

```bash
composer require marcuspi/module-product-url-key-generation
```

## Usage

The module will autogenerate a URL key for any products that are created with a blank or missing url_key attribute.

You can select which language to use as a base for slug generation (ie, Austrian users will want `ß -> sz`, not `ß -> ss`) in the backend under Stores -> Configuration -> Catalog -> Catalog -> Search Engine Optimization. It defaults to the store language.

### Thanks
This module uses the amazing [slugify](https://github.com/cocur/slugify) library.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)

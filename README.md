# PSP-Mock

Mock service for payment providers. This project is in a alpha state and supports only PAYONE payments, yet.

## Getting Started

### Installing

Currently this project was only tested with Valet+ (https://github.com/weprovide/valet-plus) on local development
environment. 

```
git clone git@github.com:techdivision/psp-mock.git
cd psp-mock
composer install
valet link --secure
cp .env.dist .env
```

### Configuration

Change the settings within the `.env` file in order to configure the endpoints.

```
PAYONE_CALLBACK_URI=https://test.my-shop.local/payone/transactionstatus
```

### Using with Magento 2

In order to use this mock service with a Magento 2 installation you need to manipulate the endpoints within the
`payone-gmbh/magento2` extension. Therefore you can use the module: https://github.com/techdivision/magento2-payone-mockable

```bash
composer require --dev techdivision/payone-mockable
```

## License

This project is licensed under the OSL 3.0 License.

## Roadmap

| Feature / Task             | Status    |
|----------------------------|-----------|
| **PAYONE - Payments**      |           |
| - Creditcard               | ✓         |
| - Debit Payment            | X         |
| - PayPal                   | X         |
| - Cash on Delivery         | X         |
| - Advance Payment          | X         |
| - Invoice                  | X         |
| **PAYONE - Address Check** | X         |
| **PAYONE - Credit rating** | X         |
| **Rule based automation**  | X         |
| **other PSPs**             | X         |


# PSP-Mock

Mock service for payment providers. This project is in a alpha state and supports only PAYONE and Heidelpay payments, yet.

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

### Make Hosts entry because php-curl would not find it otherwise
on MacOs: 
```
sudo vim /etc/hosts
```

Paste in:
```
127.0.0.1 psp-mock.test
::1 psp-mock.test
fe80::1%lo0 psp-mock.test
127.0.0.1 project-community-edition.test
::1 project-community-edition.test
fe80::1%lo0 project-community-edition.test
```
psp-mock.test = your psp-mock domain

project-community-edition.test = your magento domain

### If composer install fails run this
```
composer update symfony/flex --no-plugins
```

### Instantiate psp-mock
```
bin/setup-instance.sh
```

### Configuration

Change the settings within the `.env` file in order to configure the endpoints.

```
PAYONE_CALLBACK_URI=https://test.my-shop.local/payone/transactionstatus
```

### Using with Magento 2

#### Payone:

In order to use this mock service with a Magento 2 installation you need to manipulate the endpoints within the
`payone-gmbh/magento2` extension. Therefore you can use the module: https://github.com/techdivision/magento2-payone-mockable

```bash
composer require --dev techdivision/payone-mockable
```

#### Heidelpay
In order to use this mock service with a Magento 2 installation you need to manipulate the endpoints within the
`heidelpay/magento2` Version: `^18.10`extension. Therefore you can use the module: https://github.com/LukasKiederle/magento2-heidelpay-mockable

```bash
composer config repositories.techdivision.magento2-heidelpay-mockable vcs https://github.com/LukasKiederle/magento2-heidelpay-mockable.git
composer require --dev techdivision/heidelpay-mockable
```

## License

This project is licensed under the OSL 3.0 License.

## Roadmap Payone

| Feature / Task                | Status    |
|-------------------------------|-----------|
| **PAYONE - Payments**         |           |
| - Creditcard(Visa, Mastercard)| ✓         |
| - Debit Payment               | X         |
| - PayPal                      | X         |
| - Cash on Delivery            | X         |
| - Advance Payment             | X         |
| - Invoice                     | X         |
| **PAYONE - Address Check**    | X         |
| **PAYONE - Credit rating**    | X         |
| **Rule based automation**     | X         |

### Creditcard

| Feature / Task                | Status    |
|-------------------------------|-----------|
| - Appoint                     | ✓         |
| - Fully paid                  | ✓         |
| - Partially paid              | ✓         |
| - Debit                       | ✓         |

## Roadmap Heidelpay

| Feature / Task                | Status    |
|-------------------------------|-----------|
| **Heidelpay - Payments**      |           |
| - Creditcard(Visa)            | ✓         |
| - Debit Payment               | X         |
| - PayPal                      | X         |
| - Cash on Delivery            | X         |
| - Advance Payment             | X         |
| - Invoice                     | X         |

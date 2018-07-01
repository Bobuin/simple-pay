# Application description

This project demonstrates simple payment system operations with usage
of REST API.

Entities in system:

    Users
    Wallets
    Currencies
    Currency Rates
    Transactions

Each User have one Wallet with one specified Currency.

Currencies have daily records with Rates relatively to 'base currency' (now its USD)

Wallets keeps information about current User balance.

Transaction records contains data about all funds movements.

## API

In all API requests must be present next header parameters:

    Content-Type: application/json
    Accept: application/json

#### Creation of new Currency

    POST /currencies

    {
        "code": "EUR",
        "name": "Europe Union Euro"
    }

#### Import Currency Rate for today

    POST /currency-rates/add
    
    {
        "currency": "EUR",
        "rate": 120
    }

#### Create new User (Wallet will be added automatically)

    POST /users
    
    {
        "name": "Another One User",
        "country": "Italy",
        "city": "Milan",
        "currency": "EUR"
    }

#### Adding funds to User's balance

    POST /wallets/add-funds/
    
    {
        "wallet_id": 1,
        "amount": 100
    }

#### Transfer funds between Users

    POST /wallets/transfer
    
    {
        "from_wallet_id": 1,
        "to_wallet_id": 2,
        "amount": 100,
        "transfer_currency": "sender"
    }

Parameter `transfer_currency` can be either `sender` or `receiver`

## Reports (Web interface)

All User's operation can be reviewed on reports page `/reports/{user_id}`

On reports page you can change User by selecting from drop down menu.

It is available to narrow reporting period by choosing start and end datetime of 
transactions.

To save displayed report click "Download CSV" button.

## More features

Will be later...

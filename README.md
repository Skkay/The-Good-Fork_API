# The Good Fork - API
School project for SUPINFO.

### Context
>**The Good Fork** is a famous restaurant located in the city of Tours. It wants to develop and retain its customers and for this purpose wants to propose a new mobile application.

>Your team is in competition with several other subcontractors to do the development, the best project will win the contract.

>Your application must have an IOS and an Android version.


**The Good Fork - API** is the backend for the mobile applications.

**The Good Fork - Client App** is available [here](https://github.com/Skkay/The-Good-Fork_Client-App).

**The Good Fork - Staff App** is available [here](https://github.com/Skkay/The-Good-Fork_Staff-App).

---

### Installation
#### Requirements
- PHP 7.2.5 or higher
- Composer, available [here](https://getcomposer.org/)
- MySQL or MariaDB server

#### Install
1. `git clone https://github.com/Skkay/The-Good-Fork_API`
2. `cd The-Good-Fork_API`

3. Check Symfony requirements:
`composer require symfony/requirements-checker`

4. Create `.env.local` file at the root with:
```
DATABASE_URL="mysql://root:passwd@127.0.0.1:3306/db_name?serverVersion=5.7"
JWT_PASSPHRASE=$eCretPa$$€phr4se!
```
Replace _root_, _passwd_, _db_name_, _jwt\_passphrase_  with your own.

5. Install with composer:
`composer install`

6. Generate JWT keypair:
`php bin/console lexik:jwt:generate-keypair`

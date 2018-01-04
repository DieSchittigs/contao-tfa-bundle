# Contao Two-Factor Authentication

[![Packagist](https://img.shields.io/packagist/v/dieschittigs/contao-tfa-bundle.svg?style=for-the-badge)](https://packagist.org/packages/dieschittigs/contao-tfa-bundle)
[![Packagist](https://img.shields.io/packagist/dt/dieschittigs/contao-tfa-bundle.svg?style=for-the-badge)](https://packagist.org/packages/dieschittigs/contao-tfa-bundle)
[![license](https://img.shields.io/github/license/dieschittigs/contao-tfa-bundle.svg?style=for-the-badge)]()

This Contao bundle enables Two-Factor Authentication (TFA/2FA) for backend users. Users with 2FA enabled are not allowed to visit any backend page after logging in, before typing in the correct access code.

The bundle is compatible with **Contao 4.4** or newer.

## Installation
No matter which method you use to install the package, make sure to run the install tool afterwards so that the additional database column can be created.

#### Composer
With [Composer](https://getcomposer.org/) simply type `composer require dieschittigs/contao-tfa-bundle` in your Contao installation's root folder.

#### Contao Manager (Contao 4.5+)
Select _Packages > Install Packages_ and search for `contao-tfa-bundle`. The button _Check & Install_ will then install it for you.

## Usage
To enable Two-Factor-Authentication for your account, visit your backend profile and open the "_Two-Factor Authentication_" section. Scan the displayed QR code with a compatible app (we recommend **Google Authenticator**, which is available for iOS and Android), verify by typing in the code generated by your app and save.

## Caveats
This bundle intercepts all backend requests to display a two-factor-authentication page for users that have 2FA enabled, but haven't yet authenticated themselves. As such the users can still log in, but they can't access any backend pages.
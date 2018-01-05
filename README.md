# Contao Two-Factor Authentication

[![Packagist](https://img.shields.io/packagist/v/dieschittigs/contao-tfa-bundle.svg?style=for-the-badge)](https://packagist.org/packages/dieschittigs/contao-tfa-bundle)
[![Packagist](https://img.shields.io/packagist/dt/dieschittigs/contao-tfa-bundle.svg?style=for-the-badge)](https://packagist.org/packages/dieschittigs/contao-tfa-bundle)
[![license](https://img.shields.io/github/license/dieschittigs/contao-tfa-bundle.svg?style=for-the-badge)]()

This Contao bundle enables Two-Factor Authentication (TFA/2FA) via [TOTP](https://en.wikipedia.org/wiki/Time-based_One-time_Password_Algorithm) for backend users. Users with 2FA enabled are not allowed to visit any backend page after logging in, before typing in the correct access code.

The bundle is compatible with **Contao 4.4** or newer.

## Installation
No matter which method you use to install the package, make sure to run the install tool afterwards so that the additional database column can be created.

#### Composer
With [Composer](https://getcomposer.org/) simply type `composer require dieschittigs/contao-tfa-bundle` in your Contao installation's root folder.

#### Contao Manager (Contao 4.5+)
Select _Packages > Install Packages_ and search for `contao-tfa-bundle`. The button _Check & Install_ will then install it for you.

## Usage
To enable Two-Factor Authentication for your account, visit your backend profile and open the "_Two-Factor Authentication_" section. Scan the displayed QR code with a compatible app (we recommend **Google Authenticator**, which is available for iOS and Android), verify by typing in the code generated by your app and save.

Since the generated code is time-based and thus changes periodically, it is important that your server time is set up correctly. By default the used library for code verification is able to correct for up to 30 seconds in either direction. This can be changed in the security section of your application settings.

You can also force all users to set up Two-Factor Authentication by toggling the checkbox for "_Force Two-Factor Authentication_" in your application's security settings. This will redirect all users (including yourself as an admin) to a 2FA setup page, before they can access any other page.

## Caveats
This bundle intercepts all backend requests to redirect users that have yet to authenticate themselves to a 2FA authentication page. As such the users can still log in, but they can't access any backend pages.

If you plan to execute code on behalf of the user, make sure to take the two-factor status into account. Although the user can't visit any pages, he's still fully authenticated by the Symfony security layer, so you'll have to add extra checks to make sure two-factor authentication is completed.

## Roadmap
Although we haven't yet decided on any further features, the following might be possible:
- Additional 2FA methods (e.g. mail)
- Backup Codes
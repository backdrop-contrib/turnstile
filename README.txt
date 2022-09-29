Cloudflare Turnstile for Drupal
===============================

INTRODUCTION
------------

The Cloudflare Turnstile module uses the Turnstile web service to
augment the CAPTCHA system and protect forms. For
more information on what Turnstile is, please visit:
    https://developers.cloudflare.com/turnstile

REQUIREMENTS
------------

* hCaptcha module depends on the CAPTCHA module.
  https://drupal.org/project/captcha


INSTALLATION
------------

1. Enable Cloudflare Turnstile and CAPTCHA modules on:
       admin/modules

CONFIGURATION
-------------


1. You'll now find a Turnstile tab on the CAPTCHA
   administration page available at:
       admin/config/people/captcha/turnstile

2. Register on:
       https://cloudflare.com/

3. Input the site and secret keys into the Cloudflare settings.

4. Visit the CAPTCHA administration page and set where you
   want the Turnstile form to be presented:
       admin/config/people/captcha

THANK YOU
---------

 * Thank you goes to the Cloudflare team for all their
   help, support and their amazing CAPTCHA replacement
       https://cloudflare.com/

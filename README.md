
Domain Swap for YURLS
---------------------------------------------

- **Plugin Name**: Domain Swap
- **Plugin URI**: https://github.com/gmolop/yourls-domain-swap
- **Description**: Allows switching domains (for the same instance of YOURLS) when working with domain aliases.
- **Version**: 1.0
- **Author**: gmolop

**NOTE**: *This plugin will not setup or change any config in your installation to work with multiples domains, it will only allow you to swap between them in a easy way.*

###Installation

1. In `user/plugins/` create a new directory, named for instance `domain-swap`
2. Download this repo as .zip file (or individually)
3. Extract and copy/move the files into the new `domain-swap` folder
4. Go to your plugin manager and `Activate`

###How to have multiples domains for the same instance of YOURLS

1. Create and set up your first instance of YOURLS as usual
2. Point as many domains (or subdomains) you want to the same IP (A/CNAME record)
3. Modify your `user/config.php` file

    // from:
    define( 'YOURLS_SITE', 'http://short.url' );
    // to:
    define( 'YOURLS_SITE', 'http://' . $_SERVER['HTTP_HOST'] . '');

4. Go to config page and add all domains you want to be able to swap
5. Done

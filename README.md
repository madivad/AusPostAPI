# AusPostAPI
Australia Post API usage in PHP

**usage:**  

`kfile.php` contain `$akey` which is the API key you get from:

    https://developers.auspost.com.au/

`index.php` is a simple implementation without any GUI.
pass a variable to the page: 

    http://example.com/index.php?p=2000

valid post codes return all places names using that post code.

    http://example.com/index.php?p=creek

will return a list of all postcodes and names that have creek within the place name. 

There is no error handling in this version

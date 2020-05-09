##About Simple RSS Generator
Hi with the help of this page you can generate simple RSS 2.0

##Working Example

```php
<?php
use Channaveer\RSS\RSS;

require_once './vendor/autoload.php';

$rssItems = [
    [
        'title'         => 'What Is Composer? How Does It Work? Useful Composer Commands And Usage',
        'url'           => 'https://somesite.in/posts/what-is-composer-how-does-it-work',
        'description'   => 'In this article, you will learn: What Is Composer, Using Composer, Installation In Ubuntu/Windows/Mac.',
        'updatedAt'     => '2020-05-07'
    ]
];

$path       = '.'; /** Path to generate your rss.xml file */
$filename   = 'rss.xml'; /** RSS filename default is rss.xml  */
$rss        = new RSS();

$rss->siteName('SiteName')
    ->siteUrl('https://siteurl.com')
    ->description('Some random description of the site')
    ->language('en-US') /** Default is en-Us you can set any of yours */
    ->lastUpdated($feedItems[0]['updatedAt']) /** Just pass datetime string or date string */
    ->generate($path, $rssItems, $filename);
```

RSS Items is array of items which requires following fields.

```
title
url
description
updatedAt
```
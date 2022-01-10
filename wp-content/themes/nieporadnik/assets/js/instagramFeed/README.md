# Eyes Here!

Due to the security added by instagram, The original [jquery.isntagramFeed](https://github.com/jsanahuja/jquery.instagramFeed) won't display the images. This is due to the 
CORS security header added to the images. The image link is still accessible directly via URL but browser throws CORS error when you add this image link directly to your web page.

You have two solutions to resolve this issue:
- **Add a personal token from your client** - Well, this will work if you have 1 or 2 sites.
- **Use a proxy image** - This is what we are trying to solve in this repository. Please continue below:

## Using a proxy image

Our *jquery.instagramFeed.p.js* has an additional option called `proxy_image_url` which you can add to 
the `$.instagramFeed` option. By default, this repository contains folders called `instamedia` which you can plugin and play to your 
centralise proxy or per site.

### Step 1

*Note! we currently only support php at this time to proxy your insta images*

- Copy the folder `instamedia` to your desired server
- add cronjob `0 */6 * * * /path/to/instamedia/cleaner.php` to clean cached images

### Step 2

- change your `jquery.instagramFeed.js` <script> src to `jquery.instagramFeed.p.js` or `jquery.instagramFeed.p.min.js`.

### Step 3

add the proxy image url to your existing instagram feed.

```
    $.instagramFeed({
        'proxy_image_url': "https://www.example.com/instamedia/instamedia.php",
        'username': 'instagram',
        ...
    }); 
```

# This Repository is the cloned version of https://github.com/jsanahuja/jquery.instagramFeed

The focus of this repository was to provide an easy and ready to use plugin to display an Instagram Feed ~~but since latest Instagram changes, this no longer makes sense. Please, move to an official API based plugin.~~

~~If you feel this repo should not be archived, please reach out and let us know. Archiving can always be reversed if needed.~~

# jquery.instagramFeed [![Build Status](https://travis-ci.com/jsanahuja/jquery.instagramFeed.svg?branch=master)](https://travis-ci.com/jsanahuja/jquery.instagramFeed)
Instagram Feed without using the instagram API

Try [InstagramFeed](https://github.com/jsanahuja/InstagramFeed), the same without jQuery.

## Documentation

[Full documentation and examples here](https://www.sowecms.com/demos/jquery.instagramFeed/index.html "documentation")

## Contributing

Read and follow the [CONTRIBUTING.md](./CONTRIBUTING.md) before sending any pull request.


# MIT License

Copyright (c) 2018 Javier Sanahuja

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

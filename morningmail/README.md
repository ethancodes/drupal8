# morningmail
Morning Mail

## Overview
The gist is that we have a Drupal site that pulls in content from RSS feeds. There are ~50 feeds on various sites. We process the content as Nodes of a specific content type. Those Nodes are then reviewed and a Publication Date is set for those that make the cut. Every morning there's a script that pulls everything set to be published that day, formats it in an email, and sends it out.

## Importing
We're using the Feeds module to import content. You define a Feed Type (such as RSS) and tell it how to handle that. Then you create a Feed (such as `news.rpi.edu`) and pick a Feed Type, enter a URL, and off you go.

Everything it pulls in is turned into an Article.

## Preview
There's a View for previewing all the articles that have been marked for inclusion on a particular Publication Date. This View is also used when building the email.

## The Email
We use the Mail System and Swiftmailer modules to handle the sending of the email. We do this because Drupal's defaul mail hander does not send MIME email, and we'd like to do that.

Copy `swiftmailer.html.twig` into the theme directory. This template file formats the email, which includes the CSS.


## Debugging
`/morningmail/test_collect` will show you everything that would be included in today's email.

`/morningmail/test_collect/20180525` will show you everything that would be included in the email for May 25th, 2018.

`/morningmail/test_email/20180525` will show debugging info and send a test email as if it were May 25th, 2018.
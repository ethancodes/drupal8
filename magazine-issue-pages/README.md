# magazine-issue-pages
A module for the Magazine.

## Content Types
An `issue` is one issue of the Magazine.

An `advanced_page` is a page. It has a `page_type` field, that references a Taxonomy. Example values are Feature, At Rensselear, and From Our Readers. It also has a `issue` field, which is an Entity References to an `issue` node. So an AP can point to an Issue, but does not have to. This allows you to say, this Feature is part of this Issue, or all of these AR articles are part of that Issue.

## Views
There is a View called "Issue - From Our Readers" `issue_from_our_readers`. By default it lists all AP with the Page Type of "From Our Readers". There's an optional Contextual Filter for the Issue Node ID. This allows us to show only the From Our Readers for a particular Issue.

We'll copy this View, with some minor modifications, for both Features and At Rensselear.

## What we are trying to do here
So what we want are URLs like `fall2019/features` and `spring2020/from-our-readers`. We could do this by making (copying) a new View for each one of these, for each Issue. Which sounds awful to me. So I'm going to build a module that will
* take that URL
* map the "fall2019" part to an Issue
* take that Issue Node ID
* load the View and pass it that Node ID
* output that markup

## Routes/Paths/URLs
So we have to set up those URLs ourselves. We do that with "dynamic routes." The `.routing.yml` file just points to the `Routing/MagazineIssuePagesRoutes.php` file, which sets everything up. That has one big function which
- gets all the Issues
- loops through them and sets up
  - features
  - at rensselear
  - from our readers
- each of those routes is unique for the issue+page type
- and contains the issue nid
- and points to the Controller
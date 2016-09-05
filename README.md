GOV.UK Local Services Explorer
====================

**This service is no longer maintained**

**Local Directgov closed on 30 August 2016. The data used by this service is now
out of date and inaccurate.**



[GOV.UK](https://www.gov.uk) provides many links to local services run by
local government in the UK (for example [pay council tax](http://www.gov.uk/pay-council-tax).
You can read more about how the links to these services are managed on the
[Government Digital Service blog](http://digital.cabinetoffice.gov.uk/2012/02/14/local-services-and-gov-uk/).

This app imports the daily feed of URLs (there are somewhere in the region of
94 thousand of them) and runs them through a series of tests, including:

* is the URL working (e.g. is it a 404)?
* is the URL reporting that it works, but is actually a 404? (some council websites don't use real 404s)?
* is the website configured to return 404s? (this test is really crude, but useful)


The app also keeps a record of the changes to the URLs, and produces lists of
URLs that have problems.

I'll add more documentation to explain how to get this app up and running soon.

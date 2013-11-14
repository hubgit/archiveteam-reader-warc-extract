# Instructions

1. `php indexes.php` - fetch all the [directory listings](https://archive.org/search.php?query=collection:archiveteam_greader)
1. `php pages.php` - fetch each ["Archive Team Google Reader Grab"](https://archive.org/details/archiveteam_greader_20130619020213) page.
1. `php cdx.php` - fetch each WARC-describing CDX file.
1. `php extract.php needle` - extract feeds that match "needle"

Output text files include WARC headers - currently need to clean those up manually.
Each feed may be split over several output text files, each with a different "continuation" token in the URL.

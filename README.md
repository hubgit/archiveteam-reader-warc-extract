# Note

I found [a better way to do this](https://gist.github.com/hubgit/7470103), using the Internet Archive's hosted CDX search index.

# Instructions

1. `php indexes.php` - fetch all the [directory listings](https://archive.org/search.php?query=collection:archiveteam_greader)
1. `php pages.php` - fetch each ["Archive Team Google Reader Grab"](https://archive.org/details/archiveteam_greader_20130619020213) page.
1. `php cdx.php` - fetch each MEGAWARC-describing CDX file.
1. `php warc.php needle` - fetch WARC files from the MEGAWARCs for feeds that match "needle"
1. TODO: extract JSON content from the WARC files
1. TODO: merge feeds that are continued over more than one file.

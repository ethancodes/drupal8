Demo module. We'll do two things. Export all the Articles as JSON, which is the easy part. Import articles from JSON, creating nodes for them. Those nodes are set as unpublished.

Notes:
- URL to import from could/should be a configurable variable in admin settings
- import process could/should have a preview, allowing admins to approve which articles are imported
- export could be limited to a number (sorted by date) or date range or "for export" field
- and that could/should be a configurable variable in admin settings
- if import feed articles have a nid, update that nid (if it actually exists)
- a View can have a Feed which can output results as RSS, but not JSON - wonder if there's a module to add that functionality
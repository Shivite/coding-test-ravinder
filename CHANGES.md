# Changelog

## 0.1
- Initial release
1) In api.php file used default Route::resource to all crud default routes.
2) Renamed creatingTaskProps to taskProps and added edit/updated logic with it to reduce redundant code. Not taskProps will use for both create/edit/update.
3) Show tasks count in column header by fetching from database.
4) introduce new migration to add completed_at column.
5) Used default CRUD routes in api.php [Route::resource]

Below link is screen recording of running task Please check.


Video: https://www.loom.com/share/3828741b4ffb49fa8f6151198a85508b?sid=8766fde9-7087-4a80-abf5-4b683500dd37


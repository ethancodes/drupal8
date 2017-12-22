Dirt simple way to make custom Yaml config files for your modules.

Enable LazyConfig and then you can call

$cfg = lazyconfig_get('mymodule');

to load /modules/contrib/mymodule/mymodule.yml as a nice PHP array.

Alternatively you can call

$cfg = lazyconfig_get('mymodule', 'mymodule.admin');

to load /modules/contrib/mymodule/mymodule.admin.yml.
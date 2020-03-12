# Options

The Discord class constructor takes an array of options. So you can pass troguh options to the Discord
client construcotr like the example below:

## Options overview

| Name                  | Description                                                   | Required    | Default          |
| --------------------- | ------------------------------------------------------------- | ----------- | ---------------- |
| **token**             | The Discord authentication token                              | `true`      | -                |
| **shardID**           | The ID of the shard (if you are using sharding)               | `false`     | -                |
| **shardCount**        | How many shards you are using (if you are using sharding)     | `false`     | -                |
| **loop**              | The ReactPHP event loop                                       | `false`     | Event loop       |
| **logger**            | The monolog logger to use                                     | `false`     | Monolog Logger   |
| **loggerLevel**       | The logger level to use                                       | `false`     | `INFO`           |
| **logging**           | Whether logging is enabled                                    | `false`     | `true`           |
| **cachePool**         | The cache pool to use                                         | `false`     | `ArrayCachePool` |
| **LoadAllMembers**    | Whether all members should be preloaded                       | `false`     | `false`          |
| **disabledEvents**    | An array of events that will be ignored                       | `false`     | `[]`             |
| **pmChannels**        | Whether private message channels should be parsed and stored  | `false`     | `false`          |
| **storeMessage**      | Whether messages recieved should be stored                    | `false`     | `false`          |

## Example

```php
<?php

$discord = new \Discord\Discord([
    'token' => 'your-auth-token',
    // Extra options here...
]);
```

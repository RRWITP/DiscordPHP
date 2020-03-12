# Discord client

## `class Discord()`

The discord class is the base of the client

## Attributes

The following attributes are allowed in the `Discord()`.

| Name              | Description                                                                                 | Type           |
| ----------------- | ------------------------------------------------------------------------------------------- | -------------- |
| **id**            | The unique identifier of the bot/user                                                       | `snowflake`    |
| **username**      | The username of the bot/user                                                                | `string`       |
| **verified**      | Whether the account is verified or not                                                      | `bool`         |
| **avatar**        | The URL to the bot/user's avatar                                                            | `string`       |
| **avatar_hash**   | The avatar of the bot/user                                                                  | `string`       |
| **discriminator** | A unique integer that discriminate users with the username, often referred as "discord tag" | `int`          |
| **bot**           | Whether the user is a bot account                                                           | `bool`         |
| **user**          | The user of the client                                                                      | `User`         |
| **application**   | The Oauth application the bot belongs to                                                    | `Application`  |

## `public function run()`

Starts the ReactPHP event loop and the bot.

## `public function close()`

Closes the DiscordPHP client.

## `public function factory($class, $data)`

Provides access to the class factory. Return an instance of `$class`

### Attributes

| Parameter        | Description                                               | Type           | Default |
| ---------------- | --------------------------------------------------------- | -------------- | --------|
| **$class**       | The class to create. Must be a Part or a Repository       | `string`       | -       |
| **$data**        | An array of data to put into the Part                     | `array`        | `[]`    |

### Example

```php
<?php

$channel = $discord->factory(Channel::class, ['name' => 'newChannel']);
```

## `public function updatePresence($game, $idle)`

Updates the presence of the bot.

### Attributes

| Parameter        | Description                                               | Type           | Default |
| ---------------- | --------------------------------------------------------- | -------------- | --------|
| **$game**        | The game object to set                                    | `Game`         | `null`  |
| **$idle**        | Whether the client should be set to idle                  | `bool`         | `false` |

### Example

```php
<?php

$game = $discord->factory(Game::class, ['name' => 'i am playing a game!']);
$discord->updatePresence($game);
```

## `public function joinVoiceChannel($channel, $mute, $deaf, $monolog)`

Attempts to connect to a voice channel. Returns a Promise with a [Voice Client](client-coiv.md).

### Attributes

| Parameter       | Description                                     | Type                    | Default |
| --------------- | ----------------------------------------------- | ----------------------- | ------- |
| **$channel**    | The voice channel to join                       | [Channel](channel.md)   | -       |
| **$mute**       | Whether the client should be self-muted.        | `bool`                  | `false` |
| **$deaf**       | Whether the client should be self-deafened.     | `bool`                  | `true`  |
| **$monolog**    | The monolog logger to use.                      | `Monolog`               | `null`  |

### Examples

```php
<?php

$discord->joinVoiceChannel($channel)->then(static function (VoiceClient $vc) {
    echo "Joined voice channel.\r\n";
    $vc->playFile('myfile.mp3');
}, static function ($e) {
    echo "There was an error joining the voice channel: {$e->getMessage()}\r\n";
});
```

## `public function getVoiceClient($id)`

Attempts to retrieve a voice client. Returns a Promise with a [Voice Client](client-voice.md)

| Parameter      | Description                      | Type        |
| -------------- | -------------------------------- | ----------- |
| **$id**        | The channel ID to look for       | `snowflake` |

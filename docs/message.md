# Message

A message is the container for a text message/file sent to a text channel.
The message object contains information about text messages sent to a text channel.

## Attributes

| Name                  | Description                                                           |
| --------------------- | --------------------------------------------------------------------- |
| **id**                | The unique identifier of the message                                  |
| **channel**           | The Channel the message was sent to                                   |
| **channel_id**        | The unique identifier of the Channel that the message was sent to     |
| **content**           | The text content of the message                                       |
| **mentions**          | An array of mentions                                                  |
| **author**            | The author of the message                                             |
| **mention_everyone**  | Whether trhe message had an `@everyone` class                         |
| **timestamp**         | The time the message was sent                                         |
| **edited_timestamp**  | The time the message was edited (if it was edited)                    |
| **tts**               | Whether the message was sent as text-to-speech                        |
| **attachments**       | An array of attachments                                               |
| **embeds**            | An array of embeds                                                    |
| **nonce**             | Used fo validating a message was sent properly                        |
| **mention_roles**     | Whether the message mentioned a role                                  |

## `public function reply($text)`

Sends a reply to the channel in the format `@author-of-message, {$text}`
Returns a Promise with a message

### Attributes

| Parameter             | Description                      | Type           |
| --------------------- | -------------------------------- | -------------- |
| **$text**             | The text to send to the channel  | `string`       |

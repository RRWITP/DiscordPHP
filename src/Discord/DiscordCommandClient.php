<?php

namespace Discord;

use Discord\CommandClient\Command;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Provides an easy way to have triggerable commands.
 */
class DiscordCommandClient extends Discord
{
    /**
     * An array of options passed to the client.
     *
     * @var array Options.
     */
    protected $commandClientOptions;

    /**
     * A map of the commands.
     *
     * @var array Commands.
     */
    protected $commands = [];

    /**
     * A map of aliases for commands.
     *
     * @var array Aliases.
     */
    protected $aliases = [];

    /**
     * Constructs a new command client.
     *
     * @param array $options An array of options.
     */
    public function __construct(array $options = [])
    {
        $this->commandClientOptions = $this->resolveCommandClientOptions($options);

        $discordOptions = array_merge($this->commandClientOptions['discordOptions'], [
            'token' => $this->commandClientOptions['token'],
        ]);

        parent::__construct($discordOptions);

        $this->on('ready', function () {
            $this->commandClientOptions['prefix'] = str_replace('@mention', (string) $this->user, $this->commandClientOptions['prefix']);
            $this->commandClientOptions['name'] = str_replace('<UsernamePlaceholder>', $this->username, $this->commandClientOptions['name']);

            $this->on('message', function ($message) {
                if ($message->author->id == $this->id) {
                    return;
                }

                if (substr($message->content, 0, strlen($this->commandClientOptions['prefix'])) == $this->commandClientOptions['prefix']) {
                    $withoutPrefix = substr($message->content, strlen($this->commandClientOptions['prefix']));
                    $args = str_getcsv($withoutPrefix, ' ');
                    $command = array_shift($args);

                    if (array_key_exists($command, $this->commands)) {
                        $command = $this->commands[$command];
                    } elseif (array_key_exists($command, $this->aliases)) {
                        $command = $this->commands[$this->aliases[$command]];
                    } else {
                        // Command doesn't exist.
                        return;
                    }

                    $result = $command->handle($message, $args);

                    if (is_string($result)) {
                        $message->reply($result);
                    }
                }
            });
        });

        if ($this->commandClientOptions['defaultHelpCommand']) {
            $this->registerCommand('help', function ($message, $args) {
                $prefix = str_replace((string) $this->user, '@'.$this->username, $this->commandClientOptions['prefix']);
                $emb = [];
                $emb['color'] = 0x0080ff;
                $emb['author'] = [];
                $emb['author']['name']   = $message->author->username.'#'.$message->author->discriminator;
                $emb['author']['icon_url'] = $message->author->user->avatar;
                $emb['footer'] = [];
                $emb['footer']['text'] = $prefix.'help [command] で詳細が確認できます。';
                $emb['fields'] = [];


                if (count($args) > 0) {
                    $commandString = implode(' ', $args);
                    $command = $this->getCommand($commandString);

                    $emb['title'] = $prefix.$commandString;
                    $emb['description'] = '引数';
                    if (is_null($command)) {
                        $emb['color'] = 0xff0000;
                        $emb['description'] = 'コマンドが見つかりませんでした。';
                        //return "The command {$commandString} does not exist.";
                    }else {
                        $help = $command->getHelp($prefix);
                        //$emb['description'] = $help['text'];
                        $emb['description'] = $command->description;
                        /*.
                        $data = ['コマンド名' => $command->command, '説明' =>$command->description];
                        foreach ($data as $name => $value) {
                            $c = [
                                'name' => $name,
                                'value' => $value,
                                'inline' => true
                            ];
                            array_push($emb['fields'],$c);
                        }*/

                        $c = [
                            'name' =>  '=========',
                            'value' => 'エイリアス',
                            'inline' => false
                        ];
                        array_push($emb['fields'],$c);

                        foreach ($this->aliases as $alias => $command) {
                            if ($command != $commandString) {
                                continue;
                            }

                            $c = [
                                'name' =>  $prefix.$alias,
                                'value' => "-",
                                'inline' => true
                            ];
                            array_push($emb['fields'],$c);
                        }
                    }

                    var_dump($emb);
                    $message->channel->sendMessage($message->author.' ',false, $emb);

                    return;
                }
                $emb['title'] = 'コマンド一覧';
                $emb['description'] = 'コマンドの詳細です';

                foreach ($this->commands as $command) {
                    $help = $command->getHelp($prefix);
                    //$response .= $help['text'];
                    $c = [
                        'name' => $command->command,
                        'value' => $command->description,//.$help['text'],
                        'inline' => true
                    ];
                    array_push($emb['fields'],$c);
                }

                $message->channel->sendMessage($message->author.' ',false, $emb);
            }, [
                'description' => '使用可能なコマンドを表示します',
                'usage'       => '[command]',
                'aliases' => ['?','ヘルプ']
            ]);
        }
    }

    /**
     * Registers a new command.
     *
     * @param string           $command  The command name.
     * @param \Callable|string $callable The function called when the command is executed.
     * @param array            $options  An array of options.
     *
     * @return Command The command instance.
     */
    public function registerCommand($command, $callable, array $options = [])
    {
        if (array_key_exists($command, $this->commands)) {
            throw new \Exception("A command with the name {$command} already exists.");
        }

        list($commandInstance, $options) = $this->buildCommand($command, $callable, $options);
        $this->commands[$command]        = $commandInstance;

        foreach ($options['aliases'] as $alias) {
            $this->registerAlias($alias, $command);
        }

        return $commandInstance;
    }

    /**
     * Unregisters a command.
     *
     * @param string $command The command name.
     */
    public function unregisterCommand($command)
    {
        if (! array_key_exists($command, $this->commands)) {
            throw new \Exception("A command with the name {$command} does not exist.");
        }

        unset($this->commands[$command]);
    }

    /**
     * Registers a command alias.
     *
     * @param string $alias   The alias to add.
     * @param string $command The command.
     */
    public function registerAlias($alias, $command)
    {
        $this->aliases[$alias] = $command;
    }

    /**
     * Unregisters a command alias.
     *
     * @param string $alias The alias name.
     */
    public function unregisterCommandAlias($alias)
    {
        if (! array_key_exists($alias, $this->aliases)) {
            throw new \Exception("A command alias with the name {$alias} does not exist.");
        }

        unset($this->aliases[$alias]);
    }

    /**
     * Attempts to get a command.
     *
     * @param string $command The command to get.
     * @param bool   $aliases Whether to search aliases as well.
     *
     * @return Command|null The command.
     */
    public function getCommand($command, $aliases = true)
    {
        if (array_key_exists($command, $this->commands)) {
            return $this->commands[$command];
        }

        if (array_key_exists($command, $this->aliases) && $aliases) {
            return $this->commands[$this->aliases[$command]];
        }
    }

    /**
     * Builds a command and returns it.
     *
     * @param string           $command  The command name.
     * @param \Callable|string $callable The function called when the command is executed.
     * @param array            $options  An array of options.
     *
     * @return array[Command, array] The command instance and options.
     */
    public function buildCommand($command, $callable, array $options = [])
    {
        if (is_string($callable)) {
            $callable = function ($message) use ($callable) {
                return $callable;
            };
        } elseif (is_array($callable) && ! is_callable($callable)) {
            $callable = function ($message) use ($callable) {
                return $callable[array_rand($callable)];
            };
        }

        if (! is_callable($callable)) {
            throw new \Exception('The callable parameter must be a string, array or callable.');
        }

        $options = $this->resolveCommandOptions($options);

        $commandInstance = new Command(
            $this, $command, $callable,
            $options['description'], $options['usage'], $options['aliases']);

        return [$commandInstance, $options];
    }

    /**
     * Resolves command options.
     *
     * @param array $options Array of options.
     *
     * @return array Options.
     */
    protected function resolveCommandOptions(array $options)
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setDefined([
                'description',
                'usage',
                'aliases',
            ])
            ->setDefaults([
                'description' => 'No description provided.',
                'usage'       => '',
                'aliases'     => [],
            ]);

        $options = $resolver->resolve($options);

        if (! empty($options['usage'])) {
            $options['usage'] .= ' ';
        }

        return $options;
    }

    /**
     * Resolves the options.
     *
     * @param array $options Array of options.
     *
     * @return array Options.
     */
    protected function resolveCommandClientOptions(array $options)
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setRequired('token')
            ->setAllowedTypes('token', 'string')
            ->setDefined([
                'token',
                'prefix',
                'name',
                'description',
                'defaultHelpCommand',
                'discordOptions',
            ])
            ->setDefaults([
                'prefix'             => '@mention ',
                'name'               => '<UsernamePlaceholder>',
                'description'        => 'A bot made with DiscordPHP.',
                'defaultHelpCommand' => true,
                'discordOptions'     => [],
            ]);

        return $resolver->resolve($options);
    }

    /**
     * Handles dynamic get calls to the command client.
     *
     * @param string $name Variable name.
     *
     * @return mixed
     */
    public function __get($name)
    {
        $allowed = ['commands', 'aliases'];

        if (array_search($name, $allowed) !== false) {
            return $this->{$name};
        }

        return parent::__get($name);
    }
}

<?php

namespace Discord\CommandClient;

use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;

/**
 * A command that the Command Client will listen for.
 */
class Command
{
    /**
     * The trigger for the command.
     */
    protected string $command;

    /**
     * The description of the command.
     */
    protected string $description;

    /**
     * The usage of the command.
     */
    protected string $usage;

    /**
     * A map of sub-commands.
     */
    protected array $subCommands = [];

    /**
     * A map of sub-command aliases.
     */
    protected array $subCommandAliases = [];

    /**
     * Creates a command instance.
     *
     * @param DiscordCommandClient $client      The Discord Command Client.
     * @param string               $command     The command trigger.
     * @param \Callable            $callable    The callable function.
     * @param string               $description The description of the command.
     * @param string               $usage       The usage of the command.
     */
    public function __construct(DiscordCommandClient $client, $command, callable $callable, $description, $usage)
    {
        $this->client      = $client;
        $this->command     = $command;
        $this->callable    = $callable;
        $this->description = $description;
        $this->usage       = $usage;
    }

    /**
     * Registers a new command.
     *
     * @param  string           $command  The command name.
     * @param  \Callable|string $callable The function called when the command is executed.
     * @param  array            $options  An array of options.
     * @return Command
     *
     * @throws \Exception
     */
    public function registerSubCommand(string $command, $callable, array $options = [])
    {
        if (array_key_exists($command, $this->subCommands)) {
            throw new \Exception("A sub-command with the name {$command} already exists.");
        }

        list($commandInstance, $options) = $this->client->buildCommand($command, $callable, $options);
        $this->subCommands[$command]     = $commandInstance;

        foreach ($options['aliases'] as $alias) {
            $this->registerSubCommandAlias($alias, $command);
        }

        return $commandInstance;
    }

    /**
     * Unregisters a sub-command.
     *
     * @param  string $command The command name.
     * @return void
     *
     * @throws \Exception
     */
    public function unregisterSubCommand($command): void
    {
        if (! array_key_exists($command, $this->subCommands)) {
            throw new \Exception("A sub-command with the name {$command} does not exist.");
        }

        unset($this->subCommands[$command]);
    }

    /**
     * Registers a sub-command alias.
     *
     * @param  string $alias   The alias to add.
     * @param  string $command The command.
     * @return void
     */
    public function registerSubCommandAlias($alias, $command): void
    {
        $this->subCommandAliases[$alias] = $command;
    }

    /**
     * Unregisters a sub-command alias.
     *
     * @param  string $alias The alias name.
     * @return void
     *
     * @throws \Exception
     */
    public function unregisterSubCommandAlias($alias): string
    {
        if (! array_key_exists($alias, $this->subCommandAliases)) {
            throw new \Exception("A sub-command alias with the name {$alias} does not exist.");
        }

        unset($this->subCommandAliases[$alias]);
    }

    /**
     * Executes the command.
     *
     * @param  Message $message The message.
     * @param  array   $args    An array of arguments.
     * @return mixed
     */
    public function handle(Message $message, array $args)
    {
        $subCommand = array_shift($args);

        if (array_key_exists($subCommand, $this->subCommands)) {
            return $this->subCommands[$subCommand]->handle($message, $args);
        }

        if (array_key_exists($subCommand, $this->subCommandAliases)) {
            return $this->subCommands[$this->subCommandAliases[$subCommand]]->handle($message, $args);
        }

        if (! is_null($subCommand)) {
            array_unshift($args, $subCommand);
        }

        return call_user_func_array($this->callable, [$message, $args]);
    }

    /**
     * Gets help for the command.
     *
     * @param  string $prefix The prefix of the bot.
     * @return array
     */
    public function getHelp(string $prefix): array
    {
        $helpString = "{$prefix}{$this->command} {$this->usage}- {$this->description}\r\n";

        foreach ($this->subCommands as $command) {
            $help = $command->getHelp($prefix.$this->command.' ');
            $helpString .= "    {$help['text']}\r\n";
        }

        return ['text' => $helpString, 'subCommandAliases' => $this->subCommandAliases];
    }

    /**
     * Handles dynamic get calls to the class.
     *
     * @param  string $variable The variable to get.
     * @return mixed
     */
    public function __get($variable)
    {
        $allowed = ['command', 'description', 'usage'];

        if (array_search($variable, $allowed) !== false) {
            return $this->{$variable};
        }
    }
}

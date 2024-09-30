import { Client, GatewayIntentBits, REST, Routes } from 'discord.js';
import 'dotenv/config';
import axios from 'axios';
import { createCache } from 'cache-manager';

function cmd_plant(interaction)
{
    interaction.reply(`:potted_plant:`);
}

var SOFTWARE_VERSION = 'Unknown';
function cmd_version(interaction)
{
    cache.wrap('software_version', async () => {
        axios.get(process.env.WEB_BACKEND + '/software/version').then(function(response) {
            if (response.data.code == 200) {
                SOFTWARE_VERSION = response.data.version;
            }
        });
    }, process.env.CACHE_TIME, process.env.CACHE_TIME);

    interaction.reply(`Current software version: ${SOFTWARE_VERSION}`);
}

function cmd_url(interaction)
{
    interaction.reply(`${process.env.WEB_BACKEND}`);
}

function cmd_documentation(interaction)
{
    interaction.reply(`${process.env.WEB_BACKEND}/documentation`);
}

function cmd_demo(interaction)
{
    interaction.reply(`${process.env.WEB_BACKEND}/demo`);
}

const commands = [
    {
        name: 'plant',
        description: 'Shows a lovely plant emoji',
        handler: cmd_plant
    },
    {
        name: 'version',
        description: 'Show current release version',
        handler: cmd_version
    },
    {
        name: 'url',
        description: 'Show project URL',
        handler: cmd_url
    },
    {
        name: 'documentation',
        description: 'Show link to the documentation',
        handler: cmd_documentation
    },
    {
        name: 'demo',
        description: 'Show link to the demo workspace',
        handler: cmd_demo
    },
];

function handleCommand(interaction)
{
    for (let i = 0; i < commands.length; i++) {
        if (interaction.commandName === commands[i].name) {
            commands[i].handler(interaction);
        }
    }
}

const client = new Client({
    intents: [
        GatewayIntentBits.Guilds,
        GatewayIntentBits.GuildMembers,
        GatewayIntentBits.GuildMessages,
        GatewayIntentBits.MessageContent
    ]
});

const rest = new REST({ version: '10' }).setToken(process.env.BOT_TOKEN);

const cache = createCache();

client.once('ready', () => {
    console.log(`Logged in: ${client.user.tag} on ${client.guilds.cache.size} servers`);

    rest.put(
        Routes.applicationGuildCommands(process.env.CLIENT_ID, process.env.GUILD_ID), { body: commands }
    );
});

client.on('interactionCreate', async (interaction) => {
    if (!interaction.isChatInputCommand()) return;

    handleCommand(interaction);
});

client.login(process.env.BOT_TOKEN);

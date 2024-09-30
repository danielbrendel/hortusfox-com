import { Client, GatewayIntentBits, REST, Routes } from 'discord.js';
import 'dotenv/config';
import axios from 'axios';
import fs from 'fs';
import path from 'path';

var quotes = null;
var latestPhoto = null;

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

function cmd_sponsor(interaction)
{
    interaction.reply(`GitHub Sponsoring: ${process.env.SPONSOR_GITHUB} | Buy Me A Coffee: ${process.env.SPONSOR_COFFEE}`);
}

async function cmd_version(interaction)
{
    await interaction.deferReply();

    axios.get(process.env.WEB_BACKEND + '/software/version').then(function(response) {
        if (response.data.code == 200) {
            interaction.editReply(`Current software version: ${response.data.version}`);
        }
    });
}

async function cmd_stats(interaction)
{
    await interaction.deferReply();

    axios.get(process.env.WEB_REPO_API).then(function(response) {
        interaction.editReply(`[GitHub Stats] Stars: ${response.data.stargazers_count} | Forks: ${response.data.forks_count} | Open Issues: ${response.data.open_issues}`);
    });
}

function cmd_plant(interaction)
{
    interaction.reply(`:potted_plant:`);
}

function cmd_quote(interaction)
{
    const quote = quotes.items[Math.floor(Math.random() * quotes.items.length)];

    interaction.reply(quote);
}

async function cmd_photo(interaction)
{
    await interaction.deferReply();

    axios.get(`${process.env.WEB_BACKEND}/community/fetch/random`).then(function(response) {
        interaction.editReply(`${process.env.WEB_BACKEND}/img/photos/${response.data.data.thumb}`);
    });
}

const commands = [
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
    {
        name: 'sponsor',
        description: 'Resources on how to support the project',
        handler: cmd_sponsor
    },
    {
        name: 'version',
        description: 'Show current release version',
        handler: cmd_version
    },
    {
        name: 'stats',
        description: 'Show current GitHub repository statistics',
        handler: cmd_stats
    },
    {
        name: 'plant',
        description: 'Shows a lovely plant emoji',
        handler: cmd_plant
    },
    {
        name: 'quote',
        description: 'Lovely plant quotes to light up your mood',
        handler: cmd_quote
    },
    {
        name: 'photo',
        description: 'View a random selection from public community photos',
        handler: cmd_photo
    }
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

function sendChannelMessage(chanId, chanMsg)
{
    const channel = client.channels.cache.get(chanId);
    if (channel) {
        channel.send(chanMsg);
    }
}

client.once('ready', () => {
    console.log(`Logged in: ${client.user.tag} on ${client.guilds.cache.size} servers`);

    quotes = JSON.parse(fs.readFileSync(
        path.join(process.cwd(), 'quotes.json'),
        'utf-8'
    ));

    setInterval(() => {
        axios.get(`${process.env.WEB_BACKEND}/community/fetch/latest`).then(function(response) {
            if ((response.data.code == 200) && (latestPhoto !== response.data.data.thumb)) {
                latestPhoto = response.data.data.thumb;

                sendChannelMessage(process.env.PHOTO_CHANNEL, `New community photo: ${process.env.WEB_BACKEND}/img/photos/${latestPhoto}`);
            }
        });
    }, process.env.TIMER_INTERVAL);

    rest.put(
        Routes.applicationGuildCommands(process.env.CLIENT_ID, process.env.GUILD_ID), { body: commands }
    );
});

client.on('interactionCreate', async (interaction) => {
    if (!interaction.isChatInputCommand()) return;

    handleCommand(interaction);
});

client.login(process.env.BOT_TOKEN);

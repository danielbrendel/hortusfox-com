import { Client, GatewayIntentBits, REST, Routes, ApplicationCommandOptionType } from 'discord.js';
import 'dotenv/config';
import axios from 'axios';
import fs from 'fs';
import path from 'path';
import FormData from 'form-data';
import mysql from 'mysql2/promise';
import Game from './game.js';

const fetch = (...args) => import('node-fetch').then(({default: fetch}) => fetch(...args));

const FILE_PHOTO = 'photo.tmp';
const FILE_QUOTES = 'quotes.json';
const FILE_PLANTS = 'plants.json';
const FILE_LOG = 'history.log';

var game = null;
var quotes = null;
var plants = null;
var latestPhoto = null;

function log(content, timestamp = true)
{
    if (parseInt(process.env.LOG_ENABLE)) {
        if (timestamp) {
            content = '[' + (new Date).toLocaleString('en-US') + '] ' + content;
        }

        fs.writeFileSync(
            path.join(process.cwd(), FILE_LOG),
            content,
            {
                encoding: 'utf8',
                flag: 'a'
            }
        );
    }
}

function cmd_url(interaction)
{
    interaction.reply({
        content: `${process.env.WEB_BACKEND}`,
        ephemeral: true
    });
}

function cmd_documentation(interaction)
{
    interaction.reply({
        content: `${process.env.WEB_BACKEND}/documentation`,
        ephemeral: true
    });
}

function cmd_demo(interaction)
{
    interaction.reply({
        content: `${process.env.WEB_BACKEND}/demo`,
        ephemeral: true
    });
}

function cmd_sponsor(interaction)
{
    interaction.reply({
        content: `GitHub Sponsoring: ${process.env.SPONSOR_GITHUB}\nBuy Me A Coffee: ${process.env.SPONSOR_COFFEE}`,
        ephemeral: true
    });
}

async function cmd_version(interaction)
{
    await interaction.deferReply();

    axios.get(process.env.WEB_BACKEND + '/software/version').then(function(response) {
        if (response.data.code == 200) {
            interaction.editReply(`Current HortusFox release version: ${response.data.version}`);
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
    const plant = plants.items[Math.floor(Math.random() * plants.items.length)];

    interaction.reply({
        content: plant,
        ephemeral: true
    });
}

function cmd_quote(interaction)
{
    const quote = quotes.items[Math.floor(Math.random() * quotes.items.length)];

    interaction.reply({
        content: quote,
        ephemeral: true
    });
}

async function cmd_photo(interaction)
{
    await interaction.deferReply({ ephemeral: true });

    axios.get(`${process.env.WEB_BACKEND}/community/fetch/random`).then(function(response) {
        interaction.editReply(`${process.env.WEB_BACKEND}/img/photos/${response.data.data.thumb}`);
    });
}

async function cmd_guess(interaction)
{
    await game.guess(interaction);
}

async function cmd_scores(interaction)
{
    await game.memberPoints(interaction);
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
        description: 'Shows lovely plant GIFs or stickers randomly',
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
    },
    {
        name: 'guess',
        description: 'Try to catch a plant via guessing its name',
        options: [
            {
              name: 'name',
              type: ApplicationCommandOptionType.String,
              description: 'Name of the assumed plant',
              required: true
            }
        ],
        handler: cmd_guess
    },
    {
        name: 'scores',
        description: 'See your current game score',
        handler: cmd_scores
    }
];

function handleCommand(interaction)
{
    for (let i = 0; i < commands.length; i++) {
        if (interaction.commandName === commands[i].name) {
            commands[i].handler(interaction);
            log(`${interaction.user.username} has issued the command /${interaction.commandName}\n`);
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

const db = mysql.createPool({
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE,
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});

function sendChannelMessage(chanId, chanMsg)
{
    const channel = client.channels.cache.get(chanId);
    if (channel) {
        channel.send(chanMsg);
    }
}

function saveLatestPhoto(info)
{
    fs.writeFileSync(
        path.join(process.cwd(), FILE_PHOTO),
        info,
        {
            encoding: 'utf8',
            flag: 'w'
        }
    );
}

function abort(msg)
{
    console.error(`\x1b[31m${msg}\x1b[0m`);
    process.exit(1);
}

function success(msg)
{
    console.log(`\x1b[32m${msg}\x1b[0m`);
}

client.once('ready', () => {
    const guilds = client.guilds.cache.map(guild => guild.id);
    if (!guilds.includes(process.env.GUILD_ID)) {
        abort('Unknown Guild: Please specify correct guild in .env');
    }

    game = new Game(client, db, process.env.GAME_CHANNEL);
    game.setGameStatus(process.env.GAME_ENABLE);

    quotes = JSON.parse(fs.readFileSync(
        path.join(process.cwd(), FILE_QUOTES),
        'utf8'
    ));

    plants = JSON.parse(fs.readFileSync(
        path.join(process.cwd(), FILE_PLANTS),
        'utf8'
    ));

    if (fs.existsSync(FILE_PHOTO)) {
        latestPhoto = fs.readFileSync(
            path.join(process.cwd(), FILE_PHOTO),
            'utf8'
        );
    }

    setInterval(() => {
        axios.get(`${process.env.WEB_BACKEND}/community/fetch/latest`).then(function(response) {
            if ((response.data.code == 200) && (latestPhoto !== response.data.data.slug)) {
                latestPhoto = response.data.data.slug;

                saveLatestPhoto(latestPhoto);

                sendChannelMessage(process.env.PHOTO_CHANNEL, `:potted_plant: New community photo :potted_plant:\n${process.env.WEB_BACKEND}/p/${latestPhoto}`);
            }
        });
    }, process.env.TIMER_INTERVAL);

    rest.put(
        Routes.applicationGuildCommands(process.env.CLIENT_ID, process.env.GUILD_ID), { body: commands }
    );
    
    success(`Logged in: ${client.user.tag}. Bot is now ready.`);
});

client.on('interactionCreate', async (interaction) => {
    if (!interaction.isChatInputCommand()) return;

    handleCommand(interaction);
});

client.on('messageCreate', async (message) => {
    if ((process.env.PLANTNET_APIKEY.length > 0) && (!message.author.bot) && (message.channel.id === process.env.IDENT_CHANNEL)) {
        if (message.attachments.size > 0) {
            const imageAttachment = message.attachments.first();

            if (imageAttachment) {
                const reply = await message.reply('🔄 Processing your image...');

                try {
                    const fileUrl = imageAttachment.url.replace("media.discordapp.net", "cdn.discordapp.com");
                    
                    const fileType = imageAttachment.contentType;

                    const response = await fetch(fileUrl);
                    const arrayBuffer = await response.arrayBuffer();
                    const buffer = Buffer.from(arrayBuffer);

                    let finalImagePath = `downloaded-image.${fileType.includes('jpeg') ? 'jpg' : 'png'}`;;

                    fs.writeFileSync(finalImagePath, buffer);
                  
                    const formData = new FormData();
                    formData.append('organs', 'auto');
                    formData.append('images', fs.createReadStream(finalImagePath));

                    const apiQuery = await axios.post('https://my-api.plantnet.org/v2/identify/all?api-key=' + process.env.PLANTNET_APIKEY, formData, {
                        headers: formData.getHeaders()
                    });
                    
                    if (typeof apiQuery.data.statusCode !== 'undefined') {
                        throw new Error('Error: server returned with status ' + apiQuery.data.statusCode);
                    }

                    if ((typeof apiQuery.data.results === 'undefined') || (apiQuery.data.results.length == 0)) {
                        throw new Error('Plant could not be identified.');
                    }

                    let resultStr = '';
                    
                    for (let i = 0; i < apiQuery.data.results.length; i++) {
                        resultStr += apiQuery.data.results[i].species.scientificNameWithoutAuthor + ' (' + (apiQuery.data.results[i].score * 100).toFixed(2) + '%)\n';
                    }

                    await reply.edit('✅ Processing complete! Results:\n\n' + resultStr);

                    if (fs.existsSync(finalImagePath)) {
                        fs.unlinkSync(finalImagePath);
                    }
                } catch (error) {
                    await reply.edit('❌ Error: ' + error);
                }
            }
        }
    }
});

client.login(process.env.BOT_TOKEN);

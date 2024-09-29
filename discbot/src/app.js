import { Client, GatewayIntentBits } from 'discord.js';
import 'dotenv/config';
import axios from 'axios';

function ajax(method, url, data = {}, successfunc = function(data){}, finalfunc = function(){}, config = {})
{
    let func = axios.get;
    if (method == 'post') {
        func = axios.post;
    } else if (method == 'patch') {
        func = axios.patch;
    } else if (method == 'delete') {
        func = axios.delete;
    }

    func(url, data, config)
        .then(function(response){
            successfunc(response.data);
        })
        .catch(function (error) {
            console.log(error);
        })
        .finally(function(){
            finalfunc();
        }
    );
}

let HORTUSFOX_VERSION = null;

ajax('get', process.env.WEB_BACKEND + '/software/version', {}, (response) => {
    if (response.code == 200) {
        HORTUSFOX_VERSION = response.version;
    }
});

const client = new Client({
    intents: [
        GatewayIntentBits.Guilds,
        GatewayIntentBits.GuildMessages,
        GatewayIntentBits.MessageContent
    ]
});

client.once('ready', () => {
    console.log(`Logged in: ${client.user.tag} on ${client.guilds.cache.size} servers`);
});

client.login(process.env.BOT_TOKEN);

client.on("messageCreate", (message) => {
    if (!message.author.bot) {
        if (message.content.startsWith('!plant')) {
            message.channel.send(`:potted_plant:`);
        } else if (message.content.startsWith('!version')) {
            message.channel.send(`Current HortusFox release version: ${HORTUSFOX_VERSION}`);
        } else {
            message.channel.send(`Unknown command: ${message.content}`);
        }
    }
});
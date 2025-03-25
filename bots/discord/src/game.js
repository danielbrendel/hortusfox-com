import { fileURLToPath } from 'url';
import { dirname } from 'path';
import { AttachmentBuilder, MessageFlags } from 'discord.js';
import { randomUUID } from 'crypto';
import cron from 'node-cron';
import 'dotenv/config';

export default class Game {
    client = null;
    sql = null;
    cronjobs = [];
    gamestatus = false;
    gameChannel = null;
    gameSelection = null;
    cronjobs = null;
    curSession = null;

    constructor(client, db, channel)
    {
        this.client = client;
        this.sql = db;
        this.cronjobs = [];
        this.gamestatus = false;
        this.gameSelection = null;
        this.curSession = null;

        this.gameChannel = this.client.channels.cache.get(channel);
        
        this.cronjobs = {
            selection: cron.schedule(process.env.GAME_SELECTION_INTERVAL, () => {
                if (this.gamestatus) {
                    this.acquirePlant();
                }
            }),
            leaderboard: cron.schedule(process.env.GAME_LEADERBOARD_INTERVAL, () => {
                if (this.gamestatus) {
                    this.leaderboard();
                }
            })
        };
    }

    setClient(client)
    {
        this.client = client;
    }

    setDbInstance(db)
    {
        this.sql = db;
    }

    setGameStatus(status)
    {
        this.gamestatus = status;
    }

    acquirePlant()
    {
        this.sql.query('SELECT * FROM `plants` ORDER BY RAND() LIMIT 1', [], (err, results) => {
            if (err) {
                console.log(err);
                return;
            }

            if (results.length == 1) {
                this.gameSelection = results[0];
                this.curSession = randomUUID();

                const chanMsg = `**🪴 A wild plant has appeared!**\n➡️ Guess the name via \`/guess <plant name>\` to earn ${this.gameSelection.points} points!\n`;
                const attachment = new AttachmentBuilder(`${dirname(dirname(fileURLToPath(import.meta.url)))}\\img\\${this.gameSelection.photo}`);

                this.gameChannel.send({ content: chanMsg, files: [attachment] });
            }
        });
    }

    async guess(interaction)
    {
        const plantName = interaction.options.getString('name');

        if ((this.gamestatus) && (this.gameSelection)) {
            await interaction.deferReply();

            if (plantName.toLowerCase().trim() == this.gameSelection.name.toLowerCase().trim()) {
                this.sql.query('SELECT * FROM `guesses` WHERE member_id = ? AND session_id = ?', [interaction.member.id, this.curSession], (err, results) => {
                    if (results.length == 0) {
                        this.sql.query('SELECT * FROM `scores` WHERE member_id = ?', [interaction.member.id], (err, results) => {
                            if (results.length == 0) {
                                this.sql.query('INSERT INTO `scores` (member_id, points) VALUES(?, 0)', [interaction.member.id], (err, results) => {});
                                this.updatePoints(interaction);
                                return;
                            }
                        });
            
                        this.updatePoints(interaction);
                    } else {
                        interaction.editReply(`😏 Hey, you've already scored for this plant!`);
                    }
                });
            } else {
                interaction.editReply(`🥲 Oops! You guessed wrong!`);
            }
        } else {
            interaction.reply({ content: `Game is currently not running!`, flags: MessageFlags.Ephemeral });
        }
    }

    updatePoints(interaction)
    {
        this.sql.query('UPDATE `scores` SET points = points + ? WHERE member_id = ?', [this.gameSelection.points, interaction.member.id], (err, results) => {
            interaction.editReply(`🎯 Correct! You earned +${this.gameSelection.points} points!`);
        });

        this.sql.query('INSERT INTO `guesses` (member_id, session_id) VALUES(?, ?)', [interaction.member.id, this.curSession], (err, results) => {});
    }

    async memberPoints(interaction)
    {
        await interaction.deferReply({ flags: MessageFlags.Ephemeral });

        this.sql.query('SELECT * FROM `scores` WHERE member_id = ?', [interaction.member.id], (err, results) => {
            if (results.length > 0) {
                interaction.editReply(`🌟 You have currently earned ${results[0].points} points 🌟`);
            } else {
                interaction.editReply(`Looks like you haven't played yet!`);
            }
        });
    }

    leaderboard()
    {
        this.sql.query('SELECT * FROM `scores` WHERE points > 0 ORDER BY points DESC LIMIT 10', [], async (err, results) => {
            const guildObj = this.client.guilds.cache.get(process.env.GUILD_ID);
            var chanMsg = `🏆 Here are the current top plant gamers!\n\n`;

            for (var i = 0; i < results.length; i++) {
                const member = await guildObj.members.fetch(results[i].member_id);

                var trophy = '';
                if (i === 0) {
                    trophy = `🥇`;
                } else if (i === 1) {
                    trophy = `🥈`;
                } else if (i === 2) {
                    trophy = `🥉`;
                } else {
                    trophy = `🏅`;
                }

                chanMsg += `#${i+1} ${trophy} ${member.user.username} (${results[i].points} points)\n`;
            }

            chanMsg += `\n➡️ The scores have been reset. New round is active!\n`;

            this.sql.query('UPDATE `scores` SET points = 0', [], (err, results) => {});

            setTimeout(() => {
                this.gameChannel.send(chanMsg);
            }, 250000);
        });
    }
}

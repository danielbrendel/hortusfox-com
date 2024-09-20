const PLAYER_MAX_HEALTH = 3;
const SPEED_STEPS = 250;
const SUPPLY_MAXRAND = 5;

class TestGame extends Phaser.Scene {
    preload()
    {
        this.load.setBaseURL(window.location.origin);

        this.load.image('sky', 'game/assets/sprites/sky.png');
        this.load.image('clouds', 'game/assets/sprites/clouds.png');
        this.load.image('ground', 'game/assets/sprites/grass.png');
        this.load.image('tree_bottom', 'game/assets/sprites/tree_bottom.png');
        this.load.image('tree_stem', 'game/assets/sprites/tree_stem.png');
        this.load.image('tree_top', 'game/assets/sprites/tree_top.png');
        this.load.image('flower', 'game/assets/sprites/flower.png');
        this.load.image('shrooms', 'game/assets/sprites/shrooms.png');
        this.load.image('bush', 'game/assets/sprites/bush.png');
        this.load.image('box', 'game/assets/sprites/box.png');
        this.load.image('button', 'game/assets/sprites/button.png');
        this.load.spritesheet('fox', 'game/assets/sprites/fox.png', { frameWidth: 48, frameHeight: 26 });
        this.load.spritesheet('plant', 'game/assets/sprites/plant.png', { frameWidth: 376, frameHeight: 500 });
        this.load.spritesheet('boom', 'game/assets/sprites/explosion.png', { frameWidth: 512, frameHeight: 512 });
        this.load.spritesheet('ball', 'game/assets/sprites/ball.png', { frameWidth: 64, frameHeight: 64 });
        this.load.spritesheet('puff', 'game/assets/sprites/puff.png', { frameWidth: 32, frameHeight: 32 });

        this.load.audio('theme', 'game/assets/sounds/theme.ogg');
        this.load.audio('jump', 'game/assets/sounds/jump.wav');
        this.load.audio('hurt', 'game/assets/sounds/hurt.wav');
        this.load.audio('explosion', 'game/assets/sounds/explosion.wav');
        this.load.audio('up', 'game/assets/sounds/up.wav');

        this.load.audio('monster_spawn', 'game/assets/sounds/monster_spawn.wav');
        this.load.audio('monster_shoot', 'game/assets/sounds/monster_shoot.wav');
        this.load.audio('monster_dispose', 'game/assets/sounds/monster_dispose.wav');

        this.load.image('heart', 'game/assets/sprites/heart.png');

        for (let i = 1; i <= PLAYER_MAX_HEALTH; i++) {
            this.load.image('heart' + i, 'game/assets/sprites/heart.png');
        }

        this.trees = [];
        this.obstacles = [];
        this.bullets = [];
        this.hearts = [];
        this.playerScore = 0;
        this.playerHealth = PLAYER_MAX_HEALTH;
        this.tmSpawnSpeed = {
            min: 2000,
            max: 5000
        };
        this.tmGameTime = {
            start: Date.now(),
            current: Date.now()
        };

        this.cursors = this.input.keyboard.createCursorKeys();
    }

    create()
    {
        let self = this;

        this.add.image(0, 0, 'sky').setOrigin(0, 0);

        this.clouds = this.add.tileSprite(0, 0, gameconfig.scale.width, 300, 'clouds').setOrigin(0, 0);
        this.clouds.y += 50;

        for (let i = 0; i < this.playerHealth; i++) {
            this.hearts.push(this.add.image(55 + i * 50, 40, 'heart' + (i + 1)));
        }

        this.platforms = this.physics.add.staticGroup();
        this.platforms.create(50, gameconfig.scale.height - 25, 'ground').setScale(50, 1).refreshBody();

        this.addTree(200, gameconfig.scale.height - 80);
        this.addTree(gameconfig.scale.width - 190, gameconfig.scale.height - 80);

        this.add.image(455, gameconfig.scale.height - 105, 'flower').setScale(0.5, 0.5);
        this.add.image(635, gameconfig.scale.height - 105, 'flower').setScale(0.5, 0.5);
        this.add.image(375, gameconfig.scale.height - 86, 'shrooms').setScale(0.75, 0.75);
        this.add.image(535, gameconfig.scale.height - 86, 'shrooms').setScale(0.75, 0.75);
        this.add.image(50, gameconfig.scale.height - 79, 'bush').setScale(0.75, 0.75);
        this.add.image(gameconfig.scale.width - 40, gameconfig.scale.height - 79, 'bush').setScale(0.75, 0.75);

        this.player = this.physics.add.sprite(50, gameconfig.scale.height - 80, 'fox').setScale(1.5).refreshBody();

        this.player.setBounce(0.2);
        this.player.setCollideWorldBounds(true);
        this.player.setGravity(0, 300);

        this.anims.create({
            key: 'walk',
            frames: this.anims.generateFrameNumbers('fox', { start: 0, end: 1 }),
            frameRate: 10,
            repeat: -1
        });

        this.physics.add.collider(this.player, this.platforms);

        this.anims.create({
            key: 'chew',
            frames: this.anims.generateFrameNumbers('plant', { start: 0, end: 1 }),
            frameRate: 10,
            repeat: -1
        });

        this.anims.create({
            key: 'ball',
            frames: this.anims.generateFrameNumbers('ball', { start: 0, end: 3 }),
            frameRate: 25,
            repeat: -1
        });

        this.anims.create({
            key: 'boom',
            frames: this.anims.generateFrameNumbers('boom', { start: 0, end: 63 }),
            frameRate: 25,
            repeat: 0
        });

        this.anims.create({
            key: 'puff',
            frames: this.anims.generateFrameNumbers('puff', { start: 0, end: 9 }),
            frameRate: 50,
            repeat: 0
        });

        this.obstTimer = this.time.addEvent({
            delay: Phaser.Math.Between(self.tmSpawnSpeed.min, self.tmSpawnSpeed.max),
            loop: true,
            callback: self.spawnEnemyObstacle,
            callbackScope: self
        });

        this.obstDifficulty = this.time.addEvent({
            delay: 10000,
            loop: true,
            callback: function() {
                if (self.tmSpawnSpeed >= 0) {
                    self.tmSpawnSpeed.min -= SPEED_STEPS;
                }

                if (self.tmSpawnSpeed.max >= 500) {
                    self.tmSpawnSpeed.max -= SPEED_STEPS;
                }
            },
            callbackScope: self
        });

        this.txtTime = this.add.text(gameconfig.scale.width / 2 - 40, 25, 'Time: 0', { fontSize: '24px' });
        this.txtScore = this.add.text(gameconfig.scale.width - 40, 25, 'Score: 0', { rtl: true, fontSize: '24px', color: 'rgb(255, 255, 0)' });
        
        this.rectOverlay = this.add.rectangle(0, 0, gameconfig.scale.width, gameconfig.scale.height, 0x000000, 150).setOrigin(0, 0).setVisible(false);

        this.txtGameOver = this.add.text(gameconfig.scale.width / 2 - 55, gameconfig.scale.height / 2, 'GAME OVER!', {
            color: 'rgb(255, 0, 0)',
            fontSize: '32px',

        }).setVisible(false);

        this.rectRestart = this.add.image(gameconfig.scale.width / 2 + 37, gameconfig.scale.height / 2 + 79, 'button').setVisible(false);
        this.btnRestart = this.add.text(gameconfig.scale.width / 2 - 5, gameconfig.scale.height / 2 + 70, 'Restart', {
            color: 'rgb(0, 100, 150)',
            fontSize: '20px'
        }).setInteractive().on('pointerdown', function() {
            self.restartGame();
        }).on('pointerover', function() {
            self.btnRestart.setStyle({ color: 'rgb(255, 255, 255)', fontSize: '20px' });
        }).on('pointerout', function() {
            self.btnRestart.setStyle({ color: 'rgb(0, 100, 150)', fontSize: '20px' });
        }).setVisible(false);

        this.sndTheme = this.sound.add('theme');
        this.sndJump = this.sound.add('jump');
        this.sndHurt = this.sound.add('hurt');
        this.sndExplosion = this.sound.add('explosion');
        this.sndUp = this.sound.add('up');
        this.sndMonsterSpawn = this.sound.add('monster_spawn');
        this.sndMonsterShoot = this.sound.add('monster_shoot');
        this.sndMonsterDispose = this.sound.add('monster_dispose');

        this.children.bringToTop(this.txtScore);

        for (let i = 0; i < this.playerHealth; i++) {
            this.children.bringToTop(this.hearts[i]);
        }

        this.sndTheme.loop = true;
        this.sndTheme.play();
    }

    update()
    {
        if (this.playerHealth <= 0) {
            this.finishGame();

            return;
        }

        this.tmGameTime.current = Date.now();
        this.txtTime.setText(this.getFormattedGameTime());

        if (this.cursors.left.isDown) {
            this.player.setVelocityX(-330);
            this.player.anims.play('walk', true);
        } else if (this.cursors.right.isDown) {
            this.player.setVelocityX(330);
            this.player.anims.play('walk', true);
        }

        if (this.cursors.up.isDown && this.player.body.touching.down)
        {
            this.player.setVelocityY(-530);
            this.sndJump.play();
        }

        this.clouds.tilePositionX += 0.5;

        this.updateObstacles();
        this.updateBullets();

        this.txtScore.setText('Score: ' + this.playerScore);
    }

    spawnEnemyObstacle()
    {
        if (this.playerHealth <= 0) {
            return;
        }

        let self = this;

        let posx = gameconfig.scale.width - 20;
        let posy = Phaser.Math.Between(150, gameconfig.scale.height - 100);

        let box = this.physics.add.sprite(posx, posy, 'box');
        let plant = this.physics.add.sprite(posx, posy - 85, 'plant').setScale(0.2).refreshBody();

        let ident = 'obstacle_' + Math.random().toString(16).slice(2);

        this.obstacles.push({
            ident: ident,
            box: box,
            plant: plant,
            speed: Phaser.Math.Between(1, 3),
            destruction: false,
            shoot: self.time.addEvent({
                delay: Phaser.Math.Between(2000, 5000),
                loop: true,
                callback: function() {
                    if (self.playerHealth <= 0) {
                        return;
                    }

                    let bullet = this.physics.add.sprite(plant.x - 20, plant.y, 'ball');
                    
                    self.bullets.push({
                        bullet: bullet,
                        target: {
                            x: self.player.x,
                            y: self.player.y
                        },
                        destruction: false,
                        parent: ident,
                        tStart: Date.now(),
                        tNow: Date.now(),
                        tLifeTime: 4000
                    });

                    let bulIndex = self.bullets.length - 1;

                    self.physics.add.collider(self.player, bullet, function() {
                        self.playerHealth--;

                        self.sndHurt.play();

                        if (self.playerHealth >= 0) {
                            self.hearts[self.playerHealth].setVisible(false);
                        }

                        self.bullets[bulIndex].destruction = true;
                        self.bullets.slice(bulIndex, 1);
                        bullet.destroy();
                    });

                    self.physics.add.collider(self.platforms, bullet, function() {
                        self.bullets[bulIndex].destruction = true;
                        self.bullets.slice(bulIndex, 1);
                        bullet.destroy();
                    });

                    self.sndMonsterShoot.play();
                },
                callbackScope: self
            })
        });

        let obstIndex = this.obstacles.length - 1;

        this.physics.add.collider(this.player, box, function() {
            self.playerScore++;

            self.sndMonsterDispose.play();

            self.spawnExplosion(box.x, box.y);
            self.checkSupplySpawn(box.x, box.y);

            if (typeof self.obstacles[obstIndex] !== 'undefined') {
                let parentIdent = self.obstacles[obstIndex].ident;
                self.obstacles[obstIndex].shoot.paused = true;
                self.obstacles[obstIndex].destruction = true;
                for (let i = 0; i < self.bullets.length; i++) {
                    if (!self.bullets[i].destruction) {
                        if (self.bullets[i].parent === parentIdent) {
                            self.bullets[i].destruction = true;
                            self.bullets[i].bullet.destroy();
                            self.bullets.slice(i, 1);
                        }
                    }
                }

                self.obstacles[obstIndex].box.destroy();
                self.obstacles[obstIndex].plant.destroy();
                self.obstacles.slice(obstIndex, 1);
            }
        });

        this.physics.add.collider(box, plant, function() {
            plant.setGravity(50);
        });

        this.sndMonsterSpawn.play();
    }

    updateObstacles()
    {
        for (let i = 0; i < this.obstacles.length; i++) {
            if (!this.obstacles[i].destruction) {
                this.obstacles[i].box.x -= this.obstacles[i].speed;
                this.obstacles[i].plant.x -= this.obstacles[i].speed;

                this.obstacles[i].plant.anims.play('chew', true);

                if (this.obstacles[i].box.x <= -100) {
                    this.obstacles.splice(i, 1);
                }
            }
        }
    }

    updateBullets()
    {
        for (let i = 0; i < this.bullets.length; i++) {
            if (!this.bullets[i].destruction) {
                this.bullets[i].tNow = Date.now();
                if (this.bullets[i].tNow > this.bullets[i].tStart + this.bullets[i].tLifeTime) {
                    this.bullets[i].destruction = true;
                    this.bullets[i].bullet.destroy();
                    this.bullets.slice(i, 1);

                    continue;
                }

                this.bullets[i].bullet.anims.play('ball', true);
                this.physics.moveTo(this.bullets[i].bullet, this.bullets[i].target.x, this.bullets[i].target.y, 200);
            }
        }
    }

    spawnExplosion(x, y)
    {
        let explosion = this.physics.add.sprite(x, y, 'boom');
        explosion.anims.play('boom', true);
        explosion.on('animationcomplete', function() {
            explosion.destroy();
        });
        this.sndExplosion.play();
    }

    clearGameObjects()
    {
        for (let i = 0; i < this.bullets.length; i++) {
            if (!this.bullets[i].destruction) {
                this.bullets[i].destruction = true;
                this.bullets[i].bullet.destroy();
            }
        }

        for (let j = 0; j < this.obstacles.length; j++) {
            if (!this.obstacles[j].destruction) {
                this.obstacles[j].destruction = true;
                this.obstacles[j].box.destroy();
                this.obstacles[j].plant.destroy();
            }
        }

        this.obstacles = [];
        this.bullets = [];
    }

    finishGame()
    {
        if (this.playerHealth > 0) {
            return;
        }

        this.player.setVelocity(0, 0);

        if (!this.rectOverlay.visible) {
            this.rectOverlay.setVisible(true);
        }

        if (!this.txtGameOver.visible) {
            this.txtGameOver.setVisible(true);
            this.children.bringToTop(this.txtGameOver);
        }

        if (!this.rectRestart.visible) {
            this.rectRestart.setVisible(true);
            this.children.bringToTop(this.rectRestart);
        }

        if (!this.btnRestart.visible) {
            this.btnRestart.setVisible(true);
            this.children.bringToTop(this.btnRestart);

            this.clearGameObjects();
        }
    }

    restartGame()
    {
        location.reload();

        /*this.clearGameObjects();

        this.playerScore = 0;
        this.playerHealth = 3;

        this.player.x = 50;
        this.player.y = gameconfig.scale.height - 80;

        this.txtGameOver.setVisible(false);
        this.rectRestart.setVisible(false);
        this.btnRestart.setVisible(false);*/
    }

    removeObstacle(index, withBullet = false)
    {
        if (!this.obstacles[index].destruction) {
            let parentIdent = this.obstacles[index].ident;
            this.obstacles[index].shoot.paused = true;
            this.obstacles[index].destruction = true;

            if (withBullet) {
                for (let i = 0; i < this.bullets.length; i++) {
                    if (!this.bullets[i].destruction) {
                        if (this.bullets[i].parent === parentIdent) {
                            this.removeBullet(i);
                            continue;
                        }
                    }
                }
            }

            this.obstacles[index].box.destroy();
            this.obstacles[index].plant.destroy();
            this.obstacles.slice(index, 1);
        }
    }

    removeBullet(index)
    {
        if (!this.bullets[index].destruction) {
            this.bullets[index].destruction = true;
            this.bullets[index].bullet.destroy();
            this.obstacles.slice(index, 1);
        }
    }

    addTree(x, y)
    {
        let bottom = this.add.image(x, y - 30, 'tree_bottom');
        let stem1 = this.add.image(x - 5, y - 145, 'tree_stem');
        let stem2 = this.add.image(x - 5, y - 270, 'tree_stem');
        let top = this.add.image(x - 20, y - 415, 'tree_top');

        bottom.setScale(0.5, 0.5);
        stem1.setScale(0.5, 0.5);
        stem2.setScale(0.5, 0.5);
        top.setScale(0.75, 0.75);

        let tree = {
            bottom: bottom,
            stems: [stem1, stem2],
            top: top
        };

        this.trees.push(tree);
    }

    checkSupplySpawn(x, y)
    {
        if (this.playerHealth < PLAYER_MAX_HEALTH) {
            let rndSpawnSupply = Phaser.Math.Between(1, SUPPLY_MAXRAND);
            if (rndSpawnSupply === SUPPLY_MAXRAND) {
                this.spawnHealthSupply(x, y);
            }
        }
    }

    spawnHealthSupply(x, y)
    {
        let self = this;

        let supply = this.physics.add.sprite(x, y, 'heart');

        supply.setGravityY(200);
        supply.setGravityX(Phaser.Math.Between(-100, 100));
        supply.setCollideWorldBounds(true);
        supply.setBounce(0.5, 0.5);

        self.time.addEvent({
            delay: 5000,
            loop: false,
            callback: function() {
                self.spawnPuff(supply.x, supply.y);
                supply.destroy();
            },
            callbackScope: self
        });

        this.physics.add.collider(supply, this.platforms);

        this.physics.add.collider(this.player, supply, function() {
            if (self.playerHealth < PLAYER_MAX_HEALTH) {
                self.hearts[self.playerHealth].setVisible(true);
                self.playerHealth++;
                self.sndUp.play();
            }

            supply.destroy();
        });
    }

    spawnPuff(x, y)
    {
        let puff = this.physics.add.sprite(x, y, 'puff');
        puff.setScale(2.0, 2.0);
        puff.anims.play('puff', true);

        puff.on('animationcomplete', function() {
            puff.destroy();
        });
    }

    getFormattedGameTime()
    {
        let curms = (this.tmGameTime.current - this.tmGameTime.start);
        let cursecs = Math.round((curms % 60000) / 1000);
        let curmins = Math.floor(curms / 60000).toFixed(0);

        if (cursecs >= 60) {
            cursecs = 0;
            curmins++;
        }

        return ((curmins < 10) ? '0' + curmins : curmins) + ':' + ((cursecs < 10) ? '0' + cursecs : cursecs);
    }
}

const gameconfig = {
    type: Phaser.AUTO,
    scene: TestGame,
    physics: {
        default: 'arcade',
        arcade: {
            gravity: { y: 0 },
            debug: true
        }
    },
    scale: {
        mode: Phaser.Scale.FIT,
        autoCenter: Phaser.Scale.CENTER_BOTH,
        width: 1024,
        height: 768
    }
};
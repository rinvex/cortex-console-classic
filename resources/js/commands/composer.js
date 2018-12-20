let Command = require('./command');

class Composer extends Command {
    match(name) {
        return name === 'composer';
    }

    call(cmd) {
        cmd.command = `composer --command="${this.addslashes(cmd.rest)}"`;
        super.call(cmd);
    }
}

module.exports = Composer;

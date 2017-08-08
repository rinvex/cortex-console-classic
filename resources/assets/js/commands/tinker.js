let Command = require('./command');

class Tinker extends Command {
    match(name) {
        return name === 'tinker';
    }

    call(cmd) {
        cmd.command = `tinker --command="${this.addslashes(cmd.rest)}"`;
        super.call(cmd);
    }
}

module.exports = Tinker;

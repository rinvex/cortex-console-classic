let Command = require('./command');

class Help extends Command {
    match(name) {
        return ['list', 'help'].includes(name);
    }

    call() {
        this.api.echo(this.api.options.helpInfo);
        this.api.serverInfo();
    }
}

module.exports = Help;

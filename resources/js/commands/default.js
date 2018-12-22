let Command = require('./command');

class Default extends Command {
    match(name) {
        return ['list', 'help'].includes(name) === false;
    }
}

module.exports = Default;

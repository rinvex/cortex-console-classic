let CodeMirror = require('codemirror');
require('codemirror/addon/dialog/dialog');
require('codemirror/addon/search/searchcursor');
require('codemirror/addon/mode/loadmode');
require('codemirror/addon/edit/matchbrackets');
require('codemirror/addon/display/fullscreen');
require('codemirror/mode/clike/clike');
require('codemirror/mode/meta');
require('codemirror/keymap/vim');
require('codemirror/mode/php/php');
require('codemirror/mode/css/css');
require('codemirror/mode/javascript/javascript');
require('codemirror/mode/htmlmixed/htmlmixed');
require('codemirror/mode/xml/xml');
let Command = require('./command');

class Vi extends Command {
    constructor(api, options) {
        super(api, options);
        const textarea = $('<textarea style="display: none;"></textarea>')
            .appendTo(document.body)
            .get(0);
        this.editor = CodeMirror.fromTextArea(textarea, {
            lineNumbers: true,
            matchBrackets: true,
            keyMap: 'vim',
            showCursorWhenSelecting: true,
            theme: 'monokai',
        });
        this.editor.getWrapperElement().className += ' CodeMirror-fullscreen';
        $(this.editor.getWrapperElement()).hide();
    }

    match(name) {
        return name === 'vi';
    }

    call(cmd) {
        this.api.$term.pause();
        this.makeRequest(cmd.command).then(
            response => {
                const path = cmd.rest;
                const editor = this.editor;
                const matches = path.match(/.+\.([^.]+)$/);
                let info;
                let mode;
                let spec;
                if (matches !== null && matches.length > 0) {
                    info = CodeMirror.findModeByExtension(matches[1]);
                    if (info) {
                        mode = info.mode;
                        spec = info.mime;
                    }
                } else if (/\//.test(path)) {
                    info = CodeMirror.findModeByMIME(path);
                    if (info) {
                        mode = info.mode;
                        spec = info.mime;
                    }
                }

                if (['htmlmixed', 'css', 'javascript', 'php'].includes(mode) === false) {
                    mode = 'php';
                    spec = 'application/x-httpd-php';
                }

                if (mode) {
                    editor.setOption('mode', spec);
                    CodeMirror.autoLoadMode(editor, mode);
                }
                $(editor.getWrapperElement()).show();
                const doc = editor.getDoc();
                const cm = doc.cm;
                doc.setValue(response.result);
                doc.setCursor(0);
                editor.focus();
                cm.focus();
                const save = () => {
                    const value = JSON.stringify(doc.getValue());
                    cmd.command += ` --text=${value}`;
                    this.makeRequest(cmd.command).then(() => {}, () => {});
                };
                const quit = () => {
                    $(editor.getWrapperElement()).hide();
                    this.api.$term.resume();
                    this.api.$term.focus();
                };
                CodeMirror.Vim.defineEx('q', 'q', () => {
                    quit();
                });
                CodeMirror.Vim.defineEx('w', 'w', () => {
                    save();
                });
                CodeMirror.Vim.defineEx('wq', 'wq', () => {
                    save();
                    quit();
                });
            },
            response => {
                this.api.loading.hide();
                this.api.echo(response.result);
                this.api.serverInfo();
            }
        );
    }
}

module.exports = Vi;
